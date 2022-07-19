<div class="overflow-x-auto">
    <table class="table-auto border-collapse mt-3">
        <thead class="bg-slate-200 text-slate-700">
            <tr>
                @foreach (['Method', 'Routes', 'Expects'] as $heading)
                    <th class="border-y border-slate-500 py-2 px-3 whitespace-normal">{{ $heading }}</th>
                @endforeach
                @if ($addon)
                    @if (is_array($addon))
                        @foreach ($addon as $field)
                            <th class="border-y border-slate-500 py-2 px-3 whitespace-normal">{{ $field }}</th>
                        @endforeach
                    @else
                        <th class="border-y border-slate-500 py-2 px-3 whitespace-normal">{{ $addon }}</th>
                    @endif
                @else
                @endif
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
