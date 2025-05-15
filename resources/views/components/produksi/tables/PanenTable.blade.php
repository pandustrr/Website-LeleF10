<div class="flex flex-col lg:flex-row gap-6 w-full">
    <div class="flex-1 bg-white rounded-lg shadow-sm">
        <table class="min-w-full">
            <thead class="bg-orange-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase">No.</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase min-w-[150px]">Tanggal</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase min-w-[120px]">Kuantitas</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase min-w-[150px]">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($panens as $index => $panen)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-base">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-base whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($panen->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 text-base whitespace-nowrap">
                            {{ number_format($panen->kuantitas) }} kg
                        </td>

                        <!-- Kolom Aksi - Tombol Edit dan Delete -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end gap-3">
                                <!-- Tombol Edit - Membuka modal edit -->
                                <button onclick="openEditModal('{{ route('panen.edit', $panen->id) }}', 'panen')"
                                    class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>

                                <form action="{{ route('panen.destroy', $panen->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500 text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-4">Belum ada data panen</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="w-full lg:w-64 bg-orange-500 text-white p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-3">Total Panen</h3>
        <div class="space-y-3">
            <div>
                <div class="text-sm font-medium mb-1">Total (kg)</div>
                <div class="bg-white/20 p-2 rounded-lg text-xl font-bold">
                    {{ number_format($panens->sum('kuantitas')) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editModalContainer"></div>
@push('scripts')
    @vite('resources/js/components/produksi/modal-table.js')
@endpush
