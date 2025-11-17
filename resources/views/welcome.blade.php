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
                MBR EConcepts Computer Services
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

    <section class="py-20 bg-white border-t">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-center text-gray-900">Services</h2>

        <div class="mt-16 grid md:grid-cols-4 gap-12">
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 mx-auto bg-lime-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-lime-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                    </svg>

                </div>
                <h3 class="mt-5 font-semibold text-lg">REPAIR</h3>
                <p class="mt-3 text-gray-600">Reliable repairs, fast and efficient.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 mx-auto bg-lime-100 rounded-full">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-lime-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25A2.25 2.25 0 018.25 10.5H6A2.25 2.25 0 013.75 8.25V6zM3.75 15A2.25 2.25 0 016 12.75h2.25A2.25 2.25 0 0110.5 15v2.25A2.25 2.25 0 018.25 19.5H6A2.25 2.25 0 013.75 17.25V15zM12.75 6A2.25 2.25 0 0115 3.75h2.25A2.25 2.25 0 0119.5 6v2.25A2.25 2.25 0 0117.25 10.5H15A2.25 2.25 0 0112.75 8.25V6zM12.75 15A2.25 2.25 0 0115 12.75h2.25A2.25 2.25 0 0119.5 15v2.25A2.25 2.25 0 0117.25 19.5H15A2.25 2.25 0 0112.75 17.25V15z" />
                    </svg>
                </div>
                <h3 class="mt-5 font-semibold text-lg">LAYOUTING</h3>
                <p class="mt-3 text-gray-600">Designs that flow, layouts that work.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 mx-auto bg-lime-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-lime-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>

                </div>
                <h3 class="mt-5 font-semibold text-lg">PRINTING/BINDING</h3>
                <p class="mt-3 text-gray-600">Fast, reliable, professional printing.</p>
            </div>


            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 mx-auto bg-lime-100 rounded-full">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-lime-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                    </svg>
                </div>
                <h3 class="mt-5 font-semibold text-lg">PROGRAMMING</h3>
                <p class="mt-3 text-gray-600">Code that works, solutions that last.</p>
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
    <footer class="py-10 text-right pr-12 text-gray-500 text-sm bg-gradient-to-r from-lime-300 to-white">
        © 2025 MBR-E Concepts Computer Services. All rights reserved.
    </footer>

</body>
</html>
