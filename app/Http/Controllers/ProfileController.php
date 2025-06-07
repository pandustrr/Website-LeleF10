<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile', [
            'content' => 'components.profile.show',
            'user' => $user
        ]);
    }

    public function edit($field)
    {
        $user = Auth::user();
        $allowedFields = ['username', 'password', 'tempat_tanggal_lahir', 'alamat', 'nomor_telepon'];

        if (!in_array($field, $allowedFields)) {
            abort(404);
        }

        return view('profile', [
            'content' => 'components.profile.edit',
            'field' => $field,
            'user' => $user
        ]);
    }

    public function update(Request $request, $field)
    {
        $user = Auth::user();
        $allowedFields = ['username', 'password', 'tempat_tanggal_lahir', 'alamat', 'nomor_telepon'];

        if (!in_array($field, $allowedFields)) {
            abort(404);
        }

        $rules = [];
        $updateData = [];

        switch ($field) {
            case 'username':
                $rules = [
                    'username' => 'required|string|max:25|unique:users,username,' . $user->id,
                ];
                $updateData = ['username' => $request->username];
                break;

            case 'password':
                $rules = [
                    'new_password' => ['confirmed', Password::min(8)],
                ];
                $updateData = ['password' => $request->new_password];
                break;

            case 'tempat_tanggal_lahir':
                $rules = [
                    'tempat_tanggal_lahir' => 'required|string|max:50',
                ];
                $updateData = ['tempat_tanggal_lahir' => $request->tempat_tanggal_lahir];
                break;

            case 'alamat':
                $rules = [
                    'alamat' => 'required|string',
                ];
                $updateData = ['alamat' => $request->alamat];
                break;

            case 'nomor_telepon':
                $rules = [
                    'nomor_telepon' => 'required|string|max:15',
                ];
                $updateData = ['nomor_telepon' => $request->nomor_telepon];
                break;
        }

        $validated = $request->validate($rules);
        $user->update($updateData);

        return redirect()->route('profile.show')
            ->with('success', ucfirst(str_replace('_', ' ', $field)) . ' berhasil diperbarui!');
    }
}
