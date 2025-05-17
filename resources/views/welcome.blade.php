<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Thesistrack - Sistem Pengelolaan Tugas Akhir</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />

        <!-- AOS for Scroll Animations -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

        <!-- Styles -->
        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes slideInLeft {
                from { opacity: 0; transform: translateX(-50px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes ripple {
                to { transform: scale(2); opacity: 0; }
            }
            .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
            .animate-slideInLeft { animation: slideInLeft 0.8s ease-out forwards; }
            .hover-scale:hover {
                transform: scale(1.05);
                transition: transform 0.3s ease;
            }
            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            .logo:hover {
                transform: scale(1.1);
                transition: transform 0.3s ease;
            }
            .ripple-effect {
                position: relative;
                overflow: hidden;
            }
            .ripple-effect::after {
                content: '';
                position: absolute;
                width: 20px;
                height: 20px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                opacity: 1;
                pointer-events: none;
            }
            .ripple-effect:active::after {
                animation: ripple 0.4s ease-out;
            }
            .bg-hero {
                background-image: url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
            .bg-main {
                background: #FFFFFF;
            }
            .bg-secondary {
                background: #F9FAFB;
            }
            .bg-cta {
                background: linear-gradient(to right, #2563EB, #3B82F6);
            }
            .text-primary {
                color: #2563EB;
            }
            .text-secondary {
                color: #6B7280;
            }
            .text-accent {
                color: #FBBF24;
            }
            .bg-primary {
                background: #2563EB;
            }
            .bg-accent {
                background: #FBBF24;
            }
            .navbar {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
            }
            .navbar a, .navbar span {
                color: #1F2937;
            }
            .faq-item input[type="checkbox"] {
                display: none;
            }
            .faq-item input[type="checkbox"]:checked ~ .faq-content {
                max-height: 200px;
                padding: 1rem;
                opacity: 1;
            }
            .faq-item input[type="checkbox"]:checked ~ label .faq-arrow {
                transform: rotate(180deg);
            }
            .faq-content {
                max-height: 0;
                overflow: hidden;
                opacity: 0;
                transition: all 0.3s ease;
            }
            .faq-arrow {
                transition: transform 0.3s ease;
            }
            .feature-card:hover {
                border-color: #FBBF24;
                transition: border-color 0.3s ease;
            }
            .testimonial-slider {
                display: flex;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
            .testimonial-slider > div {
                flex: 0 0 100%;
                scroll-snap-align: start;
            }
            img.logo-img {
                display: block;
            }
            img.logo-img[src=""] {
                display: none;
            }
            img.logo-img:not([src]) {
                display: none;
            }
            img.logo-img.fallback {
                border: 1px dashed #6B7280;
                background: #F9FAFB;
                text-align: center;
                line-height: 40px;
                color: #6B7280;
            }
        </style>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-poppins antialiased dark:bg-gray-900 dark:text-white/80 bg-main">
        <div class="text-black/90 dark:text-white/80">
            <!-- Navbar -->
            <nav class="navbar fixed top-0 left-0 w-full z-50 py-4 px-6 shadow-sm">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Thesistrack Logo" class="h-10 logo logo-img" onerror="this.classList.add('fallback'); this.src=''; this.alt='Logo Not Found';">
                        <span class="text-xl font-bold text-primary">Thesistrack</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-sm font-medium hover:text-primary transition">Beranda</a>
                        <a href="#features" class="text-sm font-medium hover:text-primary transition">Fitur</a>
                        <a href="#faq" class="text-sm font-medium hover:text-primary transition">FAQ</a>
                        @auth
                            <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition hover-lift">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium hover:text-primary transition">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition hover-lift">Daftar</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <section class="min-h-screen flex items-center py-20 px-6 bg-hero relative">
                <div class="absolute inset-0 bg-primary/60"></div>
                <div class="max-w-4xl mx-auto text-center relative z-10" data-aos="fade-up">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        Kelola Tugas Akhir dengan Thesistrack
                    </h1>
                    <p class="text-lg text-white/90 mb-6">
                        Platform terpercaya untuk bimbingan, persetujuan, dan pelacakan tugas akhir secara efisien.
                    </p>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('register') }}" class="bg-accent text-gray-900 px-6 py-3 rounded-lg font-medium hover:bg-yellow-300 transition hover-lift ripple-effect">Mulai Sekarang</a>
                        <a href="#features" class="border border-white text-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-primary transition hover-lift ripple-effect" onclick="scrollToFeatures()">Pelajari Fitur</a>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-20 px-6 bg-white dark:bg-gray-900">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-primary dark:text-white" data-aos="fade-up">
                        Fitur Unggulan Thesistrack
                    </h2>
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4" data-aos="zoom-in-up">
                        <div class="feature-card bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition hover-lift">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Bimbingan Online</h3>
                            <p class="text-sm text-secondary dark:text-gray-300">
                                Komunikasi real-time dengan dosen pembimbing untuk hasil maksimal.
                            </p>
                        </div>
                        <div class="feature-card bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition hover-lift">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Persetujuan Cepat</h3>
                            <p class="text-sm text-secondary dark:text-gray-300">
                                Proses persetujuan dokumen yang terstruktur dan tanpa hambatan.
                            </p>
                        </div>
                        <div class="feature-card bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition hover-lift">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Dashboard Progres</h3>
                            <p class="text-sm text-secondary dark:text-gray-300">
                                Lacak kemajuan tugas akhir dengan visualisasi yang intuitif.
                            </p>
                        </div>
                        <div class="feature-card bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition hover-lift">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 dark:text-white">Notifikasi Aktif</h3>
                            <p class="text-sm text-secondary dark:text-gray-300">
                                Dapatkan pemberitahuan langsung untuk setiap pembaruan penting.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonial Section -->
            <section class="py-20 px-6 bg-white dark:bg-gray-900">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-primary dark:text-white" data-aos="fade-up">
                        Apa Kata Pengguna Kami
                    </h2>
                    <div class="testimonial-slider">
                        <div class="px-4" data-aos="fade-right">
                            <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                                <p class="text-secondary dark:text-gray-300 mb-4">
                                    "Thesistrack membuat proses bimbingan saya jauh lebih mudah. Dashboard-nya sangat membantu untuk melacak progres!"
                                </p>
                                <div class="flex items-center">
                                    <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="User" class="w-12 h-12 rounded-full mr-4">
                                    <div>
                                        <h4 class="font-semibold dark:text-white">Siti Aminah</h4>
                                        <p class="text-sm text-secondary dark:text-gray-400">Mahasiswa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4" data-aos="fade-right" data-aos-delay="200">
                            <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                                <p class="text-secondary dark:text-gray-300 mb-4">
                                    "Sebagai dosen, saya sangat mengapresiasi fitur persetujuan dokumen yang cepat dan terorganisir."
                                </p>
                                <div class="flex items-center">
                                    <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="User" class="w-12 h-12 rounded-full mr-4">
                                    <div>
                                        <h4 class="font-semibold dark:text-white">Dr. Budi Santoso</h4>
                                        <p class="text-sm text-secondary dark:text-gray-400">Dosen Pembimbing</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="py-20 px-6 bg-secondary dark:bg-gray-800">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-primary dark:text-white" data-aos="fade-up">
                        Pertanyaan Umum
                    </h2>
                    <div class="space-y-4">
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="100">
                            <input type="checkbox" id="faq1" class="hidden">
                            <label for="faq1" class="flex justify-between items-center p-4 cursor-pointer">
                                <span class="font-semibold dark:text-white">Bagaimana cara memulai dengan Thesistrack?</span>
                                <svg class="w-5 h-5 text-white faq-arrow" xmlns="http://www.w3.0rg/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </label>
                            <div class="faq-content text-secondary dark:text-gray-300">
                                Daftar akun, lengkapi profil Anda, dan mulai ajukan dokumen atau komunikasi dengan dosen pembimbing melalui platform.
                            </div>
                        </div>
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="200">
                            <input type="checkbox" id="faq2" class="hidden">
                            <label for="faq2" class="flex justify-between items-center p-4 cursor-pointer">
                                <span class="font-semibold dark:text-white">Apakah Thesistrack gratis?</span>
                                <svg class="w-5 h-5 text-white faq-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </label>
                            <div class="faq-content text-secondary dark:text-gray-300">
                                Ya, Thesistrack sepenuhnya gratis untuk semua pengguna tanpa opsi premium.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action Section -->
            <section class="bg-cta text-white py-20 px-6">
                <div class="max-w-4xl mx-auto text-center" data-aos="zoom-in">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        Siap untuk Sukses dengan Tugas Akhir Anda?
                    </h2>
                    <p class="text-lg mb-8">
                        Bergabunglah dengan komunitas Thesistrack dan rasakan kemudahan dalam setiap langkah perjalanan tugas akhir Anda.
                    </p>
                    <a href="{{ route('register') }}" class="bg-accent text-gray-900 px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition hover-lift text-lg ripple-effect">Mulai Sekarang</a>
                </div>
            </section>

            <!-- Footer -->
            <footer class="py-12 px-6 bg-primary text-white">
                <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Fitur</h3>
                        <ul class="text-sm space-y-2">
                            <li><a href="#features" class="hover:text-accent transition">Bimbingan Online</a></li>
                            <li><a href="#features" class="hover:text-accent transition">Persetujuan Dokumen</a></li>
                            <li><a href="#features" class="hover:text-accent transition">Dashboard Progres</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Perusahaan</h3>
                        <ul class="text-sm space-y-2">
                            <li><a href="#" class="hover:text-accent transition">Tentang Kami</a></li>
                            <li><a href="#" class="hover:text-accent transition">Kontak</a></li>
                            <li><a href="#" class="hover:text-accent transition">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto mt-8 text-center">
                    <div class="flex justify-center gap-6 mb-4">
                        <a href="#" class="hover:text-accent transition hover-scale">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="hover:text-accent transition hover-scale">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.332.014 7.052.072 2.95.272.272 2.95.072 7.052.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.102 2.878 6.78 6.98 6.98 1.28.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.102-.2 6.78-2.878 6.98-6.98.058-1.28.072-1.689.072-4.948 0-3.259-.014-3.668-.072-4.948-.2-4.102-2.878-6.78-6.98-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    </div>
                    <p class="text-sm">
                        Thesistrack © {{ date('Y') }}. All rights reserved. | Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </p>
                </div>
            </footer>

            <!-- Initialize AOS and Scroll Handler -->
            <script>
                AOS.init({
                    duration: 800,
                    once: false // Allow animations to replay when scrolling back
                });

                function scrollToFeatures() {
                    const featuresSection = document.getElementById('features');
                    featuresSection.scrollIntoView({ behavior: 'smooth' });
                    // Refresh AOS animations after scrolling
                    setTimeout(() => {
                        AOS.refresh();
                    }, 1000);
                }

                // Debug Logo Loading
                document.addEventListener('DOMContentLoaded', () => {
                    const logoImages = document.querySelectorAll('.logo-img');
                    logoImages.forEach(img => {
                        img.addEventListener('error', () => {
                            console.error(`Failed to load image at ${img.src}`);
                            img.classList.add('fallback');
                            img.src = '';
                            img.alt = 'Image Not Found';
                        });
                    });
                });
            </script>
        </div>
    </body>
</html>