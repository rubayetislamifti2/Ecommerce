<footer class="bg-gray-900 text-gray-300 pt-12 pb-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">

            <!-- Company Info -->
            <div class="col-span-1 md:col-span-1">
                <a href="#" class="flex items-center text-xl font-bold text-white mb-4">
                    <svg class="w-7 h-7 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    MyBrand
                </a>
                <p class="text-gray-400 text-sm">
                    Creating modern web applications using modern technologies like Laravel, Blade, and Tailwind CSS.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition">Services</a></li>
                    <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    <li><a href="#" class="hover:text-white transition">Careers</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Support</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                    <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition">Contact Support</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Subscribe</h3>
                <p class="text-sm text-gray-400 mb-3">Get the latest news and updates right to your inbox.</p>
                <form action="#" method="POST" class="flex flex-col space-y-2">
                    @csrf
                    <input type="email" placeholder="Your email address" class="px-3 py-2 text-sm text-gray-900 bg-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-500 transition">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} MyBrand. All rights reserved.</p>
            <div class="flex space-x-4 mt-4 sm:mt-0">
                <a href="#" class="hover:text-gray-400"><span class="sr-only">Twitter</span>Twitter</a>
                <a href="#" class="hover:text-gray-400"><span class="sr-only">GitHub</span>GitHub</a>
                <a href="#" class="hover:text-gray-400"><span class="sr-only">LinkedIn</span>LinkedIn</a>
            </div>
        </div>
    </div>
</footer>
