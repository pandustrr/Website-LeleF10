@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3 md:px-4 py-4 transition-all duration-300" id="mainContent">
        <!-- Header Section - Modified -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Produksi</h1>

            <!-- Dropdown di kanan -->
            <div class="w-full md:w-auto flex justify-end">
                @include('components.dropdownSiklus')
            </div>
        </div>

        <!-- Grid Input Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-5">
            @include('components.produksi.cards.BibitCard')
            @include('components.produksi.cards.PakanCard')
            @include('components.produksi.cards.PanenCard')
        </div>

        <!-- Tables Section -->
        <div class="mt-5 space-y-5">
            <div id="bibit-table" class="bg-white rounded-lg shadow">
                @include('components.produksi.tables.BibitTable', ['bibits' => $dataBibit])
            </div>

            <div id="pakan-table" class="hidden bg-white rounded-lg shadow">
                @include('components.produksi.tables.PakanTable', ['pakans' => $dataPakan])
            </div>

            <div id="panen-table" class="hidden bg-white rounded-lg shadow">
                @include('components.produksi.tables.PanenTable', ['panens' => $dataPanen])
            </div>
        </div>
    </div>

    <!-- Script tetap sama -->
    <script>
        // Simpan state tab aktif di sessionStorage
        let activeTab = sessionStorage.getItem('activeProduksiTab') || 'bibit';

        // Fungsi untuk menampilkan tabel yang dipilih
        function showTable(tableType) {
            // Simpan tab aktif
            activeTab = tableType;
            sessionStorage.setItem('activeProduksiTab', activeTab);

            // Sembunyikan semua tabel
            document.querySelectorAll('#bibit-table, #pakan-table, #panen-table').forEach(el => {
                el.classList.add('hidden');
            });

            // Tampilkan tabel yang dipilih
            document.getElementById(`${tableType}-table`).classList.remove('hidden');

            // Update card highlight
            updateCardHighlight(tableType);
        }

        // Fungsi untuk update highlight card
        function updateCardHighlight(tableType) {
            // Reset semua card
            document.querySelectorAll('#bibit-card, #pakan-card, #panen-card').forEach(card => {
                card.classList.remove('border-blue-500', 'border-green-500', 'border-orange-500');
                card.classList.add('border-gray-100');
            });

            // Highlight card aktif
            const selectedCard = document.getElementById(`${tableType}-card`);
            if (selectedCard) {
                selectedCard.classList.remove('border-gray-100');

                // Warna border sesuai jenis
                const colorClass = {
                    'bibit': 'border-blue-500',
                    'pakan': 'border-green-500',
                    'panen': 'border-orange-500'
                }[tableType];

                selectedCard.classList.add(colorClass);
            }
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan tab yang aktif sebelumnya atau default ke 'bibit'
            showTable(activeTab);

            // Penyesuaian layout
            const adjustLayout = () => {
                document.getElementById('mainContent').classList.add('min-w-[320px]');
            };
            adjustLayout();
            window.addEventListener('resize', adjustLayout);
        });

        // Fungsi untuk refresh tabel tertentu tanpa reload halaman
        function refreshTable(tableType) {
            fetch(`/produksi/get-${tableType}-data`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById(`${tableType}-table`).innerHTML = html;
                    showTable(activeTab); // Pertahankan tab aktif setelah refresh
                });
        }
    </script>

    @push('scripts')
        @vite('resources/js/components/produksi/modal-table.js')
    @endpush
@endsection
