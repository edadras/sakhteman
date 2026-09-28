<a href="{{ $service->url }}" class="service-card" data-tilt="6">
    <div class="service-card__bg"><img src="{{ media_url($service->image) }}" alt="" loading="lazy"></div>
    <span class="service-card__num">{{ fa_num(str_pad($index + 1, 2, '0', STR_PAD_LEFT)) }}</span>
    <span class="service-card__icon"><i class="{{ $service->icon ?: 'ri-building-2-line' }}"></i></span>
    <h3 class="service-card__title">{{ $service->title }}</h3>
    <p class="service-card__text">{{ $service->summary }}</p>
    <span class="service-card__more">جزئیات خدمت <i class="ri-arrow-left-line"></i></span>
</a>
