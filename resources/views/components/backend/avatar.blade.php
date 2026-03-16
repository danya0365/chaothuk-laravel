@props([
    'src' => null,
    'name' => 'U',
    'size' => 'md',
    'border' => false,
    'ring' => 'ring-white dark:ring-gray-800',
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
        default => $size,
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

    $borderClasses = $border ? "ring-2 $ring" : '';
@endphp

<div {{ $attributes->merge(['class' => "relative rounded-full overflow-hidden flex-shrink-0 bg-gray-200 dark:bg-gray-700 $sizeClasses $borderClasses"]) }}>
    @if($src)
        <img src="{{ $src }}" class="w-full h-full object-cover" alt="{{ $name }}">
    @else
        <div class="w-full h-full bg-gradient-to-tr from-orange-500 to-orange-600 flex items-center justify-center shadow-inner">
            <span class="text-white font-bold {{ $textClasses }}">{{ mb_strtoupper(mb_substr($name, 0, 1)) }}</span>
        </div>
    @endif
</div>
