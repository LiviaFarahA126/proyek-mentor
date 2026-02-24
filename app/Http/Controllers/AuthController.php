<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** GET /login */
    public function showLogin()
    {
        if (session('dosen')) return redirect('/dashboard');
        if (session('mahasiswa')) return redirect('/krs');
        return view('auth.login');
    }

    /**
     * POST /login/dosen
     *
     * PROBLEM: Password di DB kemungkinan plain text (belum di-hash).
     * Solusi: cek plain text dulu, kalau cocok → otomatis upgrade ke Bcrypt.
     * Kalau sudah Bcrypt → pakai Hash::check normal.
     */
    public function loginDosen(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $dosen = Dosen::where('email', $request->email)->first();

        if (!$dosen) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        $passwordValid = false;

        // Cek apakah password sudah di-hash Bcrypt
        if (str_starts_with($dosen->password, '$2y$') || str_starts_with($dosen->password, '$2b$')) {
            // Sudah Bcrypt → pakai Hash::check
            $passwordValid = Hash::check($request->password, $dosen->password);
        } else {
            // Plain text → bandingkan langsung, lalu upgrade ke Bcrypt
            if ($dosen->password === $request->password) {
                $passwordValid = true;
                // Upgrade otomatis ke Bcrypt supaya next login aman
                $dosen->update(['password' => Hash::make($request->password)]);
            }
        }

        if (!$passwordValid) {
            return back()->with('error', 'Password salah.');
        }

        session([
            'dosen' => [
                'id_dosen'   => $dosen->id_dosen,
                'nama_dosen' => $dosen->nama_dosen,
                'email'      => $dosen->email,
                'role'       => 'dosen',
            ]
        ]);

        return redirect('/dashboard');
    }

    /** POST /login/mahasiswa — pakai id_mahasiswa + nim */
    public function loginMahasiswa(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required',
            'nim'          => 'required',
        ]);

        $mhs = Mahasiswa::where('id_mahasiswa', $request->id_mahasiswa)
                        ->where('nim', $request->nim)
                        ->first();

        if (!$mhs) {
            return back()->with('error', 'ID Mahasiswa atau NIM tidak cocok.');
        }

        session([
            'mahasiswa' => [
                'id_mahasiswa'   => $mhs->id_mahasiswa,
                'nama_mahasiswa' => $mhs->nama_mahasiswa,
                'nim'            => $mhs->nim,
                'role'           => 'mahasiswa',
            ]
        ]);

        return redirect('/krs');
    }

    /** GET /logout */
    public function logout()
    {
        session()->forget(['dosen', 'mahasiswa']);
        return redirect('/login');
    }
}