<div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-[rgb(77,77,77)] shadow-lg -translate-x-full pointer-events-none z-20 text-white rounded-tr-xl transition duration-300">
    <div class="p-4 flex flex-col gap-10">

        @php
            $links = [
                ["type"=>"home"],
                ["type"=>"job orders"],
                ["type"=>"billing"],
                ["type"=>"calendar"],
                ["type"=>"accounts"],
                ["type"=>"customers"]
            ];
        @endphp
        <button class="text-white w-16 h-16 font-ms" onclick="sidebar()">__</button>
        @foreach ($links as $link)
            @if ($link['type'] === $type)
                <button class="text-white text-2xl font-ms font-bold pointer-events-none">{{ strtoupper($link['type']) }}</button>
            @else
                <button class="text-white text-xl font-ms" onclick="location.href='{{ route($link['type']) }}'">{{ strtoupper($link['type']) }}</button>
            @endif
        @endforeach
    </div>
</div>