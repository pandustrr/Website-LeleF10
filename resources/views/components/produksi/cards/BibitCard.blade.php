<div id="bibit-card"
    class="bg-white rounded-lg shadow p-3 hover:shadow-md transition-all duration-300 border border-gray-100">
    <div class="cursor-pointer" onclick="showTable('bibit')">
        <h2 class="text-md font-semibold mb-2 text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Bibit
        </h2>
    </div>
    <form action="{{ route('bibit.store') }}" method="POST">
        @csrf
        <input type="hidden" name="siklus_id" value="{{ $siklusAktif->id }}">
        <div class="space-y-2">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Bibit (kg)</label>
                <input type="number" name="kuantitas" required
                    class="w-full p-2 text-sm border border-gray-200 rounded-md">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Bibit</label>
                <select name="type" required class="w-full p-2 text-sm border border-gray-200 rounded-md">
                    <option value="">Pilih tipe</option>
                    <option value="Bibit Premium">Premium</option>
                    <option value="Bibit Standar">Standar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" required
                    class="w-full p-2 text-sm border border-gray-200 rounded-md">
            </div>
            <div class="pt-1">
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 rounded-md">
                    Simpan Bibit
                </button>
            </div>
        </div>
    </form>
</div>
