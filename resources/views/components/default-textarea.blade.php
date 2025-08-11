@props(['name'=>'' , 'type'=>'text' , 'value' => null])
<div>
    <textarea
        type="text"
        name="{{ $name}}"
        id="{{ $name }}"
        rows="3"
        class="textarea textarea-bordered w-full @error($name) textarea-error @enderror">{{$value ?? old($name)}}</textarea>
    @error($name)
    <p class="mt-1 text-sm text-error">{{ $message }}</p>
    @enderror
</div>