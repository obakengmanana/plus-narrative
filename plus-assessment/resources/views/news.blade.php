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

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-center font-extrabold mt-2.5 mb-5 text-xl">Latest News</h1>
     
                    @if(isset($news) && count($news) > 0)
                    <ul>
                        @foreach($news as $article)
                        <li class="mb-6">
                            <h2 class="font-extrabold">{{ $article['title'] }}</h2>
                            <p class="text-slate-300" >{{ $article['body'] }}</p>
                            <a class="text-cyan-500 font-semibold" href="{{ $article['url'] }}" target="_blank">Read more</a>
                        </li>
                        @endforeach
                    </ul>
                    @elseif(isset($data['news']) && count($data['news']) > 0)
                    <ul>
                        @foreach($data['news'] as $article)
                        <li class="mb-6" >
                            <h2 class="font-extrabold">{{ $article['title'] }}</h2>
                            <p class="text-slate-300">{{ $article['body'] }}</p>
                            <a class="text-cyan-500 font-semibold" href="{{ $article['url'] }}" target="_blank">Read more</a>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p>No news available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>