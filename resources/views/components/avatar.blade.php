@props([
    'src' => null,
    'name' => 'U',
    'size' => 'md',
    'border' => true,
    'ring' => 'ring-gray-700 group-hover:ring-orange-500/50',
])

@php
    $sizeClasses = match($size) {
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16',
        '2xl' => 'w-24 h-24',
        '3xl' => 'w-32 h-32',
        default => $size, // Allow arbitrary CSS classes like 'w-14 h-14'
    };
    
    $textClasses = match($size) {
        'xs' => 'text-[10px]',
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-base',
        'xl' => 'text-xl',
        '2xl' => 'text-3xl',
        '3xl' => 'text-5xl',
        default => 'text-base',
    };

    $borderClasses = $border ? "ring-2 $ring transition-all duration-300" : '';
@endphp

<div {{ $attributes->merge(['class' => "relative rounded-full overflow-hidden flex-shrink-0 bg-gray-800 $sizeClasses $borderClasses"]) }}>
    @if($src)
        <img src="{{ image_url($src) }}" class="w-full h-full object-cover" alt="{{ $name }}">
    @else
        <div class="w-full h-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
            <span class="text-white font-black {{ $textClasses }}">{{ mb_strtoupper(mb_substr($name, 0, 1)) }}</span>
        </div>
    @endif
</div>
