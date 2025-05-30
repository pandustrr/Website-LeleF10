<aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-screen fixed top-0 left-0 z-40 transition-all duration-300 ease-in-out">
    <!-- Logo Section -->
    <div class="relative border-b border-gray-200">
        <div class="flex justify-center items-center py-6 px-4">
            <img src="{{ asset('images/tanpa_bg.png') }}" alt="Company Logo" class="h-20">
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto" aria-label="Main navigation">
        <ul class="space-y-2 font-medium px-2 mt-4">
            <!-- Dashboard Section -->
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    aria-controls="dashboard-menu" aria-expanded="false"
                    onclick="toggleMenu('dashboard-menu', 'dashboard-arrow')">
                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 22 21" aria-hidden="true">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="flex-1 ms-3 text-left whitespace-nowrap text-gray-900 font-semibold">Dashboards</span>
                    <svg id="dashboard-arrow" class="w-3 h-3 ms-1 transform transition-transform duration-200"
                        fill="none" viewBox="0 0 10 6" aria-hidden="true">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dashboard-menu" class="hidden py-2 space-y-2" aria-label="Dashboard submenu">
                    <li>
                        <a href="{{ route('analisisPrediksi') }}"
                            class="flex items-center w-full p-2 ps-11 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 {{ request()->is('analisis-prediksi') ? 'bg-gray-100 font-medium' : '' }}">
                            Analisis Prediksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('produksi') }}"
                            class="flex items-center w-full p-2 ps-11 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 {{ request()->is('produksi*') ? 'bg-gray-100 font-medium' : '' }}">
                            Produksi
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isAdmin())
                        <li>
                            <a href="{{ route('keuangan') }}"
                                class="flex items-center w-full p-2 ps-11 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 {{ request()->is('keuangan') ? 'bg-gray-100 font-medium' : '' }}">
                                Keuangan
                            </a>
                        </li>
                        @endif
                    @endauth
                </ul>
            </li>

            <!-- Settings Section -->
            <li class="px-3 mt-4 text-xs font-semibold tracking-wide text-gray-500 uppercase">Settings</li>

            <!-- Account Settings -->
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    aria-controls="account-menu" aria-expanded="false"
                    onclick="toggleMenu('account-menu', 'account-arrow')">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 20 20" aria-hidden="true">
                        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0 0c-4.418 0-8 2.015-8 4.5V17h16v-2.5c0-2.485-3.582-4.5-8-4.5Z" />
                    </svg>
                    <span class="flex-1 ms-3 text-left whitespace-nowrap">Account Settings</span>
                    <svg id="account-arrow" class="w-3 h-3 ms-1 transform transition-transform duration-200"
                        fill="none" viewBox="0 0 10 6" aria-hidden="true">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="account-menu" class="hidden py-2 space-y-2" aria-label="Account submenu">

                    <li>
                        <a href="{{ route('profile.show') }}"
                            class="flex items-center w-full p-2 ps-11 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 {{ request()->is('profil') ? 'bg-gray-100 font-medium' : '' }}">
                            Profile
                        </a>
                    </li>

                </ul>
            </li>
        </ul>
    </nav>

    <!-- Logout Section -->
    <div class="p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-full p-2 text-white bg-red-500 hover:bg-red-600 rounded-lg font-semibold shadow-lg shadow-red-200 transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Logout
            </button>
        </form>
    </div>
</aside>

<script>
    // Initialize sidebar state on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-open dropdown for active menu
        const activeMenu = document.querySelector('a.bg-gray-100');
        if (activeMenu) {
            const dropdown = activeMenu.closest('ul');
            if (dropdown) {
                const buttonId = dropdown.id.replace('menu', 'button');
                const button = document.querySelector(`[aria-controls="${dropdown.id}"]`);
                const arrowId = dropdown.id.split('-')[0] + '-arrow';
                const arrow = document.getElementById(arrowId);

                dropdown.classList.remove('hidden');
                if (button) button.setAttribute('aria-expanded', 'true');
                if (arrow) arrow.style.transform = 'rotate(90deg)';
            }
        }
    });

    // Toggle menu function
    function toggleMenu(menuId, arrowId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);
        const isExpanded = menu.classList.toggle('hidden');

        arrow.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(90deg)';
        document.querySelector(`[aria-controls="${menuId}"]`).setAttribute('aria-expanded', !isExpanded);
    }
</script>
