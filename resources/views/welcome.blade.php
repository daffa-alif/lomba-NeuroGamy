<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroSimplicity - From Hate to a Mate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-inter {
            font-family: 'Inter', sans-serif;
        }

        /* Menambahkan smooth scroll behavior saat link navigasi diklik */
        html {
            scroll-behavior: smooth;
        }

        /* --- CSS untuk Animasi Scroll --- */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- CSS untuk Tombol Back to Top --- */
        #back-to-top-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #000;
            color: #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            z-index: 1000;
        }

        #back-to-top-btn.visible {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body id="top" class="bg-white text-black font-sans overflow-x-hidden">

    <!-- Header Section -->
    <header class="container mx-auto max-w-6xl px-6 py-6">
        <div class="flex justify-between items-center">
            <h1 class="font-inter text-2xl font-bold tracking-tight">NeuroSimplicity</h1>
            <nav class="hidden md:flex items-center space-x-8 text-gray-700 font-semibold">
                <a href="#Problems" class="hover:text-black">Problems</a>
                <a href="#Features" class="hover:text-black">Features</a>
                <a href="#Benefits" class="hover:text-black">Benefits</a>
            </nav>
            <a href="{{ route('register') }}" class="hidden md:block bg-black text-white py-2 px-5 rounded-md hover:bg-gray-800 transition font-semibold">Register</a>
            <div class="md:hidden">
                <button class="text-black focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>
    </header>

    <main class="container mx-auto max-w-6xl px-6">
        <!-- Hero Section -->
        <section class="grid md:grid-cols-2 gap-12 items-center py-12">
            <div class="text-left">
                <h2 class="font-inter text-6xl lg:text-7xl font-black mb-4 leading-none">From Hate,<br>to a Mate</h2>
                <p class="text-gray-500 mb-8 text-lg">Blablabla blebleble blublublu dan seterusnya</p>
                <div class="flex space-x-4 font-bold">
                    <a href="#" class="bg-black text-white py-3 px-6 rounded-lg hover:bg-gray-800 transition">Get Started</a>
                    <a href="#" class="bg-white text-black border-2 border-gray-300 py-3 px-6 rounded-lg hover:bg-gray-100 transition">Get Started</a>
                </div>
            </div>
            <div class="bg-gray-200 rounded-3xl h-80 flex items-center justify-center">
                <span class="bg-black text-white text-sm font-bold py-2 px-4 rounded-md">Try It Now!</span>
            </div>
        </section>

        <!-- Indonesian Problem Section (dengan class reveal) -->
        <section id="Problems" class="text-center py-16 reveal">
            <h3 class="font-inter text-5xl font-bold mb-16">Indonesian Problem</h3>
            <div class="max-w-5xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-lg flex flex-col justify-center items-center h-72">
                        <h4 class="font-inter text-2xl font-bold mb-3 text-center">Orang indonesia<br>males baca</h4>
                        <a href="https://rri.co.id/daerah/649261/unesco-sebut-minat-baca-orang-indonesia-masih-rendah" target="_blank" class="text-sm text-gray-500 inline-flex items-center">Click on this tab<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg></a>
                    </div>
                    <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-lg flex flex-col justify-center items-center h-72">
                        <h4 class="font-inter text-2xl font-bold mb-3 text-center">Pendidikan belum<br>merata</h4>
                        <a href="https://edukasi.okezone.com/read/2025/07/23/624/3157482/akses-pendidikan-anak-di-indonesia-masih-belum-merata-76-terkendala-ekonomi" target="_blank" class="text-sm text-gray-500 inline-flex items-center">Click on this tab<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg></a>
                    </div>
                    <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-lg flex flex-col justify-center items-center h-72">
                        <h4 class="font-inter text-2xl font-bold mb-3 text-center">"MBG" malah membuat murid<br>trauma belajar</h4>
                        <a href="https://mojok.co/terminal/pengalaman-keracunan-mbg-malas-sekolah-hingga-trauma/" target="_blank" class="text-sm text-gray-500 inline-flex items-center">Click on this tab<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Combined Features and Benefits Section (DIUBAH TOTAL) -->
        <section id="Features" class="py-24 reveal">
            <div class="text-center">
                 <h3 class="font-inter text-5xl font-bold">From Features to Benefits</h3>
                 <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto">Here's how our features directly translate into powerful benefits for you.</p>
            </div>
            
            <div class="mt-20 grid grid-cols-1 md:grid-cols-5 gap-8 items-center">
                <!-- Kolom Kiri: What we got (Features) -->
                <div class="md:col-span-2 space-y-8">
                    <h4 class="text-3xl font-bold text-center md:text-left">What you use</h4>
                    <!-- Card 1: Summarize -->
                    <div class="bg-white p-6 rounded-2xl shadow-lg border transition duration-300 ease-in-out hover:scale-105 hover:shadow-xl flex items-center space-x-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <div>
                            <h5 class="font-bold text-xl">Summarize Book</h5>
                            <p class="text-gray-600">Get key insights quickly.</p>
                        </div>
                    </div>
                     <!-- Quiz Card -->
                    <div class="bg-white p-6 rounded-2xl shadow-lg border transition duration-300 ease-in-out hover:scale-105 hover:shadow-xl flex items-center space-x-6">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                         <div>
                            <h5 class="font-bold text-xl">Quiz Based on Book</h5>
                            <p class="text-gray-600">Test your knowledge.</p>
                        </div>
                    </div>
                    <!-- Progression Card -->
                    <div class="bg-white p-6 rounded-2xl shadow-lg border transition duration-300 ease-in-out hover:scale-105 hover:shadow-xl flex items-center space-x-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        <div>
                            <h5 class="font-bold text-xl">Self-Progression</h5>
                            <p class="text-gray-600">Track your growth.</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Tengah: Panah -->
                <div class="flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300 transform md:rotate-0 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>

                <!-- Kolom Kanan: What you got (Benefits) -->
                <div id="Benefits" class="md:col-span-2 space-y-8">
                    <h4 class="text-3xl font-bold text-center md:text-left">What you get</h4>
                    <!-- Dopamine Card -->
                    <div class="bg-white p-6 rounded-2xl shadow-lg border transition duration-300 ease-in-out hover:scale-105 hover:shadow-xl flex items-center space-x-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                        <div>
                            <h5 class="font-bold text-xl">Dopamine Boost</h5>
                            <p class="text-gray-600">Feel accomplished.</p>
                        </div>
                    </div>
                    <!-- Attention Card -->
                    <div class="bg-white p-6 rounded-2xl shadow-lg border transition duration-300 ease-in-out hover:scale-105 hover:shadow-xl flex items-center space-x-6">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                         <div>
                            <h5 class="font-bold text-xl">Normal Span Attention</h5>
                            <p class="text-gray-600">Improve your focus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Send Question Section -->
        <section id="SendQuestion" class="text-center py-16 reveal">
            <h3 class="font-inter text-5xl font-bold mb-16">Have a Question?</h3>
            <div class="max-w-2xl mx-auto">
                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="name" class="block text-left mb-2 font-semibold text-gray-700">Name</label>
                        <input type="text" id="name" name="name" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition" placeholder="Your Name" required>
                    </div>
                    <div>
                        <label for="email" class="block text-left mb-2 font-semibold text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition" placeholder="your.email@example.com" required>
                    </div>
                    <div>
                        <label for="question" class="block text-left mb-2 font-semibold text-gray-700">Question</label>
                        <textarea id="question" name="question" rows="5" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition" placeholder="Ask anything you want..." required></textarea>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-black text-white py-4 px-8 rounded-lg hover:bg-gray-800 transition font-bold text-lg">Send Question</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer id="contact" class="bg-gray-900 text-white py-10 mt-16">
        <div class="container mx-auto max-w-6xl px-6 text-center">
            <p>&copy; 2025 NeuroSimplicity. All rights reserved.</p>
            <p class="mt-2">Contact us at <a href="mailto:support@neurosimplicity.com" class="underline">support@neurosimplicity.com</a></p>
        </div>
    </footer>

    <!-- Tombol Back to Top -->
    <a href="#top" id="back-to-top-btn" class="hover:bg-gray-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </a>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Logika untuk Animasi Scroll Reveal ---
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            const elementsToReveal = document.querySelectorAll('.reveal');
            elementsToReveal.forEach(element => {
                observer.observe(element);
            });

            // --- Logika untuk Tombol Back to Top ---
            const backToTopButton = document.getElementById('back-to-top-btn');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 400) { // Tampilkan tombol setelah scroll 400px
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });
        });
    </script>
</body>
</html>

