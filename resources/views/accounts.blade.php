<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Manage Accounts</title>
</head>
<body class="bg-gradient-to-t from-slate-300 to-slate-0">
    <div class="w-screen h-screen flex flex-col gap-0" style="background-image: url('/Images/logobg.png'); background-size: cover; background-position: center;">
        {{-- burger button + profile button --}}
        <x-header :type="''"/>  
        <div class="bg-gradient-to-r from-lime-400 to-slate-0 p-2 pl-16">
            <h1 class="font-ms text-2xl font-bold">Accounts</h1>
        </div>

        {{-- div with two divs: employee account table and account crud --}}
        <div class="flex flex-col items-center md:flex-row md:justify-between p-5 gap-[1vw]">

           {{-- Employee Accounts Table --}}
            <div class="bg-white shadow-xl p-2 h-[60vh] md:p-5 rounded-lg md:h-[70vh] md:w-auto flex-grow">
                <div class="h-[55vh] md:h-[65vh] overflow-y-scroll border border-black">
                    <table id="employeeTable" class="table-fixed text-center w-full border border-black">
                        <thead class="font-ms text-sm md:text-lg font-semibold sticky top-0 bg-lime-300">
                            <tr>
                                <th class="sm:pt-[1vw] sm:pb-[1vw] pr-[1vw] pl-[1vw] pt-[0.2vw] pb-[0.2vw]">ID</th>
                                <th class="sm:pt-[1vw] sm:pb-[1vw] pr-[1vw] pl-[1vw] pt-[0.2vw] pb-[0.2vw]">First Name</th>
                                <th class="sm:pt-[1vw] sm:pb-[1vw] pr-[1vw] pl-[1vw] pt-[0.2vw] pb-[0.2vw]">Last Name</th>
                                <th class="sm:pt-[1vw] sm:pb-[1vw] pr-[1vw] pl-[1vw] pt-[0.2vw] pb-[0.2vw]">Contact Number</th>
                            </tr>
                        </thead>
                        <tbody class="font-ms text-md border border-black">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="border border-black pr-[1vw] pl-[1vw] sm:pt-[1vw] sm:pb-[1vw] pt-[0.3vw] pb-[0.3vw] cursor-pointer" onclick="selectRow(this)">{{ $user->id }}</td>
                                    <td class="border border-black pr-[1vw] pl-[1vw] sm:pt-[1vw] sm:pb-[1vw] pt-[0.3vw] pb-[0.3vw] cursor-pointer" onclick="selectRow(this)">{{ $user->first_name }}</td>
                                    <td class="border border-black pr-[1vw] pl-[1vw] sm:pt-[1vw] sm:pb-[1vw] pt-[0.3vw] pb-[0.3vw] cursor-pointer" onclick="selectRow(this)">{{ $user->last_name }}</td>
                                    <td class="border border-black pr-[1vw] pl-[1vw] sm:pt-[1vw] sm:pb-[1vw] pt-[0.3vw] pb-[0.3vw] cursor-pointer" onclick="selectRow(this)">{{ $user->contact_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACCOUNT CRUD --}}
            <div class="bg-white shadow-xl p-5 rounded-lg h-[70vh] min-w-[30vw] hidden z-30 md:z-0 md:block">
                <form class="flex flex-col gap-[0.5vw] p-5" action="/account-crud"  method="POST">
                    @csrf
                        <div class="flex items-left md:flex-col lg:flex-row">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] lg:text-right flex-grow">Employee ID</label>
                            <input id="id" type="text" name="id" placeholder="000000" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw] " readonly><!-- This input is for displaying the ID of the selected account -->
                        </div>
                        <div class="flex items-left md:flex-col lg:flex-row">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] flex-grow lg:text-right">First Name</label>
                            <div class="flex flex-col">
                                <input id="firstname" type="text" name="first_name" placeholder="Juan" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw]">
                                <div>
                                    @error('first_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex items-left md:flex-col lg:flex-row">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] flex-grow lg:text-right">Last Name</label>
                            <input id="lastname" type="text" name="last_name" placeholder="Dela Cruz" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw]">
                        </div>
                        <div class="flex items-left md:flex-col lg:flex-row">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] flex-grow lg:text-right">Contact Number</label>
                            <input id="contactnumber" type="number" name="contact_number" placeholder="0000-000-0000" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw]">
                        </div>
                        <div class="flex items-left md:flex-col lg:flex-row">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] flex-grow lg:text-right">Username</label>
                            <input id="username" type="text" name="username" value="{{old('username')}}"  placeholder="username" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw]">
                        </div>
                        <div class="flex items-left md:flex-col lg:flex-row">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] flex-grow lg:text-right">Password</label>
                            <input id="password" type="password" name="password" value="{{old('password')}}" placeholder="Password" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw]">
                        </div>
                        <div class="text-right">
                            <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password
                        </div>
                        <div class="flex items-left md:flex-col lg:flex-row lg:items-center">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] flex-grow lg:text-right">Status</label>
                            <select name="status" id="status" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw] font-ms text-xs">
                                <option class="font-ms text-sm" value="active">Active</option>
                                <option class="font-ms text-sm" value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-left md:flex-col lg:flex-row lg:items-center">
                            <label for="id" class="font-ms text-sm font-bold mr-[1vw] flex-grow lg:text-right">Role</label>
                            <select name="role" id="role" class="border border-gray-300 p-2 rounded h-[2vw] min-w-[15vw] font-ms text-xs">
                                <!--Sample data for demonstration purposes; to be replaced with data from DB*-->
                                @php
                                $roles = [
                                    (object)[ 'id' => 1, 'name' => 'Admin' ],
                                    (object)[ 'id' => 2, 'name' => 'Customer Support' ],
                                    (object)[ 'id' => 3, 'name' => 'Cashier' ],
                                    (object)[ 'id' => 3, 'name' => 'Service Personnel' ],
                                    (object)[ 'id' => 3, 'name' => 'HR' ],
                                ];
                                @endphp
                                <!--Sample data for demonstration purposes; to be replaced with data from DB*-->
                                @foreach ($roles as $role)
                                    <option class="font-ms text-sm" value="{{$role->name}}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                    <a class="underline text-right text-sm" href="">Create a Custom Role</a>
                    <div class="flex flex-row pt-[1vw] justify-end">
                        <button class="font-ms border border-black text-md p-0.5 min-w-[5vw] rounded-full" onclick="clearhighlight()" type="reset" value="update">Clear</button>
                    </div>
                    <hr class="border-t-1 border-black mt-[1vw] mb-[1vw]">
                    <div class="flex flex-row justify-center gap-[1vw]">
                        <button class="font-ms bg-lime-300 text-lg p-1 min-w-[8vw] rounded-full" type="submit" value="add" name="action">Add</button>
                        <button class="font-ms bg-lime-300 text-lg p-1 min-w-[8vw] rounded-full" type="submit" value="update" name="action">Update</button>
                    </div>
                </form>
                {{-- Show validation errors --}}
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
            </div>
        </div>
        </div>

        {{-- Filter Section --}}
        <div class="text-center bg-white shadow-xl content-center pl-3 ml-auto mr-auto mb-[3vw] md:mb-0 md:ml-10 rounded-lg w-[90vw] h-[10vh] md:w-[45vw] md:h-[8vh]">
            <button class="font-ms bg-gray-300 text-lg p-1 min-w-[8vw] rounded-full" type="submit" value="add">PREV</button>
            <button class="font-ms bg-gray-300 text-lg p-1 min-w-[8vw] rounded-full" type="submit" value="update">NEXT</button>   
            <input class="ml-[2vw] border border-black p-[0.5vw] rounded-md" id="filterword" name="filterword" type="text" placeholder="Look for...">
            <select class="border border-black p-[0.5vw] rounded-md" name="filtercategory" id="filtercategory">
                <option value="ID">ID</option>
                <option value="ID">First Name</option>
                <option value="ID">Last Name</option>
                <option value="ID">Contact Number</option>
            </select>
        </div>

        {{-- Buttons that only appear when naka phone size --}}
        <div class="flex flex-row justify-center gap-[1vw] md:hidden">
            <button class="font-ms bg-lime-300 text-lg p-3 min-w-[8vw] rounded-full" onclick="showEmployeeModal(1)" value="add">New Employee</button>
            <button class="font-ms bg-lime-300 text-lg p-3 min-w-[8vw] rounded-full" onclick="showEmployeeModal(2)" value="update">Update</button>
        </div>

        <!-- Modal for Employee CRUD -->
        <div id="modalBg" class="bg-black opacity-40 z-20 fixed w-[100vw] h-[100vh] hidden"></div>
        <div id="employeeModal" class="bg-white shadow-xl p-5 rounded-lg max-h-[90vh] max-w-[80vw] hidden fixed inset-0 m-auto z-30 flex flex-col items-center justify-center">
                <button class="font-ms font-bold text-xl text-right w-[70vw] pt-[5vw]" onclick="showEmployeeModal()">x</button>
                <form id="employeeModalForm" class="flex flex-col gap-[0.5vw] p-5" action="/account-crud" type="post"> 
                    @csrf
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] lg:text-right flex-grow">Employee ID</label>
                            <input id="mid" type="text" name="id" placeholder="000000" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw]" readonly><!-- This input is for displaying the ID of the selected account -->
                        </div>
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] flex-grow lg:text-right">First Name</label>
                            <input id="mfirstname" type="text" name="firstname" placeholder="Juan" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw]">
                        </div>
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] flex-grow lg:text-right">Last Name</label>
                            <input id="mlastname" type="text" name="lastname" placeholder="Dela Cruz" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw]">
                        </div>
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] flex-grow lg:text-right">Contact Number</label>
                            <input id="mcontactnumber" type="number" name="contactnumber" placeholder="0000-000-0000" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw]">
                        </div>
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] flex-grow lg:text-right">Username</label>
                            <input id="musername" type="text" name="username" placeholder="Username" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw]">
                        </div>
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] flex-grow lg:text-right">Password</label>
                            <input id="mpassword" type="password" name="password" placeholder="Password" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw]">
                        </div>
                        <div class="text-right">
                            <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password
                        </div>
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] flex-grow">Status</label>
                            <select name="status" id="mstatus" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw] font-ms text-l">
                                <option class="font-ms text-sm" value="active">Active</option>
                                <option class="font-ms text-sm" value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-left flex-col">
                            <label for="id" class="font-ms text-xl font-bold mr-[1vw] flex-grow lg:text-right">Role</label>
                            <select name="role" id="mrole" class="border border-gray-300 p-2 rounded h-[8vw] w-[70vw] font-ms text-sm">
                                <!--Sample data for demonstration purposes; to be replaced with data from DB*-->
                                @php
                                $roles = [
                                    (object)[ 'id' => 1, 'name' => 'Admin' ],
                                    (object)[ 'id' => 2, 'name' => 'Customer Support' ],
                                    (object)[ 'id' => 3, 'name' => 'Cashier' ],
                                    (object)[ 'id' => 3, 'name' => 'Service Personnel' ],
                                    (object)[ 'id' => 3, 'name' => 'HR' ],
                                ];
                                @endphp
                                <!--Sample data for demonstration purposes; to be replaced with data from DB*-->
                                @foreach ($roles as $role)
                                    <option class="font-ms text-sm w-[10vw]" value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                    <a class="underline text-right text-sm" href="">Create a Custom Role</a>
                    <div class="flex flex-row pt-[1vw] justify-end">
                        <button class="font-ms border border-black text-md p-0.5 min-w-[15vw] rounded-full" type="reset" value="update">Clear</button>
                    </div>
                    <hr class="border-t-1 border-black mt-[1vw] mb-[1vw]">
                    <div class="flex flex-row justify-center gap-[1vw]">
                        <button class="font-ms bg-lime-300 text-lg p-1 min-w-[20vw] rounded-full" type="submit" value="add">Add</button>
                        <button class="font-ms bg-lime-300 text-lg p-1 min-w-[20vw] rounded-full" type="submit" value="update">Update</button>
                    </div>
                </form>
            </div>

        <!-- Sidebar -->
        <x-sidebar :type="'accounts'"/>
    </div>
</body>
</html>