@php($user = auth()->user())
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Setup Profil</title>
    <style>
        .wrap{max-width:720px;margin:40px auto;padding:24px;border:1px solid #e6edf5;border-radius:12px;background:#fff;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial;}
        .row{display:flex;gap:16px;}
        .col{flex:1}
        label{display:block;font-weight:600;margin:.5rem 0 .25rem}
        input,select{width:100%;padding:.6rem .7rem;border:1px solid #cbd5e1;border-radius:8px}
        .muted{color:#64748b}
        .summary{margin-bottom:16px}
        .btn{display:inline-block;padding:.65rem 1rem;border-radius:10px;background:#2563eb;color:#fff;border:1px solid #1d4ed8}
    </style>
    </head>
<body>
<div class="wrap">
    <h2>Lengkapi Profil</h2>
    <div class="summary">
        <div>Nama: <b>{{ $user->name }}</b></div>
        <div>Email: <b>{{ $user->email }}</b></div>
        <div>Username: <b>{{ $user->username }}</b></div>
        @if ((string)$user->staff === '999')
            <div class="muted">Tipe: Mahasiswa</div>
        @else
            <div class="muted">Tipe: Staf</div>
        @endif
    </div>

    <form method="post" action="{{ route('profile.setup.store') }}">
        @csrf
        @if ((string)$user->staff === '999')
            <label>NIM</label>
            <input type="text" value="{{ $user->username }}" disabled>
            <input type="hidden" name="nim" value="{{ $user->username }}">
            <div class="row">
                <div class="col">
                    <label>Jurusan</label>
                    <select name="department_id" required>
                        <option value="">-- Pilih --</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label>Prodi</label>
                    <select name="study_program_id" required>
                        <option value="">-- Pilih --</option>
                        @foreach($programs as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <label>NIP (opsional)</label>
            <input type="text" name="nip" value="{{ old('nip', $user->nip) }}">
            <div class="muted" style="margin:.5rem 0 1rem">Unit: {{ optional($user->getUnit)->nama }} — Jabatan: {{ optional($user->getStaff)->nama }}</div>
        @endif

        <label>No. HP</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required>

        <div style="margin-top:16px">
            <button class="btn" type="submit">Simpan</button>
        </div>
    </form>
</div>
</body>
</html>



