@switch($type)
    @case('error')
        <div class="my-3 py-3 px-2 bg-red-400 rounded-lg text-white text-center">
            {{ $slot }}
        </div>
    @break

    @case('warning')
        <div class="my-3 py-3 px-2 bg-orange-400 rounded-lg text-white text-center">
            {{ $slot }}
        </div>
    @break

    @default
        <div class="my-3 py-3 px-2 bg-blue-400 rounded-lg text-white text-center">
            {{ $slot }}
        </div>
@endswitch
