@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css">
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Masuk ke akun Anda
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">
                <!-- Ganti Ilustrasi SVG dengan Gambar Logo -->
                <div
                    class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-500 to-indigo-700 p-8 items-center justify-center">
                    <div class="text-center">
                        <img src="{{ asset('images/tanpa_bg.png') }}" alt="Logo Sistem" class="w-64 h-auto mx-auto">
                        <h3 class="mt-6 text-xl font-medium text-white">Sistem Manajemen Budidaya Ikan Lele</h3>
                        <p class="mt-2 text-indigo-100">Masuk untuk mengakses fitur</p>
                    </div>
                </div>

                <!-- Form Login -->
                <div class="w-full md:w-1/2 p-8 sm:p-10">
                    @if (session('status'))
                        <div class="mb-4 px-4 py-3 rounded text-green-700 bg-green-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="space-y-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-account-outline text-gray-400"></i>
                                </div>
                                <input id="username" name="username" type="text" value="{{ old('username') }}" required
                                    autofocus
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 @error('username') border-red-500 @enderror"
                                    placeholder="Masukkan username">
                            </div>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-lock-outline text-gray-400"></i>
                                </div>
                                <input id="password" name="password" type="password" required
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 @error('password') border-red-500 @enderror"
                                    placeholder="Masukkan password">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                <i class="mdi mdi-login mr-2"></i> Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
