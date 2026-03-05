<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index($role)
    {
        $query = User::where('role', $role)->latest();
        
        if ($role === 'siswa') {
            $query->with('siswa');
        }

        $users = $query->paginate(10);
        return view("admin.user.{$role}.index", compact('users', 'role'));
    }

    public function create($role)
    {
        if ($role === 'siswa') {
            $tahunAjarans = \App\Models\TahunAjaran::all();
            $eskuls = \App\Models\Eskul::all();
            return view("admin.user.{$role}.create", compact('tahunAjarans', 'eskuls', 'role'));
        }
        return view("admin.user.{$role}.create", compact('role'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,guru,siswa'
        ];

        if ($request->role === 'siswa') {
            $rules['nis'] = 'required|string|unique:siswa,nis';
            $rules['tahun_ajaran_id'] = 'required|exists:tahun_ajaran,id';
            $rules['eskuls'] = 'required|array|min:1';
            $rules['eskuls.*'] = 'exists:eskul,id';
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'siswa') {
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nama' => $request->name,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
            ]);

            // PENGISIAN PIVOT TABLE ANGGOTA ESKUL
            if ($request->has('eskuls')) {
                $pivotData = [];
                foreach ($request->eskuls as $eskulId) {
                    $pivotData[$eskulId] = [
                        'id' => Str::uuid()->toString(), // Inject UUID karena method attach() tidak trigger model events
                        'tahun_ajaran_id' => $request->tahun_ajaran_id,
                        'status' => 'aktif',
                    ];
                }
                $siswa->eskul()->attach($pivotData);
            }
        }

        return redirect()->route('admin.users.index', $request->role)->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $role = $user->role;
        if ($role === 'siswa') {
            $tahunAjarans = \App\Models\TahunAjaran::all();
            $eskuls = \App\Models\Eskul::all();
            return view("admin.user.{$role}.edit", compact('user', 'tahunAjarans', 'eskuls', 'role'));
        }
        return view("admin.user.{$role}.edit", compact('user', 'role'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if ($user->role === 'siswa') {
            $siswa = $user->siswa;
            $siswaId = $siswa ? $siswa->id : null;
            $rules['nis'] = 'required|string|unique:siswa,nis,' . $siswaId;
            $rules['tahun_ajaran_id'] = 'required|exists:tahun_ajaran,id';
            $rules['eskuls'] = 'required|array|min:1';
            $rules['eskuls.*'] = 'exists:eskul,id';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($user->role === 'siswa') {
            $siswa = $user->siswa()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $request->nis,
                    'nama' => $request->name,
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                ]
            );

            // UPDATE PIVOT TABLE ANGGOTA ESKUL
            if ($request->has('eskuls')) {
                $pivotData = [];
                foreach ($request->eskuls as $eskulId) {
                    $pivotData[$eskulId] = [
                        'id' => Str::uuid()->toString(), // Inject UUID
                        'tahun_ajaran_id' => $request->tahun_ajaran_id,
                        'status' => 'aktif',
                    ];
                }
                // sync() akan memperbarui data. Jika eskul di-uncheck, otomatis terhapus dari tabel anggota_eskul
                $siswa->eskul()->sync($pivotData); 
            } else {
                $siswa->eskul()->sync([]); // Kosongkan eskul jika tidak ada yang dicentang
            }
        }

        return redirect()->route('admin.users.index', $user->role)->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $role = $user->role;
        
        // Opsional: Bersihkan pivot relasi agar tidak jadi orphan data sebelum user dihapus (jika tidak ada cascade delete di database)
        if ($role === 'siswa' && $user->siswa) {
            $user->siswa->eskul()->sync([]); 
        }

        $user->delete();
        return redirect()->route('admin.users.index', $role)->with('success', 'User berhasil dihapus.');
    }
}