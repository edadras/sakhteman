{{-- عنوان بخش به سبک قالب: $title, $en, $icon, $button = [label, url], $desc --}}
<div class="sec-head">
    <div class="sec-title" data-reveal="right">
        <span class="sec-title__bar"></span>
        <i class="sec-title__icon {{ $icon ?? 'ri-building-2-line' }}"></i>
        <div>
            <h2 data-split>{{ $title }}</h2>
            @if (! empty($en))<small>{{ $en }}</small>@endif
        </div>
    </div>
    @if (! empty($button))
        <a href="{{ $button[1] }}" class="btn btn--gray" data-reveal="left" data-magnetic=".2">{{ $button[0] }}</a>
    @elseif (! empty($desc))
        <p class="sec-desc" data-reveal>{{ $desc }}</p>
    @endif
</div>
