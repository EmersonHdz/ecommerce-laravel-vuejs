@props(['disabled' => false, 'errors', 'type' => 'text', 'label' => false])

<?php
/** @var \Illuminate\Support\ViewErrorBag $errors */
/** @var \Illuminate\View\ComponentAttributeBag  $attributes */
?>
<?php
$errorClasses = 'border-red-600 focus:border-red-600 ring-1 ring-red-600 focus:ring-red-600';
$defaultClasses = '';
$successClasses = 'border-emerald-500 focus:border-emerald-500 ring-1 ring-emerald-500 focus:ring-emerald-500';

$attributeName = preg_replace('/(\w+)\[(\w+)]/', '$1.$2', $attributes['name']);
?>
<div>
    @if ($label)
        <label>{{$label}}</label>
    @endif
    @if ($type === 'select')
        <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
            'class' => 'rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200 w-full ' .
             ($errors->has($attributeName) ? $errorClasses : (old($attributeName) ? $successClasses :$defaultClasses))
        ]) !!}>
            {{ $slot }}
        </select>
    @else
        <input {{ $disabled ? 'disabled' : '' }} type="{{$type}}" {!! $attributes->merge([
            'class' => 'w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200' .
             ($errors->has($attributeName) ? $errorClasses : (old($attributeName) ? $successClasses :$defaultClasses))
        ]) !!}>
    @endif
    @error($attributeName)
        <small class="text-red-600"> {{ $message }}</small>
    @enderror
</div>