<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of users as cards.
     */
    public function index()
    {
        $user = auth()->user();
        return redirect()->route('profile.show', $user->username);
    }

    /**
     * Show the form for editing the user profile.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('User.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
            'no_telp' => 'nullable|string|max:20',
            'lokasi' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
            'path_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('path_foto')) {
            // Delete old photo if exists
            if ($user->path_foto) {
                $oldPath = storage_path('app/public/' . $user->path_foto);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            // Store new photo
            $path = $request->file('path_foto')->store('users', 'public');
            $validated['path_foto'] = $path;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Display the specified user profile.
     */
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('User.show', compact('user'));
    }
}

