@if ($team->count())
<section class="team-section grid-bg" data-grid-spot>
    <div class="container team-layout">
        <div class="team-text">
            @include('partials.sec-head', ['title' => 'تیم ما', 'en' => 'Our Team', 'icon' => 'ri-team-line'])
            <p data-reveal>{{ setting('team_text', 'تیمی از معماران، مهندسان و مدیران پروژه با سال‌ها تجربه.') }}</p>
            @if (setting('team_button_text'))
                <a href="{{ setting('team_button_link', route('contact')) }}" class="btn" data-reveal data-magnetic=".2">{{ setting('team_button_text') }}</a>
            @endif
        </div>
        <div class="team-collage">
            @foreach ($team->take(8) as $member)
                <figure class="team-photo" data-parallax-y="{{ [0.12, 0.25, 0.05, 0.18, 0.3, 0.1, 0.22, 0.08][$loop->index] }}">
                    <img src="{{ media_url($member->photo) }}" alt="{{ $member->name }}" loading="lazy">
                    <figcaption><strong>{{ $member->name }}</strong><span>{{ $member->position }}</span></figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif
