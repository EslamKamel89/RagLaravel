<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Category</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline">
                    ← Back
                </a>
            </div>

            @if ($errors->any())
            <div class="mb-6">
                <x-alert status="error" message="Fix the errors below." />
            </div>
            @endif

            <div class="p-8 shadow bg-base-100 rounded-box">
                <form method="POST" action="{{ route('categories.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label class="label">
                            <span class="font-medium label-text">Category Name</span>
                        </label>
                        <x-default-input name="name" value="{{ $category->name }}" />
                    </div>

                    <div class="flex justify-end">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>