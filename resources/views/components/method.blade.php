<x-td>
    @switch($type)
        @case('post')
            <div class="p-1 rounded-lg bg-sky-400 text-sky-900 opacity-80 font-semibold text-center">POST</div>
        @break

        @case('put')
            <div class="p-1 rounded-lg bg-orange-400 text-orange-900 opacity-80 font-semibold text-center">PUT</div>
        @break

        @case('delete')
            <div class="p-1 rounded-lg bg-red-400 text-red-900 opacity-80 font-semibold text-center">DELETE</div>
        @break

        @default
            <div class="p-1 rounded-lg bg-lime-400 text-lime-900 opacity-80 font-semibold text-center">GET</div>
    @endswitch
</x-td>
