<x-app-layout>
    <x-slot name="header">

        <x-header>
            {{ __('Edit Question') }} :: {{ $question->id }}
        </x-header>

    </x-slot>

    <x-container>

        <x-form :action="route('question.update', $question)" method="put">

            <x-textarea name="question" label="Your question" :value="$question->question" />

            <x-btn.primary>Save</x-btn.primary>
            <x-btn.reset>Cancel</x-btn.reset>

        </x-form>

    </x-container>

</x-app-layout>
