@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-3 py-4 sm:px-4 sm:py-5">
        <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-5">Laporan Keuangan</h1>

        <!-- Dropdown Siklus -->
        <div class="mb-4 sm:mb-5">
            <form method="GET" action="{{ route('keuangan') }}">
                <div class="flex items-center">
                    <label for="siklus_id" class="mr-2 text-sm sm:text-base">Pilih Siklus:</label>
                    <select name="siklus_id" id="siklus_id"
                            class="form-select rounded-md text-sm sm:text-base py-1 sm:py-2"
                            onchange="this.form.submit()">
                        @foreach ($siklusList as $siklus)
                            <option value="{{ $siklus->id }}"
                                {{ $siklusAktif && $siklus->id == $siklusAktif->id ? 'selected' : '' }}>
                                {{ $siklus->nama_siklus }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="panen_id" value="{{ request('panen_id') }}">
                </div>
            </form>
        </div>

        <!-- Chart Prediksi -->
        @include('components.keuangan.chart-keuangan')

        <!-- Grid Layout Responsif -->
        <div class="flex flex-col lg:flex-row gap-4 sm:gap-5">
            <!-- Tabel Keuangan (Lebih besar) -->
            <div class="flex-1 min-w-0 overflow-hidden">
                @include('components.keuangan.tabel-keuangan')
            </div>

            <!-- Card Simulasi -->
            <div class="w-full lg:w-80 xl:w-96">
                @include('components.keuangan.simulasi-panen', [
                    'siklusAktif' => $siklusAktif,
                    'panens' => $panens,
                    'selectedPanen' => $selectedPanen,
                    'selectedPanenId' => request('panen_id')
                ])
            </div>
        </div>
    </div>
@endsection
