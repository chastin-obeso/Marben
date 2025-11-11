<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Home</title>
</head>
<body class="bg-gradient-to-t from-slate-100 to-slate-0">
    <div class="p-10 flex flex-col">
        <x-header2 :type="''"/>
        <div class="pt-10 items-center text-center flex flex-col gap-10">
            <div>
                <h1 class="text-8xl font-[600]">MBR-E CONCEPTS<br>COMPUTER SERVICES</h1>
            </div>
            <div class="text-lg">
                founded by Corazon Roxas 
                and has been In operation since 2008. 
                It mainly comprises four types of services: <br>
                <b>repair, layouting, printing/binding, 
                and software development.</b>
            </div>
            <div>
                <div class="flex flex-row gap-5 pt-10">
                    <img class="w-90 h-60 hover:scale-[1.05] duration-300 rounded-lg" src="Images/thumbnails/1.jpg" alt="">
                    <img class="w-90 h-60 hover:scale-[1.05] duration-300 rounded-lg" src="Images/thumbnails/2.jpg" alt="">
                    <img class="w-90 h-60 hover:scale-[1.05] duration-300 rounded-lg" src="Images/thumbnails/3.jpg" alt="">
                    <img class="w-90 h-60 hover:scale-[1.05] duration-300 rounded-lg" src="Images/thumbnails/4.jpg" alt="">
                </div>
            </div>
        </div>
    </div>   
</body>
</html>