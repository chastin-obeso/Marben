

@if ($variant === 'gray')
    <button class="font-ms outline outline-1 text-lg p-[2vw] w-auto lg:p-[0.5vw] lg:w-[10vw] rounded-full {{ $addclass }}" 
    id="{{ $id }}" 
    type="{{ $type }}" 
    value="{{ $value }}" 
    onclick="{{ $onclick }}"
>  
    {{ $text }}
</button>
@elseif ($variant === 'lime')
    <button class="font-ms bg-lime-300 text-lg p-[2vw] w-[30vw] lg:p-[0.5vw] lg:w-[10vw] rounded-full {{ $addclass }}"  
    id="{{ $id }}" 
    type="{{ $type }}" 
    value="{{ $value }}" 
    onclick="{{ $onclick }}"
>  
    {{ $text }}
</button>
@endif
