<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Manage Customers</title>
</head>
<body class="bg-gradient-to-t from-slate-300 to-slate-0">
    <div class="w-screen h-screen flex flex-col" style="background-image: url('/Images/logobg.png'); background-size: cover; background-position: center;">
        <x-header :type="''"/> 
        <x-sidebar :type="'calendar'"/>
    </div>
</body>
</html>