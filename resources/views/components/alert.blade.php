@props([
    'type' => 'info' // 'info' | 'success' | 'warning' | 'danger'
])

<div class="alert alert-{{ $type }} d-flex gap-2" role="alert">
    <div>
        <i class="fa-solid fa-{{[
            'danger'  => 'radiation',
            'warning' => 'triangle-exclamation',
            'success' => 'circle-check',
            'info'    => 'circle-info',
            ][$type]}}">
        </i>
    </div>
    <div>
        {{ $slot }}
    </div>
</div>
