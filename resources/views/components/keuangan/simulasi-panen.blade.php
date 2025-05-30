<div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-500">
    <h2 class="text-lg font-semibold mb-4">Simulasi Harga Panen</h2>

    <!-- Dropdown Pilihan Panen -->
    <div class="mb-4">
        <form method="GET" action="{{ route('keuangan') }}" id="panenForm">
            <input type="hidden" name="siklus_id" value="{{ $siklusAktif->id ?? '' }}">

            <label class="block text-gray-700 mb-2">Pilih Data Panen</label>
            <select name="panen_id" onchange="this.form.submit()" class="form-select w-full rounded-md border-gray-300">
                @if($panens && count($panens) > 0)
                    @foreach($panens as $panen)
                        <option value="{{ $panen->id }}"
                            {{ $selectedPanen && $selectedPanen->id == $panen->id ? 'selected' : '' }}>
                            @if($panen->tanggal && $panen->kuantitas)
                                {{ \Carbon\Carbon::parse($panen->tanggal)->format('d/m/Y') }} - {{ $panen->kuantitas }} kg
                                @if($panen->harga_jual)
                                    (Rp {{ number_format($panen->harga_jual, 0, ',', '.') }}/kg)
                                @endif
                            @else
                                Data Panen Tidak Lengkap
                            @endif
                        </option>
                    @endforeach
                @else
                    <option value="">Tidak ada data panen</option>
                @endif
            </select>
        </form>
    </div>

    @if($selectedPanen && $selectedPanen->tanggal && $selectedPanen->kuantitas)
        <!-- Informasi Kuantitas -->
        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Tanggal Panen</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($selectedPanen->tanggal)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kuantitas</p>
                    <p class="font-medium">{{ $selectedPanen->kuantitas }} kg</p>
                </div>
                @if($selectedPanen->harga_jual)
                <div>
                    <p class="text-sm text-gray-600">Harga Saat Ini</p>
                    <p class="font-medium">Rp {{ number_format($selectedPanen->harga_jual, 0, ',', '.') }}/kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Pemasukan</p>
                    <p class="font-medium text-green-600">Rp {{ number_format($selectedPanen->total_harga, 0, ',', '.') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Form Simulasi Harga -->
        <form id="simulasiForm" action="{{ route('panen.simulasi') }}" method="POST">
            @csrf
            <input type="hidden" name="siklus_id" value="{{ $siklusAktif->id ?? '' }}">
            <input type="hidden" name="panen_id" value="{{ $selectedPanen->id }}">

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Harga Jual per kg (Rp)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-600">Rp</span>
                    <input type="number" name="harga_jual" class="form-input w-full pl-10"
                           value="{{ old('harga_jual', $selectedPanen->harga_jual) }}"
                           min="1000" step="500" required
                           placeholder="Contoh: 50000">
                </div>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div>
                    @if($selectedPanen->harga_jual && $selectedPanen->updated_at)
                        <p class="text-sm text-gray-600">Terakhir diperbarui:
                            {{ \Carbon\Carbon::parse($selectedPanen->updated_at)->format('d/m/Y') }}
                        </p>
                    @endif
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan Harga
                </button>
            </div>
        </form>
    @else
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-700">Data panen tidak tersedia atau tidak lengkap</h3>
                    <p class="text-sm text-red-600 mt-1">Silakan input data panen terlebih dahulu di menu Produksi.</p>
                </div>
            </div>
        </div>
    @endif
</div>

@if(session('success'))
    <!-- Notifikasi Sukses -->
    <div id="successNotification" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center animate-fade-in-up" role="alert">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('successNotification').remove()" class="ml-4 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }
    </style>

    <script>
        // Hilangkan notifikasi setelah 5 detik
        setTimeout(() => {
            const notification = document.getElementById('successNotification');
            if (notification) {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    </script>
@endif
