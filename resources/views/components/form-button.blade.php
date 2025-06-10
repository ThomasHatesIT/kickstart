<button {{$attributes->merge(['class' => 'btn btn-primary text-white fw-semibold px-3 py-2 rounded shadow-sm focus:shadow-lg', 'type' => 'submit'])}}>
    {{$slot}}
</button>