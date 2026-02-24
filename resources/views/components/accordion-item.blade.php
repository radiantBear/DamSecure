@props([
    'accordionId',
    'id',
    'header',
    'show' => false
])

<div class="accordion-item">
    <h4 class="accordion-header">
        <button class="accordion-button {{ $show ? '' : 'collapsed'}}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $id }}" aria-expanded="{{ $show ? 'true' : 'false' }}" aria-controls="{{ $id }}">
            {{ $header }}
        </button>
    </h4>
    <div id="{{ $id }}" class="accordion-collapse collapse {{ $show ? 'show' : '' }}" data-bs-parent="#{{ $accordionId }}">
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>
