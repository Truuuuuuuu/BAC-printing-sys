@props(['label' => '', 'value'=> 0, 'icon' => 'heroicon-s-stop'])


<div class="rounded-2xl border-2 border-white bg-foreground w-full p-5">
    <div class="flex gap-2 opacity-70 ">
        <x-dynamic-component :component="$icon" class="w-4 h-4 opacity-70"/>
        <p class="text-sm uppercase tracking-wide">{{ $label }}</p>
    </div>
    <h1 class="text-5xl font-bold mt-3 text-primary">{{ $value }}</h1>
</div>