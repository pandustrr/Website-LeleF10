<div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-500">
    <h2 class="text-lg font-semibold mb-4">Simulasi Harga Panen</h2>

    <!-- Informasi Kuantitas -->
    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Kuantitas Panen (kg)</label>
        @if(isset($panen) && $panen->kuantitas)
            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                <span class="font-medium">{{ $panen->kuantitas }} kg</span>
            </div>
        @else
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-red-700">Data panen belum tersedia</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Form Simulasi Harga -->
    <form id="simulasiForm" action="{{ route('panen.simulasi') }}" method="POST">
        @csrf
        <input type="hidden" name="siklus_id" value="{{ $siklusAktif->id ?? '' }}">

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Harga Jual per kg (Rp)</label>
            <input type="number" name="harga_jual" class="form-input w-full"
                   value="{{ old('harga_jual', $panen->harga_jual ?? 50000) }}" min="1000" required>
        </div>

        <div class="mt-4">
            <button type="button" onclick="validateForm()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Simpan Harga Jual
            </button>
        </div>
    </form>
</div>

<!-- Modal Peringatan -->
<div id="warningModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Data Tidak Lengkap</h3>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Anda belum memiliki data panen. Silakan input data panen terlebih dahulu di menu Produksi.</p>
            </div>
            <div class="mt-5">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi validasi form
    function validateForm() {
        const kuantitasPanen = {{ $panen->kuantitas ?? 0 }};
        const warningModal = document.getElementById('warningModal');

        if(kuantitasPanen <= 0) {
            warningModal.classList.remove('hidden');
            warningModal.classList.add('flex');
        } else {
            document.getElementById('simulasiForm').submit();
        }
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        const warningModal = document.getElementById('warningModal');
        warningModal.classList.add('hidden');
        warningModal.classList.remove('flex');
    }
</script>
@endpush
