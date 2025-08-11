@props(['name'=>'' , 'type'=>'text'])
<div>
    <textarea
        type="text"
        name="{{ $name}}"
        id="{{ $name }}"
        rows="3"
        value="{{ old($name)}}"
        class="textarea textarea-bordered w-full @error($name) textarea-error @enderror"></textarea>
    @error($name)
    <p class="mt-1 text-sm text-error">{{ $message }}</p>
    @enderror
</div>