@php
    $mapped = $projects->filter(fn ($p) => $p->lat && $p->lng);
@endphp
@if ($mapped->count())
<section class="section map-section" style="padding-top:0">
    <div class="container">
        @include('partials.sec-head', ['title' => 'نقشه پروژه‌ها', 'en' => 'Projects map', 'icon' => 'ri-map-pin-2-line', 'desc' => 'روی هر نقطه بزنید تا پروژه‌های آن منطقه را ببینید.'])
        <div class="pmap" data-pmap data-reveal="scale">
            <svg class="pmap__svg" viewBox="0 0 {{ \App\Support\IranMap::WIDTH }} {{ \App\Support\IranMap::HEIGHT }}" role="img" aria-label="نقشه پروژه‌ها در ایران">
                <defs>
                    <pattern id="pmapGrid" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.6" fill="rgba(255,255,255,.14)"/></pattern>
                    <clipPath id="pmapClip"><path d="{{ \App\Support\IranMap::PATH }}"/></clipPath>
                </defs>
                <path class="pmap__land" d="{{ \App\Support\IranMap::PATH }}"/>
                <rect width="100%" height="100%" fill="url(#pmapGrid)" clip-path="url(#pmapClip)"/>
                <path class="pmap__outline" d="{{ \App\Support\IranMap::PATH }}"/>
                @foreach ($mapped as $p)
                    @php [$x, $y] = \App\Support\IranMap::project((float) $p->lat, (float) $p->lng); @endphp
                    <g class="pmap__dot" transform="translate({{ $x }} {{ $y }})" data-project="{{ $p->id }}" tabindex="0" role="button" aria-label="{{ $p->title }}">
                        <circle class="pmap__pulse" r="10"/>
                        <circle class="pmap__core" r="7"/>
                    </g>
                @endforeach
            </svg>
            <div class="pmap__list">
                @foreach ($mapped as $p)
                    <a href="{{ $p->url }}" class="pmap__card" data-card="{{ $p->id }}" hidden>
                        <img src="{{ media_url($p->cover) }}" alt="" loading="lazy">
                        <div>
                            <small>{{ $p->location }}</small>
                            <strong>{{ $p->title }}</strong>
                            <span class="badge {{ $p->status === 'completed' ? 'badge--green' : 'badge--amber' }}">{{ $p->status_label }}</span>
                        </div>
                        <i class="ri-arrow-left-up-line"></i>
                    </a>
                @endforeach
                <div class="pmap__hint" data-hint><i class="ri-cursor-line"></i> یک نقطه روی نقشه را انتخاب کنید</div>
                <div class="pmap__legend">
                    <span><b>{{ fa_num($mapped->count()) }}</b> پروژه</span>
                    <span><b>{{ fa_num($mapped->pluck('location')->map(fn ($l) => trim(explode('،', (string) $l)[0]))->unique()->count()) }}</b> شهر</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
