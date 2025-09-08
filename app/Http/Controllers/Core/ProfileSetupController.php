<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileSetupController extends Controller
{
    public function show()
    {
        $departments = DB::table('departments')->select('id','name')->orderBy('name')->get();
        $programs = DB::table('study_programs')->select('id','name')->orderBy('name')->get();
        return view('profile.setup', compact('departments','programs'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $rules = [ 'phone' => 'required|string|max:20' ];
        if ((string)$user->staff === '999') {
            $rules['department_id'] = 'required|exists:departments,id';
            $rules['study_program_id'] = 'required|exists:study_programs,id';
        }
        $data = $request->validate($rules);

        // update phone (dan nip opsional untuk staf)
        $user->phone = $data['phone'];
        if ((string)$user->staff !== '999') {
            if ($request->filled('nip')) {
                $user->nip = $request->input('nip');
            }
        }
        $user->profile_completed_at = now();
        $user->save();

        // mahasiswa: simpan ke students
        if ((string)$user->staff === '999') {
            DB::table('students')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'nim' => $user->username,
                    'department_id' => $data['department_id'],
                    'study_program_id' => $data['study_program_id'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return redirect()->route('home.index');
    }
}



