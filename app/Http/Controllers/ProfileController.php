<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile', ['content' => 'components.profile.show', 'user' => $user]);
    }

    public function edit($field = null)
    {
        $user = Auth::user();
        $allowedFields = ['username', 'tempat_tanggal_lahir', 'alamat', 'nomor_telepon', 'password'];

        if ($field && !in_array($field, $allowedFields)) {
            abort(404);
        }

        return view('profile', [
            'content' => 'components.profile.edit',
            'user' => $user,
            'field' => $field
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $field = $request->input('field');

        $rules = [
            'username' => 'required|string|max:25|unique:users,username,'.$user->id,
            'tempat_tanggal_lahir' => 'required|string|max:50',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:15',
        ];

        // Pesan sukses yang spesifik
        $successMessages = [
            'username' => 'Username berhasil diperbarui!',
            'tempat_tanggal_lahir' => 'Tempat & tanggal lahir berhasil diperbarui!',
            'alamat' => 'Alamat berhasil diperbarui!',
            'nomor_telepon' => 'Nomor telepon berhasil diperbarui!',
            'password' => 'Password berhasil diubah!'
        ];

        if ($field === 'password') {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            if ($user->password !== $request->current_password) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }

            $user->password = $request->new_password;
            $user->save();

            return redirect()->route('profile.show')
                ->with('success', $successMessages['password']);
        }

        $validated = $request->validate([$field => $rules[$field]]);
        $user->update([$field => $validated[$field]]);

        return redirect()->route('profile.show')
            ->with('success', $successMessages[$field]);
    }
}
