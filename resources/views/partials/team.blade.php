@if ($team->count())
<section class="section {{ $class ?? '' }}">
    <div class="container">
        <div class="section-head">
            <div class="section-head__text">
                <span class="eyebrow">تیم ما</span>
                <h2 class="section-title" data-split>متخصصانی که <em>می‌سازند</em></h2>
            </div>
            <p class="muted" data-reveal style="max-width:420px">تیمی از معماران، مهندسان و مدیران پروژه با سال‌ها تجربه در اجرای پروژه‌های شاخص.</p>
        </div>
        <div class="team-grid" data-stagger=".12">
            @foreach ($team as $member)
                <div class="team-card">
                    <div class="team-card__photo"><img src="{{ media_url($member->photo) }}" alt="{{ $member->name }}" loading="lazy"></div>
                    <div class="team-card__info">
                        <strong>{{ $member->name }}</strong>
                        <span>{{ $member->position }}</span>
                        <div class="team-card__social">
                            @if ($member->instagram)<a href="{{ $member->instagram }}" target="_blank" rel="noopener" aria-label="اینستاگرام"><i class="ri-instagram-line"></i></a>@endif
                            @if ($member->linkedin)<a href="{{ $member->linkedin }}" target="_blank" rel="noopener" aria-label="لینکدین"><i class="ri-linkedin-line"></i></a>@endif
                            @if ($member->email)<a href="mailto:{{ $member->email }}" aria-label="ایمیل"><i class="ri-mail-line"></i></a>@endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
