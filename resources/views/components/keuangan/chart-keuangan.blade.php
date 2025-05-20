<div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">
  <!-- Header dengan info profit -->
  <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center">
      <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
          <!-- Icon SVG -->
        </svg>
      </div>
      <div>
        <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">
          Rp {{ number_format($profit, 0, ',', '.') }}
        </h5>
        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Profit Siklus {{ $siklusAktif->nama_siklus }}</p>
      </div>
    </div>
    <!-- Persentase profit -->
  </div>

  <!-- Detail pengeluaran/pemasukan -->
  <div class="grid grid-cols-2">
    <dl class="flex items-center">
        <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal me-1">Pengeluaran:</dt>
        <dd class="text-gray-900 text-sm dark:text-white font-semibold">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</dd>
    </dl>
    <dl class="flex items-center justify-end">
        <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal me-1">Pemasukan:</dt>
        <dd class="text-gray-900 text-sm dark:text-white font-semibold">Rp {{ number_format($pemasukan, 0, ',', '.') }}</dd>
    </dl>
  </div>

  <!-- Chart -->
  <div id="finance-column-chart"></div>

  <!-- Footer dengan dropdown -->
  <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
    <!-- ... -->
  </div>
</div>

@push('scripts')
<script>
  // Script chart Apex
</script>
@endpush
