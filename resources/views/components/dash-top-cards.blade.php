@props(['label' => '', 'value'=> 0, 'icon' => 'heroicon-s-stop'])


<div class="rounded-2xl border-2 border-white bg-foreground w-full p-5">
    <div class="flex gap-2">
        <x-dynamic-component :component="$icon" class="w-4 h-4 opacity-70"/>
        <p class="text-sm">{{ $label }}</p>
    </div>
    <h1 class="text-4xl font-bold mt-3">{{ $value }}</h1>
</div>