<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 ">
            {{ __('Rules Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Success and Error Messages -->
            @if (session('success'))
            <div class="mb-6">
                <x-alert status="success" message="{{ session('success') }}" />
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6">
                <x-alert status="error" message="{{ session('error') }}" />
            </div>
            @endif

            <!-- Create New Rule Button -->
            <div class="mb-6">
                <a href="{{ route('rules.create') }}" class="btn btn-primary">
                    {{ __('Create New Rule') }}
                </a>
            </div>

            <!-- Rules Table -->
            <div class="overflow-x-auto shadow bg-base-100 rounded-box">
                <table class="table w-full table-zebra">
                    <!-- head -->
                    <thead>
                        <tr class="text-base">
                            <th>{{ __('#') }}</th>
                            <th>{{ __('Rule Name') }}</th>
                            <th>{{ __('Content') }}</th>
                            <th>{{ __('Main Keywords') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="rules-table-body">
                        @forelse ($rules as $index => $rule)
                        <tr data-id="{{ $rule['_id'] }}" data-name="{{ addslashes($rule['rules']) }}">
                            <th>{{ $index + 1 }}</th>
                            <td>
                                <span class="font-medium">{{ $rule['rules'] ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="max-w-xs truncate" title="{{ $rule['content'] }}">
                                    {{ $rule['content'] }}
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($rule['main_keyword'] as $keyword)
                                    <span class="badge badge-ghost badge-sm">{{ $keyword }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('rules.edit', $rule['_id']) }}" class="btn btn-square btn-outline btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <button
                                        type="button"
                                        class=" btn btn-square btn-outline btn-sm btn-error open-delete-modal"
                                        data-id="{{ $rule['_id'] }}"
                                        data-name="{{ $rule['rules'] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                {{ __('No rules found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-dialog id="rules.index.deleteModal">
        <x-slot:title>{{ __('Confirm Deletion') }}</x-slot:title>
        <p>{{ __('Are you sure you want to delete the rule:') }} <strong id="ruleName"></strong>?</p>
        <x-slot:actions>
            <button type="button" class="btn btn-ghost" id="cancelDelete">{{ __('Cancel') }}</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-white btn btn-error">{{ __('Delete') }}</button>
            </form>
        </x-slot:actions>
    </x-dialog>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.open-delete-modal', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#ruleName').text(name);
                $('#deleteForm').attr('action', '/admin/rules/' + id);

                const modal = document.getElementById('rules.index.deleteModal');
                modal.showModal();
            });

            $('#cancelDelete').on('click', function() {
                const modal = document.getElementById('rules.index.deleteModal');
                modal.close();
            });

            $('#rules.index.deleteModal').on('close', function() {
                $('#deleteForm').attr('action', '');
                $('#ruleName').text('');
            });
        });
    </script>
</x-app-layout>