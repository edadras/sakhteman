<a href="{{ $service->url }}" class="service-card" data-cursor="جزئیات">
    <div class="service-card__img"><img src="{{ media_url($service->image) }}" alt="{{ $service->title }}" loading="lazy"></div>
    <div class="service-card__body">
        <i class="service-card__icon {{ $service->icon ?: 'ri-building-2-line' }}"></i>
        <h3 class="service-card__title">{{ $service->title }}</h3>
        <p class="service-card__text">{{ $service->summary }}</p>
    </div>
    <span class="service-card__tab"><i class="ri-arrow-up-line"></i></span>
</a>
