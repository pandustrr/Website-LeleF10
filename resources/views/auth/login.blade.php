@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css">
    <style>
        .bg-black-50 {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Mobile specific styles */
        @media (max-width: 767px) {
            .mobile-logo {
                display: block;
                margin: 0 auto 1.5rem;
                width: 120px;
                height: auto;
            }

            .mobile-title {
                text-align: center;
                margin-bottom: 1rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .login-btn {
                padding: 0.75rem;
            }
        }

        /* Desktop specific styles */
        @media (min-width: 768px) {
            .desktop-logo {
                display: block;
            }

            .mobile-logo {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-black-50 flex flex-col justify-center py-6 sm:px-6 lg:px-8">
        <!-- Mobile Logo -->
        <div class="md:hidden text-center">
            <img src="{{ asset('images/tanpa_bg.png') }}" alt="Logo Sistem" class="mobile-logo">
            <h3 class="text-lg font-medium text-gray-800 mobile-title">Sistem Manajemen Budidaya Ikan Lele</h3>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="text-center text-2xl md:text-3xl font-extrabold text-gray-900">
                Masuk ke akun Anda
            </h2>
        </div>

        <div class="mt-6 mx-4 sm:mx-auto sm:w-full sm:max-w-4xl">
            <div class="bg-white shadow-md sm:shadow-xl rounded-lg sm:rounded-2xl overflow-hidden flex flex-col md:flex-row">
                <!-- Gambar Logo (Desktop) -->
                <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-500 to-indigo-700 p-6 md:p-8 items-center justify-center">
                    <div class="text-center">
                        <img src="{{ asset('images/tanpa_bg.png') }}" alt="Logo Sistem" class="w-48 md:w-64 h-auto mx-auto desktop-logo">
                        <h3 class="mt-4 md:mt-6 text-lg md:text-xl font-medium text-white">Sistem Manajemen Budidaya Ikan Lele</h3>
                        <p class="mt-1 md:mt-2 text-indigo-100 text-sm md:text-base">Masuk untuk mengakses fitur</p>
                    </div>
                </div>

                <!-- Form Login -->
                <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-10 form-container">
                    <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-account-outline text-gray-400"></i>
                                </div>
                                <input id="username" name="username" type="text" value="{{ old('username') }}" required
                                    autofocus
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 md:py-3 @error('username') border-red-500 @enderror"
                                    placeholder="Masukkan username">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-lock-outline text-gray-400"></i>
                                </div>
                                <input id="password" name="password" type="password" required
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 md:py-3 @error('password') border-red-500 @enderror"
                                    placeholder="Masukkan password">
                            </div>
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
                                class="w-full flex justify-center py-2 md:py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out login-btn">
                                <i class="mdi mdi-login mr-2"></i> Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        const popupOptions = {
            customClass: {
                popup: 'text-sm rounded-xl p-4'
            },
            confirmButtonColor: '#6366f1',
            width: '320px'
        };

        @if ($errors->has('username') || $errors->has('password'))
            Swal.fire({
                icon: 'warning',
                title: 'Data tidak boleh kosong',
                text: 'Silakan isi semua kolom yang diperlukan',
                ...popupOptions
            });
        @endif

        @if ($errors->has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Data salah',
                text: '{{ $errors->first('error') }}',
                confirmButtonColor: '#ef4444',
                ...popupOptions
            });
        @endif
    </script>
@endpush
