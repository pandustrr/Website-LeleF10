@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Profil Saya</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            <!-- Role -->
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Role</h2>
                    <p class="mt-1 text-gray-600 capitalize">{{ $user->role }}</p>
                </div>
            </div>

            <!-- Username -->
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Username</h2>
                    <p class="mt-1 text-gray-600">{{ $user->username }}</p>
                </div>
                <a href="{{ route('profile.edit', ['field' => 'username']) }}"
                   class="text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Tempat & Tanggal Lahir</h2>
                    <p class="mt-1 text-gray-600">{{ $user->tempat_tanggal_lahir }}</p>
                </div>
                <a href="{{ route('profile.edit', ['field' => 'tempat_tanggal_lahir']) }}"
                   class="text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
            </div>

            <!-- Alamat -->
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Alamat</h2>
                    <p class="mt-1 text-gray-600 whitespace-pre-line">{{ $user->alamat }}</p>
                </div>
                <a href="{{ route('profile.edit', ['field' => 'alamat']) }}"
                   class="text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
            </div>

            <!-- Nomor Telepon -->
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Nomor Telepon</h2>
                    <p class="mt-1 text-gray-600">{{ $user->nomor_telepon }}</p>
                </div>
                <a href="{{ route('profile.edit', ['field' => 'nomor_telepon']) }}"
                   class="text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
            </div>

            <!-- Password -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Password</h2>
                    <p class="mt-1 text-gray-600">••••••••</p>
                </div>
                <a href="{{ route('profile.edit', ['field' => 'password']) }}"
                   class="text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
