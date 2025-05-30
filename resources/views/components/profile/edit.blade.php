<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit {{ str_replace('_', ' ', ucfirst($field)) }}</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="field" value="{{ $field }}">

        @if($field === 'username')
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username"
                       value="{{ old('username', $user->username) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

        @elseif($field === 'tempat_tanggal_lahir')
            <div class="mb-4">
                <label for="tempat_tanggal_lahir" class="block text-sm font-medium text-gray-700">Tempat & Tanggal Lahir</label>
                <input type="text" id="tempat_tanggal_lahir" name="tempat_tanggal_lahir"
                       value="{{ old('tempat_tanggal_lahir', $user->tempat_tanggal_lahir) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

        @elseif($field === 'alamat')
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alamat', $user->alamat) }}</textarea>
            </div>

        @elseif($field === 'nomor_telepon')
            <div class="mb-4">
                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon"
                       value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

        @elseif($field === 'password')
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" id="new_password" name="new_password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        @endif

        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('profile.show') }}"
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
