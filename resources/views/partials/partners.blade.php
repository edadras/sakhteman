@if ($partners->count())
<section class="partners marquee" data-marquee="50" data-marquee-reverse style="border:0;padding:70px 0">
    <div class="marquee__track">
        <div class="marquee__group">
            @foreach ($partners as $partner)
                @if ($partner->url)
                    <a class="partner-logo" href="{{ $partner->url }}" target="_blank" rel="noopener" title="{{ $partner->name }}"><img src="{{ media_url($partner->logo) }}" alt="{{ $partner->name }}" loading="lazy"></a>
                @else
                    <span class="partner-logo" title="{{ $partner->name }}"><img src="{{ media_url($partner->logo) }}" alt="{{ $partner->name }}" loading="lazy"></span>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
