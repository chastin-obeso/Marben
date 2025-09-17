<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>View Profile</title>
</head>
<body class="bg-gradient-to-t from-slate-300 to-slate-0">
    <div class="w-screen h-screen flex flex-col gap-0" style="background-image: url('/Images/logobg.png'); background-size: cover; background-position: center;">
        <x-header :type="'viewprofile'"/>

        <div class="profileModal w-[100vw] h-auto bg-white rounded-lg box-border" id="profileModal">
            <div class="modal-content pt-[1vw] w-[100vw]">
                <div class="bg-gradient-to-r from-lime-400 to-slate-0 p-2 pl-6 lg:pl-16">
                    <h1 class="font-ms text-lg lg:text-3xl font-bold">User Profile</h1>
                </div>
                <form action="/edit-user" method="POST">
                    @csrf
                    <div class="pl-[10vw] lg:pl-[5vw] pt-[1vw] flex flex-col gap-[5vw] lg:flex-row">
                        <div class="flex flex-col gap-[2vw] ml-[3vw] mt-[3vw]">
                            <h1 class="font-ms text-md lg:text-2xl font-semibold">Employee Details</h1>
                            {{-- sample user data --}}
                            @php 
                            $user = [
                                (object)
                                [
                                'first_name' => Auth::user()->first_name,
                                'last_name' => Auth::user()->last_name,
                                'contact_number' => Auth::user()->contact_number,
                                'role' => Auth::user()->role,
                                'username' => Auth::user()->username,
                                ]
                            ];
                            @endphp
                            {{-- $user variable is passed from db --}}
                            <div class="flex w-[80vw] lg:w-[40vw] flex-row items-center">
                                <label class="font-ms text-md lg:text-xl w-[25vw] lg:w-[10vw] h-[10vw] lg:h-[2.5vw] lg:pr-[2vw] content-center align-middle items-center" for="firstname">First Name</label>
                                <input class="p-[1vw] border border-gray-300 flex-grow lg:text-xl" type="text" id="first_name" name="firstname" value="{{ $user[0]->first_name }}" readonly>
                            </div>
                            <div class="flex w-[80vw] lg:w-[40vw]  flex-row items-center">
                                <label class="font-ms text-md lg:text-xl w-[25vw] lg:w-[10vw] h-[10vw] lg:h-[2.5vw] lg:pr-[2vw] content-center" for="lastname">Last Name</label>
                                <input class="p-[1vw] border border-gray-300 flex-grow lg:text-xl" type="text" id="last_name" name="lastname" value="{{ $user[0]->last_name }}" readonly>
                            </div>
                            <div class="flex w-[80vw] lg:w-[40vw]  flex-row items-center">
                                <label class="font-ms text-md lg:text-xl w-[25vw] lg:w-[10vw] h-[10vw] lg:h-[2.5vw] lg:pr-[2vw] content-center" for="contactnumber">Contact #</label>
                                <input class="p-[1vw] border border-gray-300 flex-grow lg:text-xl" type="number" id="contact_number" name="contactnumber" value="{{ $user[0]->contact_number }}" readonly>
                            </div>
                            <div class="flex w-[80vw] lg:w-[40vw]  flex-row items-center">
                                <label class="font-ms text-md lg:text-xl w-[25vw] lg:w-[10vw] h-[10vw] lg:h-[2.5vw] lg:pr-[2vw] content-center" for="role">Role</label>
                                <input class="p-[1vw] border border-gray-300 flex-grow lg:text-xl" type="text" id="role" name="role" value="{{ $user[0]->role }}" readonly>
                            </div> 
                        </div>
                        <div class="flex flex-col gap-[2vw] ml-[3vw] mt-[3vw]">
                            <h1 class="font-ms text-md lg:text-xl font-semibold">Profile Details</h1>
                            <div class="flex w-[80vw] lg:w-[40vw]  flex-row items-center">
                                <label class="font-ms text-md lg:text-xl w-[25vw] lg:w-[10vw] h-[10vw] lg:h-[2.5vw] lg:pr-[2vw] content-center" for="username">Username</label>
                                <input class="p-[1vw] border border-gray-300 flex-grow lg:text-xl" type="text" id="username" name="username" value="{{ $user[0]->username }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row justify-center lg:justify-end lg:pr-[3vw] gap-[1vw] mt-[5vw]">
                        <x-roundbutton id="editBtn" type="button" value="edit" onclick="toggleEditProfile()" text="Edit Profile" variant="lime" addclass="''" />
                        <x-roundbutton id="cancelBtn" type="button" value="cancel" onclick="toggleEditProfile()" text="Cancel" variant="gray" addclass="hidden" />
                        <x-roundbutton id="saveBtn" type="submit" value="update" onclick="toggleEditProfile()" text="Save" variant="lime" addclass="hidden" />
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <x-sidebar :type="'viewprofile'"/>
    </div>
</body>
</html>