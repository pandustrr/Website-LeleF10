<div class="relative">
    <!-- Header Modal -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
            @isset($pakan) Edit Data Pakan @else Tambah Data Pakan @endisset
        </h3>
        <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Form Edit -->
    <form action="{{ isset($pakan) ? route('pakan.update', $pakan->id) : route('pakan.store') }}" method="POST">
        @csrf
        @isset($pakan)
            @method('PUT')
        @endisset

        <!-- Hidden siklus_id field -->
        <input type="hidden" name="siklus_id" value="{{ $pakan->siklus_id ?? request('siklus_id') }}">

        <div class="space-y-4">
            <!-- Field Tanggal -->
            <div>
                <label for="edit-tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="edit-tanggal" value="{{ $pakan->tanggal ?? old('tanggal') }}"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                    required>
                @error('tanggal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field Kuantitas -->
            <div>
                <label for="edit-kuantitas" class="block text-sm font-medium text-gray-700 mb-1">Kuantitas (kg)</label>
                <input type="number" name="kuantitas" id="edit-kuantitas"
                    value="{{ $pakan->kuantitas ?? old('kuantitas') }}"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                    min="1" required>
                @error('kuantitas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field Tipe -->
            <div>
                <label for="edit-tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pakan</label>
                <select name="tipe" id="edit-tipe"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>
                    @foreach(array_keys(App\Models\Pakan::HARGA_PAKAN) as $tipe)
                        <option value="{{ $tipe }}"
                            @if(isset($pakan) && $pakan->tipe == $tipe) selected
                            @elseif(old('tipe') == $tipe) selected @endif>
                            {{ $tipe }}
                        </option>
                    @endforeach
                </select>
                @error('tipe')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" onclick="closeEditModal()"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

