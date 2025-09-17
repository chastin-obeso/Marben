<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Manage Accounts</title>
</head>
<body class="bg-gradient-to-t from-slate-300 to-slate-0 pt-20">
    <form action="{{route('test.store')}}" method="POST">
        <input type="text" name="name" class="border-b border-b-3 border-blue-600">
        <div>
            @error('name')
                {{ $message }}
            @enderror
        </div>
        @csrf
        <button class="bg-blue-500 px-3 py-2 rounded">Submit</button>
    </form>
</body>
</html>