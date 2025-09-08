<?php

namespace App\Http\Controllers\Autentikasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use Auth;
use Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class OAuthController extends Controller
{
    use AuthenticatesUsers;

    public function redirect(){
        $queries = http_build_query([
            'client_id' => config('services.oauth_server.client_id'),
            'redirect_uri' => config('services.oauth_server.redirect'),
            'response_type' => 'code',
        ]);
        return redirect(config('services.oauth_server.uri') . '/oauth/authorize?' . $queries);
    }

    public function callback(Request $request)
    {
        
        $response = Http::withoutVerifying()->post(config('services.oauth_server.uri') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.oauth_server.client_id'),
            'client_secret' => config('services.oauth_server.client_secret'),
            'redirect_uri' => config('services.oauth_server.redirect'),
            'code' => $request->code
        ]);

        $response = $response->json();

        $this->authAfterSso($response);
        
        if (!isset($response['access_token'])) {
            return redirect('/');
        }

        return redirect()->intended();
    }

    //digunakan untuk transfer data user ke client

    protected function authAfterSso($response){
        //dd($response);
        if (!isset($response['access_token'])) {
            return redirect('/');
        }
        $tokenData = $response;
        $response = Http::withoutVerifying()->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $response['access_token']
        ])->get(config('services.oauth_server.uri') . '/api/user');

        if ($response->status() === 200) {
            $SSOUser = $response->json();
        }else return redirect('/');
        
        //echo $SSOUser['unit'];
        //echo $SSOUser['staff'];
        $role=unserialize($SSOUser['role']);
        $perm= Permission::where('name','adminlte.darkmode.toggle')->orWhere('name','logout.perform')->orWhere('name','home.index')->orWhere('name','login.show')->pluck('id','id')->all();
        $admin = Permission::pluck('id','id')->all();
        foreach($role as $r){  
            $r=trim($r);
            $rl = Role::where(['name' => $r])->first();
            if($rl){
                if($r=="admin"){
                    $rl->syncPermissions($admin);
                }
            }else{
                $rl = Role::create(['name' => $r]);
                $rl->syncPermissions($perm);
            }
           
            
        }
        //dd($SSOUser);
        

        
        $users  =   User::where(['username' => $SSOUser['username']])->first();
        if($users){
            Auth::login($users,true);
            Session::flush();        
            Auth::logout();
            
            Auth::login($users,true);
            $users->unit = $SSOUser['unit'];
            $users->staff = $SSOUser['staff'];
            $users->save();
            
            \DB::table('sessions')
            ->where('user_id', $users->id)
            ->where('id', '!=', \Session::getId())->delete();
            
            // simpan/refresh token SSO
            try {
                $users->token()?->delete();
                $users->token()->create([
                    'access_token' => $tokenData['access_token'],
                    'expires_in' => $tokenData['expires_in'] ?? null,
                    'refresh_token' => $tokenData['refresh_token'] ?? null,
                ]);
            } catch (\Throwable $e) {}
            
            \Auth::user()->syncRoles([]);
            \Auth::user()->syncRoles($role);
            //unserialize
            
            // arahkan ke setup profil jika belum lengkap
            try {
                if (is_null($users->profile_completed_at)) {
                    return redirect()->route('profile.setup.show');
                }
            } catch (\Throwable $e) {}
            
        }else{
            $users = User::create([
                'name'   => $SSOUser['name'],
                'email'   => $SSOUser['email'],
                'username'   => $SSOUser['username'],
                'password'  => Hash::make(Str::random(11)),
                'unit'   => $SSOUser['unit'],
                'staff'   => $SSOUser['staff'],
                'status' => 1
            ]);

            // jika mahasiswa (staff=999), buat data students nim=username
            try {
                if (isset($SSOUser['staff']) && (string)$SSOUser['staff'] === '999') {
                    \DB::table('students')->updateOrInsert(
                        ['user_id' => $users->id],
                        [
                            'nim' => $SSOUser['username'],
                            'department_id' => null,
                            'study_program_id' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            } catch (\Throwable $e) {
                // abaikan jika tabel belum tersedia
            }
            if( $SSOUser['staff'] == 3 || $SSOUser['staff'] == 4 ){
                $users->syncRoles(3);
            }else $users->syncRoles(2);
            Auth::login($users,true);


            \Auth::user()->syncRoles([]);
            \Auth::user()->syncRoles($role);

            //Komen Untuk Mengijinkan Login Lebih dari 1 device
            \DB::table('sessions')
            ->where('user_id', $users->id)
            ->where('id', '!=', \Session::getId())->delete();

            // simpan token SSO pada user baru
            try {
                $users->token()?->delete();
                $users->token()->create([
                    'access_token' => $tokenData['access_token'],
                    'expires_in' => $tokenData['expires_in'] ?? null,
                    'refresh_token' => $tokenData['refresh_token'] ?? null,
                ]);
            } catch (\Throwable $e) {}

            // redirect pertama kali ke setup profil
            try { return redirect()->route('profile.setup.show'); } catch (\Throwable $e) {}
        }
        
        
    }
	
	public function refresh(Request $request)
    {
        //dd($request->user()->token->refresh_token);
        $response = Http::withoutVerifying()->post(config('services.oauth_server.uri') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->user()->token->refresh_token,
            'client_id' => config('services.oauth_server.client_id'),
            'client_secret' => config('services.oauth_server.client_secret'),
            'redirect_uri' => config('services.oauth_server.redirect'),
        ]);
        //dd($response);
        if ($response->status() !== 200) {
            $request->user()->token()->delete();

            return redirect('/')
                ->withStatus('Authorization failed from OAuth server.');
        }else $this->ssoLogout($request);

        $response = $response->json();
        $request->user()->token()->update([
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']
        ]);

        return redirect('/');
    }

}
