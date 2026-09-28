@php
    $wa = setting('whatsapp');
    $networks = [
        'instagram' => ['ri-instagram-line', setting('instagram')],
        'telegram' => ['ri-telegram-line', setting('telegram')],
        'whatsapp' => ['ri-whatsapp-line', $wa ? (str_starts_with($wa, 'http') ? $wa : 'https://wa.me/'.preg_replace('/\D/', '', en_num($wa))) : null],
        'linkedin' => ['ri-linkedin-line', setting('linkedin')],
        'youtube' => ['ri-youtube-line', setting('youtube')],
        'aparat' => ['ri-film-line', setting('aparat')],
    ];
@endphp
<div class="{{ $class ?? 'social' }}">
    @foreach ($networks as $name => [$icon, $url])
        @if ($url)
            <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $name }}"><i class="{{ $icon }}"></i></a>
        @endif
    @endforeach
</div>
