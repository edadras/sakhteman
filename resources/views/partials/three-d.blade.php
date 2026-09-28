@if ($project3d)
<section class="section tower-section grid-bg" data-grid-spot>
    <div class="container tower-layout">
        <div class="tower-text">
            @include('partials.sec-head', ['title' => 'پروژه در سه بعد', 'en' => '3D preview', 'icon' => 'ri-box-3-line'])
            <h3 data-reveal>{{ $project3d->title }}</h3>
            <p data-reveal>{{ $project3d->summary }}</p>
            <ul class="tower-facts" data-stagger=".08">
                @if ($project3d->floors)<li><i class="ri-stack-line"></i><span>طبقات</span><b>{{ fa_num($project3d->floors) }}</b></li>@endif
                @if ($project3d->area)<li><i class="ri-ruler-2-line"></i><span>زیربنا</span><b>{{ fa_num($project3d->area) }}</b></li>@endif
                @if ($project3d->location)<li><i class="ri-map-pin-2-line"></i><span>موقعیت</span><b>{{ $project3d->location }}</b></li>@endif
            </ul>
            <a href="{{ $project3d->url }}" class="btn" data-reveal data-magnetic=".2">مشاهده پروژه <i class="ri-arrow-left-line"></i></a>
        </div>
        <div class="tower-stage" data-tower data-floors="{{ max(4, min(24, (int) preg_replace('/\D+/', '', en_num($project3d->floors ?? '')) ?: 12)) }}">
            <img class="tower-fallback" src="{{ media_url($project3d->cover) }}" alt="{{ $project3d->title }}" loading="lazy">
            <div class="tower-hint"><i class="ri-drag-move-2-line"></i> بکشید تا بچرخد</div>
            <button type="button" class="tower-toggle" data-tower-mode aria-label="حالت شب و روز"><i class="ri-moon-line"></i></button>
        </div>
    </div>
</section>
@endif
