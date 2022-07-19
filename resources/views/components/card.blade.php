<div {{ $attributes->merge(['class' => 'border border-slate-100 bg-slate-50 shadow py-3 px-5 ' . $addonClass]) }}>
    <h2 class="text-xl text-center">{{ $title }}</h2>
    <hr>
    <div class="md:flex md:flex-col md:items-center">
        {{ $slot }}
    </div>
</div>
