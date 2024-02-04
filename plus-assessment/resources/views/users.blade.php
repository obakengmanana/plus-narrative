@php
$currentUser = Auth::user()
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
    </div>

    <div class=" container mx-auto mt-5 text-gray-200 bg-opacity-30 p-4 rounded-md">

        <form action="{{ route('users') }}" method="GET" id="searchForm">
            <div class="mb-6 search">
                <input type="text" name="search" class="searchTerm" id="search" placeholder="Who are you searching for?" value="{{ request('search') }}" onsubmit="event.preventDefault();">
                <button class="searchButton">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>

        @if(count($users) > 0)
        <table class="users-table w-full text-gray-200 dark:bg-gray-800  bg-opacity-80 border border-gray-300">
            <thead>
                <tr>
                    <th class="border-b p-2 text-white text-center">ID</th>
                    <th class="border-b p-2 text-white text-center">First Name</th>
                    <th class="border-b p-2 text-white text-center">Last Name</th>
                    <th class="border-b p-2 text-white text-center">Email</th>
                    <th class="border-b p-2 text-white text-center">Member Since</th>
                    <th class="border-b p-2 text-white text-center">Roles</th>
                    <th class="border-b p-2 text-white text-center">Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="border-b p-2 text-white text-center">{{ $user->id }}</td>
                    <td class="border-b p-2 text-white text-center">
                        @if($currentUser->isAdmin())
                        <a class="dark:text-cyan-400 font-bold" href="{{ route('update-user', ['id' => $user->id]) }}">
                            {{ $user->first_name }}
                        </a>
                        @else
                        {{ $user->first_name }}
                        @endif
                    </td>
                    <td class="border-b p-2 text-white text-center">{{ $user->last_name }}</td>
                    <td class="border-b p-2 text-white text-center">{{ $user->email }}</td>
                    <td class="border-b p-2 text-white text-center">{{ date_format($user->created_at,"d-M-Y") }}</td>
                    <td class="border-b p-2 text-white text-center">
                        @if($user->roles)
                        {{ $user->roles->pluck('name')->implode(', ') }}
                        @else
                        No roles
                        @endif
                    </td>
                    <td class="border-b p-2 text-white text-center">
                        @if($user->permissions)
                        {{ $user->permissions->pluck('name')->unique()->implode(', ') }}
                        @else
                        No permissions
                        @endif
                    </td>
                </tr>
                <!-- Add more columns as needed -->
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
        @else
        <p id="noResults">No users found.</p>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('search');
            var searchForm = document.getElementById('searchForm');
            var noResultsMessage = document.getElementById('noResults');

            searchInput.addEventListener('input', function() {
                // Delay the search to avoid making too many requests while typing
                setTimeout(function() {
                    searchForm.submit();
                }, 2000);
            });

        });
    </script>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);

        .search {
            width: 100%;
            position: relative;
            display: flex;
        }

        .searchTerm {
            width: 100%;
            border: 3px solid #00B4CC;
            border-right: none;
            padding: 5px;
            border-radius: 5px 0 0 5px;
            outline: none;
            color: #9DBFAF;
        }

        .searchTerm:focus {
            color: #00B4CC;
        }

        .searchButton {
            width: 40px;
            border: 1px solid #00B4CC;
            background: #00B4CC;
            text-align: center;
            color: #fff;
            border-radius: 0 5px 5px 0;
            font-size: 20px;
        }

        /*Resize the wrap to see the search bar change!*/
        .wrap {
            width: 30%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</x-app-layout>