<div class="relative w-full md:w-auto">
    <!-- Dropdown Button (Blue) -->
    <button id="dropdownSiklusButton" data-dropdown-toggle="dropdownSiklus" data-dropdown-placement="bottom"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-center gap-2 transition-colors duration-200 min-w-[180px] h-[42px]"
        type="button">
        <span class="truncate flex items-center justify-center">{{ $siklusAktif->nama_siklus ?? 'Pilih Siklus' }}</span>
        <svg class="w-3 h-3 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div id="dropdownSiklus" class="z-20 hidden bg-white rounded-lg shadow-lg w-80 border border-gray-200">
        <!-- Siklus List -->
        <div class="max-h-72 overflow-y-auto py-2">
            @foreach($siklusList as $siklus)
            <a href="{{ route('produksi', ['siklus_id' => $siklus->id]) }}"
               class="flex items-center justify-between px-4 py-3 text-sm text-gray-800 hover:bg-blue-50 transition-colors duration-150 h-12 border-b border-gray-100">
                <span class="truncate text-center w-full">{{ $siklus->nama_siklus }}</span>
                @if($siklusAktif && $siklus->id === $siklusAktif->id)
                <svg class="w-4 h-4 text-blue-600 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 10 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                @endif
            </a>
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
            @if($siklusAktif)
            <form action="{{ route('siklus.destroy', $siklusAktif->id) }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit"
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
