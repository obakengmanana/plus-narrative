

@props(['options', 'name', 'checked'])

@foreach($options as $option)
    <label class="block font-small text-xs text-gray-700 dark:text-gray-300" for="{{ $name }}_{{ $option }}">
        <input
            type="checkbox"
            id="{{ $name }}_{{ $option }}"
            name="{{ $name }}[]"
            value="{{ $option->name }}"
            {{ in_array($option->name, $checked) ? 'checked' : '' }}
        >
        {{ $option->name }}
    </label>
@endforeach


