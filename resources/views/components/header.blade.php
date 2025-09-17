<div class="header flex flex-row justify-between pl-10 pr-10 pt-3">
    <button class="w-15 h-15" onclick="sidebar()" id="burger-button">
        <img class="w-15 select-none pointer-events-none z-10" src="/Images/burger.png" alt="Logo" draggable="false">
    </button>
    <div class="flex flex-row items-center gap-10">
        <div class="flex flex-col text-center items-center">
            @if ($type === 'viewprofile')
                <x-roundbutton type="button" id="backBtn" value="edit" onclick="location.href='{{ route('home') }}'" variant="gray" text="Back" addclass="''"/>
            @else
                <button class="w-10 h-10" id="profile-button" onclick="location.href='{{ route('view-profile') }}'">
                    <img class="w-25 select-none pointer-events-none z-10" src="/Images/userIcon.png" alt="Logo" draggable="false">
                </button>
                <h1 class="text-l">User</h1>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="underline bg-transparent border-none cursor-pointer">Log Out</button>
                </form>
            @endif
        </div>
    </div>
</div>  