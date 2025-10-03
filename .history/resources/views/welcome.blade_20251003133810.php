<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'AI Library') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Inter',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--spacing:.25rem}}@layer base{*,::after,::before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{line-height:1.5;font-family:var(--font-sans);-webkit-text-size-adjust:100%;tab-size:4}body{line-height:inherit}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}img,svg,video{display:block;vertical-align:middle;max-width:100%;height:auto}button,input,select,textarea{font:inherit;color:inherit}}@layer utilities{.container{width:100%;margin-left:auto;margin-right:auto;padding-left:1rem;padding-right:1rem}@media(min-width:640px){.container{max-width:640px}}@media(min-width:768px){.container{max-width:768px}}@media(min-width:1024px){.container{max-width:1024px}}@media(min-width:1280px){.container{max-width:1280px}}@media(min-width:1536px){.container{max-width:1536px}}.fixed{position:fixed}.top-0{top:0}.left-0{left:0}.right-0{right:0}.z-50{z-index:50}.min-h-screen{min-height:100vh}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.gap-2{gap:.5rem}.gap-4{gap:1rem}.gap-6{gap:1.5rem}.gap-8{gap:2rem}.gap-12{gap:3rem}.grid-cols-2{grid-template-columns:repeat(2,1fr)}.flex-col{flex-direction:column}.rounded-none{border-radius:0}.rounded-lg{border-radius:.5rem}.border{border-width:1px}.border-2{border-width:2px}.border-b{border-bottom-width:1px}.border-black{border-color:#000}.border-gray-200{border-color:#e5e7eb}.bg-white{background-color:#fff}.bg-black{background-color:#000}.bg-gray-50{background-color:#f9fafb}.bg-gray-100{background-color:#f3f4f6}.p-4{padding:1rem}.p-6{padding:1.5rem}.p-8{padding:2rem}.px-4{padding-left:1rem;padding-right:1rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.py-3{padding-top:.75rem;padding-bottom:.75rem}.px-8{padding-left:2rem;padding-right:2rem}.py-4{padding-top:1rem;padding-bottom:1rem}.py-12{padding-top:3rem;padding-bottom:3rem}.py-16{padding-top:4rem;padding-bottom:4rem}.py-20{padding-top:5rem;padding-bottom:5rem}.pt-20{padding-top:5rem}.pb-12{padding-bottom:3rem}.text-center{text-align:center}.text-sm{font-size:.875rem;line-height:1.25rem}.text-base{font-size:1rem;line-height:1.5rem}.text-lg{font-size:1.125rem;line-height:1.75rem}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-2xl{font-size:1.5rem;line-height:2rem}.text-3xl{font-size:1.875rem;line-height:2.25rem}.text-4xl{font-size:2.25rem;line-height:2.5rem}.text-5xl{font-size:3rem;line-height:1}.text-6xl{font-size:3.75rem;line-height:1}.font-medium{font-weight:500}.font-semibold{font-weight:600}.font-bold{font-weight:700}.font-extrabold{font-weight:800}.leading-tight{line-height:1.25}.text-white{color:#fff}.text-black{color:#000}.text-gray-600{color:#4b5563}.text-gray-700{color:#374151}.text-gray-900{color:#111827}.shadow-sm{box-shadow:0 1px 2px 0 rgba(0,0,0,.05)}.shadow-lg{box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}.shadow-xl{box-shadow:0 20px 25px -5px rgba(0,0,0,.1),0 8px 10px -6px rgba(0,0,0,.1)}.transition-all{transition-property:all;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:.15s}.duration-300{transition-duration:.3s}.hover\:bg-black:hover{background-color:#000}.hover\:bg-gray-100:hover{background-color:#f3f4f6}.hover\:text-white:hover{color:#fff}.hover\:shadow-xl:hover{box-shadow:0 20px 25px -5px rgba(0,0,0,.1),0 8px 10px -6px rgba(0,0,0,.1)}.hover\:scale-105:hover{transform:scale(1.05)}@media(min-width:768px){.md\:grid-cols-3{grid-template-columns:repeat(3,1fr)}.md\:grid-cols-4{grid-template-columns:repeat(4,1fr)}.md\:flex-row{flex-direction:row}.md\:text-5xl{font-size:3rem;line-height:1}.md\:text-6xl{font-size:3.75rem;line-height:1}}.inline-flex{display:inline-flex}.mb-2{margin-bottom:.5rem}.mb-4{margin-bottom:1rem}.mb-6{margin-bottom:1.5rem}.mb-8{margin-bottom:2rem}.mb-12{margin-bottom:3rem}.mb-16{margin-bottom:4rem}.max-w-4xl{max-width:56rem}.max-w-6xl{max-width:72rem}.mx-auto{margin-left:auto;margin-right:auto}.w-6{width:1.5rem}.h-6{height:1.5rem}.w-12{width:3rem}.h-12{height:3rem}.w-16{width:4rem}.h-16{height:4rem}.space-x-6>:not(:last-child){margin-right:1.5rem}}
            </style>
        @endif
    </head>
    <body class="bg-white text-black">
        <!-- Navigation Bar -->
        <nav class="fixed top-0 left-0 right-0 bg-white border-b border-black z-50">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-2">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-xl font-bold">AI Library</span>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#home" class="font-medium hover:text-gray-600 transition-all">Home</a>
                        <a href="#features" class="font-medium hover:text-gray-600 transition-all">Features</a>
                        <a href="#library" class="font-medium hover:text-gray-600 transition-all">Library</a>
                        <a href="#about" class="font-medium hover:text-gray-600 transition-all">About</a>
                    </div>

                    <!-- Auth Links -->
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-black text-white font-semibold transition-all duration-300 hover:bg-gray-900">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-2 font-semibold hover:text-gray-600 transition-all">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-6 py-2 bg-black text-white font-semibold transition-all duration-300 hover:bg-gray-900">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="pt-20">
            <!-- ATTENTION: Hero Section -->
            <section id="home" class="py-20 bg-black text-white">
                <div class="container mx-auto px-6 text-center">
                    <div class="flex items-center justify-center gap-4 mb-8">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                        Transform Your Learning<br/>with AI-Powered Library
                    </h1>
                    <p class="text-xl text-gray-300 mb-12 max-w-4xl mx-auto">
                        Discover a revolutionary platform where artificial intelligence meets knowledge management. Experience the future of research and learning.
                    </p>
                    <div class="flex flex-col md:flex-row gap-4 justify-center">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-black font-bold text-lg hover:bg-gray-100 transition-all duration-300">
                                Get Started Free
                            </a>
                        @endif
                        <a href="#library" class="px-8 py-4 border-2 border-white text-white font-bold text-lg hover:bg-white hover:text-black transition-all duration-300">
                            Explore Library
                        </a>
                    </div>
                </div>
            </section>

            <!-- INTEREST: Features Section -->
            <section id="features" class="py-20 bg-white">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4">Why Choose AI Library?</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            Cutting-edge technology designed to revolutionize how you access and interact with knowledge
                        </p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="p-8 border-2 border-black hover:bg-black hover:text-white transition-all duration-300 group">
                            <div class="w-12 h-12 bg-black group-hover:bg-white flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-white group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Smart Search</h3>
                            <p class="text-gray-600 group-hover:text-gray-300">
                                AI-powered search that understands context and delivers precise results instantly
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="p-8 border-2 border-black hover:bg-black hover:text-white transition-all duration-300 group">
                            <div class="w-12 h-12 bg-black group-hover:bg-white flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-white group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Personalized</h3>
                            <p class="text-gray-600 group-hover:text-gray-300">
                                Intelligent recommendations tailored to your reading habits and interests
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="p-8 border-2 border-black hover:bg-black hover:text-white transition-all duration-300 group">
                            <div class="w-12 h-12 bg-black group-hover:bg-white flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-white group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Lightning Fast</h3>
                            <p class="text-gray-600 group-hover:text-gray-300">
                                Access millions of resources in milliseconds with our optimized infrastructure
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- DESIRE: Benefits Section -->
            <section id="library" class="py-20 bg-gray-50">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4">Access Unlimited Knowledge</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            Everything you need for research, learning, and intellectual growth
                        </p>
                    </div>

                    <div class="grid md:grid-cols-4 gap-8 mb-16">
                        <div class="text-center p-6">
                            <div class="text-5xl font-bold mb-2">50K+</div>
                            <div class="text-gray-600 font-medium">Books Available</div>
                        </div>
                        <div class="text-center p-6">
                            <div class="text-5xl font-bold mb-2">10K+</div>
                            <div class="text-gray-600 font-medium">Active Members</div>
                        </div>
                        <div class="text-center p-6">
                            <div class="text-5xl font-bold mb-2">1M+</div>
                            <div class="text-gray-600 font-medium">AI Searches</div>
                        </div>
                        <div class="text-center p-6">
                            <div class="text-5xl font-bold mb-2">24/7</div>
                            <div class="text-gray-600 font-medium">AI Assistant</div>
                        </div>
                    </div>

                    <div class="bg-black text-white p-12 text-center">
                        <h3 class="text-3xl font-bold mb-4">Ready to Experience the Future?</h3>
                        <p class="text-xl text-gray-300 mb-8 max-w-4xl mx-auto">
                            Join thousands of researchers, students, and knowledge seekers already benefiting from AI-powered library services
                        </p>
                    </div>
                </div>
            </section>

            <!-- ACTION: CTA Section -->
            <section id="about" class="py-20 bg-white">
                <div class="container mx-auto px-6 text-center">
                    <h2 class="text-4xl md:text-5xl font-bold mb-6">Start Your Journey Today</h2>
                    <p class="text-xl text-gray-600 mb-12 max-w-4xl mx-auto">
                        Create your free account and unlock the power of AI-enhanced learning. No credit card required.
                    </p>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-12 py-5 bg-black text-white text-lg font-bold hover:bg-gray-900 transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            Create Free Account
                        </a>
                    @endif
                </div>
            </section>

            <!-- Footer -->
            <footer class="bg-black text-white py-12">
                <div class="container mx-auto px-6 text-center">
                    <div class="mb-6">
                        <div class="flex items-center justify-center gap-2 mb-4">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-2xl font-bold">AI Library</span>
                        </div>
                    </div>
                    <p class="text-gray-400">&copy; {{ date('Y') }} AI Library. Powered by Artificial Intelligence.</p>
                </div>
            </footer>
        </div>
    </body>
</html>