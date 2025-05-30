<div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
    <h2 class="text-lg font-semibold mb-3">Simulasi Harga Panen</h2>

    <!-- Dropdown Pilihan Panen -->
    <div class="mb-3">
        <form method="GET" action="{{ route('keuangan') }}" id="panenForm">
            <input type="hidden" name="siklus_id" value="{{ $siklusAktif->id ?? '' }}">
            <select name="panen_id" onchange="this.form.submit()"
                    class="form-select w-full rounded-md border-gray-300 text-sm py-2">
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
        <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200 text-sm">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-gray-600 mb-1">Tanggal Panen</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($selectedPanen->tanggal)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Kuantitas</p>
                    <p class="font-medium">{{ $selectedPanen->kuantitas }} kg</p>
                </div>
                @if($selectedPanen->harga_jual)
                <div>
                    <p class="text-gray-600 mb-1">Harga Saat Ini</p>
                    <p class="font-medium">Rp {{ number_format($selectedPanen->harga_jual, 0, ',', '.') }}/kg</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Total Pemasukan</p>
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

            <div class="mb-3">
                <label class="block text-gray-700 mb-1 text-sm">Harga Jual per kg (Rp)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-gray-600">Rp</span>
                    <input type="number" name="harga_jual"
                           class="form-input w-full pl-8 text-sm py-2 border-gray-300 rounded-md"
                           value="{{ old('harga_jual', $selectedPanen->harga_jual) }}"
                           min="1000" step="500" required
                           placeholder="Contoh: 50000">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors">
                <div class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan Harga
                </div>
            </button>
        </form>
    @else
        <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded-lg text-sm">
            <div class="flex items-start">
                <svg class="h-4 w-4 text-red-400 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="font-medium text-red-700">Data panen tidak tersedia atau tidak lengkap</h3>
                    <p class="text-red-600 mt-1">Silakan input data panen terlebih dahulu di menu Produksi.</p>
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
