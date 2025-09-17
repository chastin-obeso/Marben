<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Change Password</title>
</head>
<body class="bg-gradient-to-t from-lime-200 to-white font-ms">
    <div class="bg-container h-[100vh] w-[100vw] flex flex-col justify-center items-center">
            <div class="login-box flex flex-col p-[12vw] lg:p-[3vw] w-[80vw] lg:w-[20vw] h-auto bg-white rounded-lg shadow-lg items-center gap-[1vw]">
                <img class="logo w-[40vw] lg:w-[10vw] mb-[4vw] lg:mb-[1vw]" src="{{ asset('Images/logo.jpg') }}" alt="Logo">
                <form class="login-form flex flex-col items-center gap-[0.5vw]" action="/change-password" method="POST">
                    @csrf
                        <input class=" w-[60vw] lg:w-[15vw] border border-gray-300 p-2 rounded" type="text" id="name" name="name" placeholder="Username" required>
                        <input class=" w-[60vw] lg:w-[15vw] border border-gray-300 p-2 rounded" type="password" id="oldpassword" name="oldpassword" placeholder="Old Password" required>
                        <input class=" w-[60vw] lg:w-[15vw] border border-gray-300 p-2 rounded" type="password" id="newpassword" name="newpassword" placeholder="New Password" required>
                        <input class=" w-[60vw] lg:w-[15vw] border border-gray-300 p-2 rounded" type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                    <button class="loginbtn mt-[1vw] w-[40vw] mb-[1vw] lg:w-[10vw] h-[10vw] lg:h-[2.5vw] rounded-full bg-lime-300 text-black text-xs md:text-lg" type="submit">Save</button>
                    <a class="underline text-xs md:text-lg" href="{{ url('/login') }}">Back to Login</a>
                </form>                
            </div>
        </div>
</body>
</html>