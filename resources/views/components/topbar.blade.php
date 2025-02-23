<!-- navbar.blade.php -->
<nav class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route("home") }}" class="flex items-center">
                    <img src="{{ asset('img/roc-logo.png') }}" alt="ROC.PH Logo" class="h-8">
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex space-x-8">
                <a href="" class="text-primary hover:text-white hover:bg-primary rounded-md px-3 py-2 text-sm font-bold uppercase">
                    Request
                </a>
                <a href="" class="text-primary hover:text-white hover:bg-primary rounded-md px-3 py-2 text-sm font-bold uppercase">
                    On-Demand
                </a>
                <a href="" class="text-primary hover:text-white hover:bg-primary rounded-md px-3 py-2 text-sm font-bold uppercase">
                    Care
                </a>
                <a href="" class="text-primary hover:text-white hover:bg-primary rounded-md px-3 py-2 text-sm font-bold uppercase">
                    Careers
                </a>
            </div>

            <!-- Schedule Demo Button -->
            <div class="hidden md:flex items-center">
                <a href="" class="inline-flex items-center px-4 py-2 hover:bg-primary rounded-md text-sm font-bold text-primary hover:text-white">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Schedule a Demo
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu icon -->
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">Request</a>
            <a href="" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">On-Demand</a>
            <a href="" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">Care</a>
            <a href="" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">Careers</a>
            <a href="" class="block px-3 py-2 rounded-md text-base font-medium text-blue-500 hover:bg-blue-50">Schedule a Demo</a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>