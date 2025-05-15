<div id="panen-card"
    class="bg-white rounded-lg shadow p-3 hover:shadow-md transition-all duration-300 border border-gray-100 flex flex-col h-full">
    <!-- Header -->
    <div class="cursor-pointer" onclick="showTable('panen')">
        <h2 class="text-md font-semibold mb-2 text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Panen
        </h2>
    </div>

    <!-- Form dengan flex-grow untuk mengisi ruang -->
    <form action="{{ route('panen.store') }}" method="POST" class="flex flex-col flex-grow">
        @csrf
        <input type="hidden" name="siklus_id" value="{{ $siklusAktif->id }}">
        <div class="space-y-2">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Panen (kg)</label>
                <input type="number" name="kuantitas" required
                    class="w-full p-2 text-sm border border-gray-200 rounded-md">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" required
                    class="w-full p-2 text-sm border border-gray-200 rounded-md">
            </div>
        </div>

        <div class="flex-grow"></div>

        <div class="pt-4">
            <button type="submit"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 rounded-md transition-colors duration-300">
                Simpan Panen
            </button>
        </div>
    </form>
</div>
