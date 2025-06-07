<!-- resources/views/components/profile/show.blade.php -->
<div class="bg-white rounded-xl shadow-lg p-8 max-w-4xl mx-auto my-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 border-b pb-4">Profil Saya</h1>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        <!-- Role -->
        <div class="flex items-center p-6 bg-gray-50 rounded-xl border border-gray-100">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-700">Role</h2>
                <p class="mt-1 text-gray-600 capitalize">{{ $user->role }}</p>
            </div>
            <div class="w-10 text-center">
                <span class="text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                </span>
            </div>
        </div>

        <!-- Username -->
        <div class="flex items-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-700">Username</h2>
                <p class="mt-1 text-gray-600">{{ $user->username }}</p>
            </div>
            <button data-modal-target="modal-username" data-modal-toggle="modal-username" class="text-blue-600 hover:text-blue-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
        </div>

        <!-- Password -->
        <div class="flex items-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-700">Password</h2>
                <p class="mt-1 text-gray-600">••••••••</p>
            </div>
            <button data-modal-target="modal-password" data-modal-toggle="modal-password" class="text-blue-600 hover:text-blue-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
        </div>

        <!-- Tempat & Tanggal Lahir -->
        <div class="flex items-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-700">Tempat & Tanggal Lahir</h2>
                <p class="mt-1 text-gray-600">{{ $user->tempat_tanggal_lahir }}</p>
            </div>
            <button data-modal-target="modal-tempat_tanggal_lahir" data-modal-toggle="modal-tempat_tanggal_lahir" class="text-blue-600 hover:text-blue-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
        </div>

        <!-- Alamat -->
        <div class="flex items-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-700">Alamat</h2>
                <p class="mt-1 text-gray-600 whitespace-pre-line">{{ $user->alamat }}</p>
            </div>
            <button data-modal-target="modal-alamat" data-modal-toggle="modal-alamat" class="text-blue-600 hover:text-blue-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
        </div>

        <!-- Nomor Telepon -->
        <div class="flex items-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-700">Nomor Telepon</h2>
                <p class="mt-1 text-gray-600">{{ $user->nomor_telepon }}</p>
            </div>
            <button data-modal-target="modal-nomor_telepon" data-modal-toggle="modal-nomor_telepon" class="text-blue-600 hover:text-blue-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
        </div>
    </div>
</div>

@include('components.profile.modal-username')
@include('components.profile.modal-password')
@include('components.profile.modal-tempat_tanggal_lahir')
@include('components.profile.modal-alamat')
@include('components.profile.modal-nomor_telepon')
