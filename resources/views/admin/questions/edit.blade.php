<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Question</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('questions.index') }}" class="btn btn-sm btn-outline">
                    ← Back
                </a>
            </div>

            @if ($errors->any())
            <div class="mb-6">
                <x-alert status="error" message="Fix the errors below." />
            </div>
            @endif

            <div class="p-8 shadow bg-base-100 rounded-box">
                <form method="POST" action="{{ route('questions.update', $question) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label class="label">
                            <span class="font-medium label-text">Category</span>
                        </label>

                        <select name="category_id" class="w-full select select-bordered">
                            <option value="">No Category</option>
                            @foreach ($categories as $id => $name)
                            <option value="{{ $id }}" @selected($question->category_id == $id)>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="label">
                            <span class="font-medium label-text">Question</span>
                        </label>
                        <x-default-textarea name="question" value="{{ $question->question }}" />
                    </div>

                    <div class="mb-5">
                        <label class="label">
                            <span class="font-medium label-text">Answer</span>
                        </label>
                        <x-default-textarea name="answer" value="{{ $question->answer }}" />
                    </div>

                    <div class="mb-5">
                        <label class="label">
                            <span class="font-medium label-text">Keywords</span>
                        </label>
                        <x-default-input name="keywords" value="{{ $question->keywords }}" />
                    </div>

                    <div class="flex justify-end">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>