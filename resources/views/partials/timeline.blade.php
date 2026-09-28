@if ($steps->count())
<section class="section timeline-section" data-timeline-section>
    <div class="container">
        @include('partials.sec-head', ['title' => 'تایم‌لاین پروژه', 'en' => 'Project timeline', 'icon' => 'ri-route-line', 'desc' => 'از اولین جلسه مشاوره تا تحویل کلید؛ اسکرول کنید و ساخته شدن ساختمان را ببینید.'])
        <div class="timeline-layout">
            <div class="timeline">
                <div class="timeline__list">
                    @foreach ($steps as $i => $step)
                        <div class="tl-step {{ $loop->first ? 'is-open' : '' }}" data-tl>
                            <div class="tl-step__img"><img src="{{ media_url($step->image, asset('assets/img/demo/about-1.jpg')) }}" alt="" loading="lazy"></div>
                            <div class="tl-step__box">
                                <h3><b>{{ fa_num(str_pad($i + 1, 2, '0', STR_PAD_LEFT)) }}</b>{{ $step->title }}</h3>
                                @if ($step->description)<p>{{ $step->description }}</p>@endif
                            </div>
                        </div>
                        @unless ($loop->last)
                            <div class="tl-sep"><span class="tl-arrow"><i class="ri-arrow-down-line"></i></span></div>
                        @endunless
                    @endforeach
                </div>
            </div>
            <div class="building-wrap">
                <div class="building-card">
                    @include('partials.building-svg')
                    <div class="building-card__stage"><span data-stage-num>{{ fa_num(1) }}</span><strong data-stage-title>{{ $steps->first()->title }}</strong></div>
                    <div class="building-card__bar"><span data-stage-bar></span></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
