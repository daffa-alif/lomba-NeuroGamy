<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neurogamy - Library Enhanced Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white text-black font-sans">
    <!-- Header Section -->
    <header class="bg-black text-white py-6">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Neurogamy</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="#home" class="hover:text-gray-300">Home</a></li>
                    <li><a href="#features" class="hover:text-gray-300">Features</a></li>
                    <li><a href="#about" class="hover:text-gray-300">About</a></li>
                    <li><a href="#contact" class="hover:text-gray-300">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- AIDA: Attention Section -->
    <section id="home" class="bg-white py-20 text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-5xl font-extrabold mb-4">Discover Knowledge with Neurogamy</h2>
            <p class="text-xl mb-8">Your ultimate library-enhanced platform for seamless learning and exploration.</p>
            <a href="" class="bg-black text-white py-3 px-6 rounded-full text-lg hover:bg-gray-800 transition">Get Started Now</a>
        </div>
    </section>

    <!-- AIDA: Interest Section -->
    <section id="features" class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12">Why Choose Neurogamy?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4">Vast Library Access</h4>
                    <p>Explore thousands of books, journals, and resources tailored to your interests.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4">Smart Recommendations</h4>
                    <p>Our AI-driven system suggests content based on your learning habits.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4">Seamless Interface</h4>
                    <p>Navigate effortlessly with our clean, user-friendly design.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- AIDA: Desire Section -->
    <section id="about" class="bg-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold mb-8">Join a Community of Learners</h3>
            <p class="text-lg mb-6">Neurogamy connects you with a global community of knowledge seekers. Access curated content, collaborate with peers, and elevate your learning experience.</p>
            <p class="text-lg font-semibold">Ready to transform the way you learn?</p>
        </div>
    </section>

    <!-- AIDA: Action Section -->
    <section id="signup" class="bg-black text-white py-16 text-center">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold mb-6">Start Your Journey Today</h3>
            <p class="text-lg mb-8">Sign up now to unlock the full potential of Neurogamy's library-enhanced platform.</p>
            <form action="{{ route('register') }}" method="POST" class="flex justify-center">
                @csrf
                <input type="email" name="email" placeholder="Enter your email" class="p-3 rounded-l-lg text-black" required>
                <button type="submit" class="bg-white text-black py-3 px-6 rounded-r-lg hover:bg-gray-200 transition">Sign Up</button>
            </form>
        </div>
    </section>

    <!-- Footer Section -->
    <footer id="contact" class="bg-gray-900 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Neurogamy. All rights reserved.</p>
            <p class="mt-2">Contact us at <a href="mailto:support@neurogamy.com" class="underline">support@neurogamy.com</a></p>
        </div>
    </footer>
</body>
</html>