<!-- resources/views/components/dropdown.blade.php -->

@props(['trigger', 'options', 'name', 'selected'])

<div class="relative">
    <div id="{{ $trigger }}" onclick="toggleDropdown('{{ $trigger }}')">
        {{ $trigger }}
    </div>

    <div id="{{ $trigger }}-dropdown" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg ltr:origin-top-left rtl:origin-top-right start-0" style="display: none;">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-700">
            @foreach($options as $option)
                <label for="{{ $name }}_{{ $option }}">
                    <input type="checkbox" id="{{ $name }}_{{ $option }}" name="{{ $name }}[]" value="{{ $option }}" {{ is_array($selected) && in_array($option, $selected) ? 'checked' : '' }}>
                    {{ $option }}
                </label>
            @endforeach
        </div>
    </div>
</div>

<script>
    function toggleDropdown(triggerId) {
        var dropdown = document.getElementById(triggerId + '-dropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(event) {
        var trigger = document.getElementById('{{ $trigger }}');
        var dropdown = document.getElementById('{{ $trigger }}-dropdown');

        if (event.target !== trigger && event.target !== dropdown) {
            dropdown.style.display = 'none';
        }
    });
</script>
