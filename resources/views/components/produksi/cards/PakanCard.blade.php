<div id="pakan-card"
    class="bg-white rounded-lg shadow p-3 hover:shadow-md transition-all duration-300 border border-gray-100">
    <div class="cursor-pointer" onclick="showTable('pakan')">
        <h2 class="text-md font-semibold mb-2 text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Pakan
        </h2>
    </div>
    <form action="{{ route('pakan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="siklus_id" value="{{ $siklusAktif->id }}">
        <div class="space-y-2">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Pakan (kg)</label>
                <input type="number" name="kuantitas" required
                    class="w-full p-2 text-sm border border-gray-200 rounded-md">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Pakan</label>
                <select name="tipe" required class="w-full p-2 text-sm border border-gray-200 rounded-md">
                    <option value="">Pilih tipe</option>
                    <option value="premium">Premium</option>
                    <option value="standar">Standar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" required
                    class="w-full p-2 text-sm border border-gray-200 rounded-md">
            </div>
            <div class="pt-1">
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 rounded-md">
                    Simpan Pakan
                </button>
            </div>
        </div>
    </form>
</div>
