@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Profile Saya</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Pengguna -->
            <div class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Username</h2>
                    <p class="mt-1 text-gray-600">{{ $user->username }}</p>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Tempat & Tanggal Lahir</h2>
                    <p class="mt-1 text-gray-600">{{ $user->tempat_tanggal_lahir }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Alamat</h2>
                    <p class="mt-1 text-gray-600 whitespace-pre-line">{{ $user->alamat }}</p>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Nomor Telepon</h2>
                    <p class="mt-1 text-gray-600">{{ $user->nomor_telepon }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('profile.edit') }}"
               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection
