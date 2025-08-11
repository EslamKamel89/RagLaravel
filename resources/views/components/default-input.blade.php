@props(['name'=>'' , 'type'=>'text' , 'value'=> null])
<div>
    <input
        type="text"
        name="{{ $name}}"
        id="{{ $name }}"
        value="{{$value ?? old($name)}}"
        class="input my-1 input-bordered w-full @error($name) input-error @enderror">
    @error($name)
    <p class="mt-1 text-sm text-error">{{ $message }}</p>
    @enderror
</div>