<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Categories Management' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-6">
                <x-alert status="success" message="{{ session('success') }}" />
            </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    {{ 'Create Category' }}
                </a>
            </div>

            <div class="flex gap-2 mb-6">
                <form method="GET" class="flex w-full gap-2">
                    <input
                        type="text"
                        name="filter"
                        value="{{ $filter }}"
                        placeholder="Search categories..."
                        class="w-full input input-bordered">
                    <button class="btn btn-primary">Search</button>
                </form>
            </div>

            <div class="overflow-x-auto shadow bg-base-100 rounded-box">
                <table class="table w-full table-zebra">
                    <thead>
                        <tr class="text-base">
                            <th>#</th>
                            <th>Name</th>
                            <th class="w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $loop->index }}</td>
                            <td class="font-medium">{{ $category->name }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="btn btn-square btn-outline btn-sm">
                                        ✏️
                                    </a>

                                    <button
                                        class="btn btn-square btn-outline btn-error btn-sm open-delete"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}">
                                        🗑
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <x-dialog id="deleteModal">
        <x-slot:title>Confirm Deletion</x-slot:title>
        <p>Delete category: <strong id="deleteName"></strong>?</p>

        <x-slot:actions>
            <button class="btn btn-ghost" id="cancelDelete">Cancel</button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button class="text-white btn btn-error">Delete</button>
            </form>
        </x-slot:actions>
    </x-dialog>

    <script>
        $(function() {
            $('.open-delete').click(function() {
                $('#deleteName').text($(this).data('name'));
                $('#deleteForm').attr('action', '/admin/categories/' + $(this).data('id'));
                document.getElementById('deleteModal').showModal();
            });

            $('#cancelDelete').click(() => {
                document.getElementById('deleteModal').close();
            });
        });
    </script>
</x-app-layout>