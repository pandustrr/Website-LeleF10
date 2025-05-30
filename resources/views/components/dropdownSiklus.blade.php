<div class="relative w-full md:w-auto">
    <!-- Dropdown Button -->
    <button id="dropdownSiklusButton" data-dropdown-toggle="dropdownSiklus" data-dropdown-placement="bottom"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-center gap-2 transition-colors duration-200 min-w-[180px] h-[42px]"
        type="button">
        <span class="truncate flex items-center justify-center">
            {{ $siklusAktif->nama_siklus ?? 'Pilih Siklus' }}
        </span>
        <svg class="w-3 h-3 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div id="dropdownSiklus" class="z-20 hidden bg-white rounded-lg shadow-lg w-80 border border-gray-200">
        <!-- Siklus List -->
        <div class="max-h-72 overflow-y-auto py-2">
            @foreach($semuaSiklus as $siklus)
            <div class="flex items-center justify-between px-4 py-3 text-sm text-gray-800 hover:bg-blue-50 transition-colors duration-150 h-12 border-b border-gray-100">
                <a href="{{ route('produksi', ['siklus_id' => $siklus->id]) }}"
                   class="truncate text-center w-full flex-1">
                    {{ $siklus->nama_siklus }}
                </a>

                <div class="flex items-center ml-2 space-x-2">
                    <!-- Edit Button -->
                    <button onclick="tampilkanModalEditSiklus({{ $siklus->id }}, '{{ $siklus->nama_siklus }}')"
                        class="text-blue-500 hover:text-blue-700 p-1 rounded-full hover:bg-blue-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>

                    @if($siklusAktif && $siklus->id === $siklusAktif->id)
                    <svg class="w-4 h-4 text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 10 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Add Siklus Form -->
        <div class="border-t border-gray-200 p-4 bg-gray-50 rounded-b-lg">
            <form action="{{ route('siklus.store') }}" method="POST" class="flex gap-3 items-center">
                @csrf
                <input type="text" name="nama_siklus"
                    class="flex-grow px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-800 placeholder-gray-400 bg-white"
                    placeholder="Nama siklus baru" required>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200 whitespace-nowrap">
                    Tambah
                </button>
            </form>

            <!-- Delete Current Siklus -->
            @if($siklusAktif && count($semuaSiklus) > 1)
            <form action="{{ route('siklus.destroy', $siklusAktif->id) }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus siklus ini?')"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm text-red-600 hover:text-red-800 transition-colors duration-200">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h16M7 8v8m4-8v8M7 1h4a1 1 0 0 1 1 1v3H6V2a1 1 0 0 1 1-1ZM3 5h12v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5Z"/>
                    </svg>
                    Hapus Siklus Ini
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit Siklus -->
<div id="modalEditSiklus" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Nama Siklus</h3>
                <div class="mb-4">
                    <form id="formEditSiklus" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="siklus_id" id="editSiklusId">
                        <label for="editNamaSiklus" class="block text-sm font-medium text-gray-700">Nama Siklus</label>
                        <input type="text" name="nama_siklus" id="editNamaSiklus"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </form>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('formEditSiklus').submit()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="tutupModalEditSiklus()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan modal edit siklus
    function tampilkanModalEditSiklus(siklusId, siklusNama) {
        const form = document.getElementById('formEditSiklus');
        form.action = `/siklus/${siklusId}`;
        document.getElementById('editSiklusId').value = siklusId;
        document.getElementById('editNamaSiklus').value = siklusNama;
        document.getElementById('modalEditSiklus').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal edit siklus
    function tutupModalEditSiklus() {
        document.getElementById('modalEditSiklus').classList.add('hidden');
    }
</script>
