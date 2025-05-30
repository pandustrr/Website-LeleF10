<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="modalOverlay">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4 shadow-2xl">
        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
            <button onclick="document.getElementById('modalOverlay').remove(); document.body.style.overflow = ''"
                class="text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Konten Modal -->
        {{ $slot }}

        <!-- Footer Modal -->
        @if(isset($footer))
            <div class="mt-6 flex justify-end space-x-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

<script>
    // Blok scroll saat modal terbuka
    document.body.style.overflow = 'hidden';

    // Fokus ke input pertama jika ada
    const firstInput = document.querySelector('#modalOverlay input, #modalOverlay select, #modalOverlay textarea');
    if (firstInput) firstInput.focus();
</script>
