<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create New Rule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('rules.index') }}" class="btn btn-sm btn-outline">
                    ← {{ __('Back to Rules List') }}
                </a>
            </div>
            @if ($errors->any())
            <div class="mb-6">
                <x-alert status="error" message="{{ __('Please fix the errors below.') }}" />
            </div>
            @endif
            <div class="p-8 shadow bg-base-100 rounded-box">
                <form method="POST" action="{{ route('rules.store') }}" id="createRuleForm">
                    @csrf
                    <div class="mb-5">
                        <label for="rules" class="label">
                            <span class="font-medium label-text">{{ __('Rule Name') }}</span>
                        </label>
                        <x-default-input name="rules" />
                    </div>
                    <div class="mb-5">
                        <label for="content" class="label">
                            <span class="font-medium label-text">{{ __('Content') }}</span>
                        </label>
                        <x-default-textarea name="content" />
                    </div>
                    <div class="mb-5">
                        <label class="label">
                            <span class="font-medium label-text">{{ __('Main Keywords') }}</span>
                        </label>
                        <input type="hidden" name="main_keyword[]" id="keywords-hidden">
                        <div class="flex gap-2 mb-3">
                            <input
                                type="text"
                                id="keyword-input"
                                class="w-full input input-bordered"
                                placeholder="{{ __('Type a keyword and press Enter or click Add') }}" />
                            <button
                                type="button"
                                id="add-keyword"
                                class="btn btn-primary">
                                {{ __('Add') }}
                            </button>
                        </div>
                        <div id="keywords-container" class="flex flex-wrap gap-2 p-2 border border-gray-300 border-dashed rounded-md min-h-10 bg-base-200">
                            @if(old('main_keyword'))
                            @foreach(old('main_keyword') as $keyword)
                            <span class="gap-2 py-1 pl-3 pr-1 badge badge-primary badge-sm">
                                {{ $keyword }}
                                <button type="button" class="btn-remove-keyword" data-keyword="{{ $keyword }}">&times;</button>
                            </span>
                            @endforeach
                            @endif
                        </div>
                        @error('main_keyword')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Create Rule') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const $container = $('#keywords-container');
            const $input = $('#keyword-input');
            const $hiddenInput = $('#keywords-hidden');

            function addKeyword(value) {
                if (!value || value.trim() === '') return;
                value = value.trim();
                if ($container.find(`[data-keyword="${value}"]`).length > 0) {
                    $input.val('');
                    return;
                }
                const badge =
                    `<span class="gap-2 py-1 pl-3 pr-1 duration-150 badge badge-primary badge-sm animate-in slide-in-from-left-2">
                        ${value}
                        <button type="button" class="btn-remove-keyword opacity-70 hover:opacity-100" data-keyword="${value}">&times;</button>
                    </span>`;
                $container.append(badge);
                $input.val('');
                updateHiddenField();
            }

            $input.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    addKeyword($(this).val());
                }
            });
            $('#add-keyword').on('click', function() {
                addKeyword($input.val());
            });
            $container.on('click', '.btn-remove-keyword', function() {
                $(this).parent().remove();
                updateHiddenField();
            });

            function updateHiddenField() {
                const keywords = [];
                $container.find('.btn-remove-keyword').each(function() {
                    keywords.push($(this).data('keyword'));
                });
                $('[name^="main_keyword"]').remove();
                keywords.forEach(function(keyword) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'main_keyword[]',
                        value: keyword,
                    }).appendTo('#createRuleForm');
                });
            }
            updateHiddenField();
        });
    </script>
</x-app-layout>