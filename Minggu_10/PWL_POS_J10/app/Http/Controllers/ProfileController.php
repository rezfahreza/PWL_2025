<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // ambil user yang sedang login
        $activeMenu = 'profile'; // set menu yang aktif
        $breadcrumb = (object) [
            'title' => 'Profil Saya',
            'list'  => ['Home', 'Profile Saya']
        ];
 
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
 
        return view('profile.index', compact('user', 'activeMenu', 'breadcrumb', 'page'));
    }
 
    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit_profile', compact('user'));
    }
 
 
    public function updateProfile(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
 
        $user = Auth::user();
 
        // Simpan file baru
        if ($request->hasFile('photo')) {
            $filename = 'photo_' . $user->user_id . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = public_path('profile_photo');
            $request->file('photo')->move($path, $filename);

            $user->photo = $filename;
            $user->save(); 
        }
 
        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
