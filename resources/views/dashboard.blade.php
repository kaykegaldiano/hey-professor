<x-app-layout>
    <x-slot name="header">

        <x-header>
            {{ __('Vote for a question') }}
        </x-header>

    </x-slot>

    <x-container>
        <div class="dark:text-gray-400 uppercase font-bold mb-1">List of Questions</div>

        <div class="dark:text-gray-400 space-y-4">

            @foreach($questions as $item)
                <x-question :question="$item" />
            @endforeach

        {{ $questions->links() }}

        </div>

    </x-container>

</x-app-layout>
