@php
use App\View\Components\CheckboxGroup;
@endphp

<x-app-layout>
    <div class="py-12">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                @if(auth()->user()->roles)
                ({{ auth()->user()->roles->pluck('name')->implode(', ') }})
                @endif
            </h2>
        </x-slot>
        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    

                <form method="POST" action="{{ route('delete-user', ['user' => $user]) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit" >
                        {{ __('Delete User') }}
                    </x-danger-button>
                </form>



            </div>
        </div>
    </div>



    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('update-user', ['id' => $user->id]) }}">

                        @csrf

                        <!-- First Name -->
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $user->first_name ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Last Name -->
                        <div class="mt-4">
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $user->last_name ?? '')" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Roles Dropdown -->
                        @if(isset($roles))
                        <div class="mt-4">
                            <x-input-label for="roles" :value="__('Roles')" />
                            <x-checkbox-group id="roles" :options="$roles" name="roles[]" :checked="old('roles', $user->roles->pluck('name')->toArray() ?? [])" />
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                        </div>
                        @endif

                        <div class="flex items-center justify-start mt-4">
                            <x-primary-button>
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (session('admin-delete-error'))
    <script>
        // Display a popup using JavaScript
        alert("{{ session('admin-delete-error') }}");
    </script>
    @endif
</x-app-layout>