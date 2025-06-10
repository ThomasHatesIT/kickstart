```html
@props(['name'])

@error($name)
    <p {{ $attributes->merge(['class' => 'small text-danger fw-semibold mt-1']) }}>
        {{ $message }}
    </p>
@enderror
```