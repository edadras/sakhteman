@if ($partners->count())
<section class="section">
    <div class="container">
        @include('partials.hang-head', ['title' => 'افتخارات همکاری', 'en' => 'Partnership honors', 'icon' => 'ri-user-star-line'])
        <div class="partners-box">
            <div class="partners-grid" data-stagger=".06">
                @foreach ($partners as $partner)
                    <div class="partner">
                        @if ($partner->url)
                            <a href="{{ $partner->url }}" target="_blank" rel="noopener" title="{{ $partner->name }}"><img src="{{ media_url($partner->logo) }}" alt="{{ $partner->name }}" loading="lazy"></a>
                        @else
                            <img src="{{ media_url($partner->logo) }}" alt="{{ $partner->name }}" title="{{ $partner->name }}" loading="lazy">
                        @endif
                        <span class="partner-marker"></span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
