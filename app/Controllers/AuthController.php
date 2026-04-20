<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'token_balance' => 5 // Bonus 5 token untuk user baru
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('/stock')->with('success', 'Registrasi berhasil, silakan login.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal registrasi.');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();

        // Ambil input identity (bisa email atau username)
        $identity = $this->request->getPost('login_identity');
        $password = (string) $this->request->getPost('password');

        // Cari user berdasarkan email ATAU username
        $user = $userModel->groupStart()
            ->where('email', $identity)
            ->orWhere('username', $identity)
            ->groupEnd()
            ->first();

        if ($user && password_verify($password, $user->password)) {
            session()->set([
                'user_id' => $user->id,
                'username' => $user->username,
                'token_balance' => $user->token_balance,
                'is_logged' => true
            ]);
            return redirect()->to('/stock')->with('success', 'Selamat datang kembali!');
        }

        return redirect()->back()->with('error', 'Username/Email atau Password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}