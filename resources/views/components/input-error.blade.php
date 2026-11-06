@props(['name'])

@error($name)
    <p {{ $attributes->merge(['class' => 'mt-1.5 flex items-start gap-1 text-xs font-medium text-rose-600 dark:text-rose-400']) }}>
        <x-icon name="alert-triangle" class="mt-0.5 h-3.5 w-3.5 shrink-0" />
        <span>{{ $message }}</span>
    </p>
@enderror
