<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Filament Style Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            /* Filament primary color */
            --primary: #A3E635;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800">

    <!-- Navigation -->
    <header class="bg-white border-b" style="position: sticky; top: 0; z-index: 100;">
        <div class=" pl-10 pr-10 m-auto px-6 py-4 flex items-center justify-between gap-8">
            <div class="flex items-center space-x-2">
                <img class="w-24 rounded" src="images/logo.png"></img>
            </div>

            <div>
                <a href="{{ route('login') }}"
                   class="px-6 py-4 text-md font-medium bg-lime-400 text-black rounded-xl shadow hover:bg-lime-500 transition">
                    Employee Portal
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-24 pb-32 bg-gradient-to-r from-lime-300 to-white">

        <div class="max-w-4xl mx-auto text-center px-6">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 leading-tight">
                MBR-E Concepts Computer Services
            </h1>

            <p class="mt-6 text-lg text-gray-600">
                A company founded by Corazon Roxas and has been in operation since 2008. 
            </p>

             <p class="mt-6 text-xl font-semibold text-gray-600">
                REPAIR | LAYOUTING | PRINTING/BINDING | PROGRAMMING
            </p>

            <div class="mt-10 flex justify-center gap-4">
                <a href="https://www.facebook.com/profile.php?id=100065692772967"
                   class="px-6 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white border-t">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-900">Services</h2>

            <div class="mt-16 grid md:grid-cols-4 gap-12">
                <div class="text-center">
                    
                    <h3 class="mt-5 font-semibold text-lg">REPAIR</h3>
                    <p class="mt-3 text-gray-600">Build interfaces quickly with reusable UI components.</p>
                </div>

                <div class="text-center">
                    
                    <h3 class="mt-5 font-semibold text-lg">LAYOUTING</h3>
                    <p class="mt-3 text-gray-600">A modern, minimal style inspired by the Filament ecosystem.</p>
                </div>

                <div class="text-center">
                    
                    <h3 class="mt-5 font-semibold text-lg">PRINTING/BINDING</h3>
                    <p class="mt-3 text-gray-600">A modern, minimal style inspired by the Filament ecosystem.</p>
                </div>

                <div class="text-center">
                    
                    <h3 class="mt-5 font-semibold text-lg">PROGRAMMING</h3>
                    <p class="mt-3 text-gray-600">Simple, readable, and flexible components for all use cases.</p>
                </div>
            </div>
        </div>
    </section>
    <p><hr></p>
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Featured Gallery
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="rounded-xl overflow-hidden border bg-gray-100">
                    <img src="{{ asset('images/thumbnails/1.jpg') }}" class="w-full h-full object-cover" />
                </div>

                <div class="rounded-xl overflow-hidden border bg-gray-100">
                    <img src="{{ asset('images/thumbnails/2.jpg') }}" class="w-full h-full object-cover" />
                </div>

                <div class="rounded-xl overflow-hidden border bg-gray-100">
                    <img src="{{ asset('images/thumbnails/3.jpg') }}" class="w-full h-full object-cover" />
                </div>

                <div class="rounded-xl overflow-hidden border bg-gray-100">
                    <img src="{{ asset('images/thumbnails/4.jpg') }}" class="w-full h-full object-cover" />
                </div>

            </div>

        </div>
    </section>


    <!-- Footer -->
    <footer class="py-10 text-center text-gray-500 text-sm bg-gradient-to-r from-lime-300 to-white">
        © 2025 MBR-E Concepts Computer Services. All rights reserved.
    </footer>

</body>
</html>
