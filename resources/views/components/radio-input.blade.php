@props(['disabled' => false, 'selections', 'selected' => ''])

<div class="mt-1 block w-full">
    @foreach ($selections as $selection)
        <div class="flex items-center mb-2">
            <input {{ $selected == $selection['value'] ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}
                id="{{ $attributes['name'] . '-' . $selection['id'] }}" type="radio" name="{{ $attributes['name'] }}"
                value="{{ $selection['value'] }}"
                class="w-4 h-4 cursor-pointer border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600"
                {!! $attributes !!}>
            <label for="{{ $attributes['name'] . '-' . $selection['id'] }}"
                class="block ms-2  text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">
                {{ $selection['label'] }}
            </label>
        </div>
    @endforeach
</div>
