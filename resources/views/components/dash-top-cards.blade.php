@props(['label' => '', 'value' => 0, 'icon' => 'heroicon-s-stop'])


<div class="rounded-2xl border-2 border-white bg-foreground w-full p-5">
    <div class="flex justify-between items-start  opacity-70 ">
        <p class="text-sm uppercase tracking-wide opacity-70">{{ $label }}</p>
        <div class="bg-primary/20 p-2 rounded-2xl">
            <x-dynamic-component :component="$icon" class="w-8 h-8  text-primary" />
        </div>
    </div>
    <h1 class="text-5xl font-bold -mt-3 text-primary">{{ $value }}</h1>
</div>