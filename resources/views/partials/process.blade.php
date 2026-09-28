@if ($steps->count())
<section class="section process {{ $class ?? '' }}">
    <div class="container">
        <div class="section-head section-head--center">
            <div class="section-head__text">
                <span class="eyebrow">فرایند کار ما</span>
                <h2 class="section-title" data-split>از <em>ایده</em> تا تحویل کلید</h2>
                <p data-reveal>مسیری شفاف و حساب‌شده که در هر مرحله، شما را در جریان پیشرفت پروژه قرار می‌دهد.</p>
            </div>
        </div>
        <div class="process-grid">
            <div class="process-line"><svg preserveAspectRatio="none" viewBox="0 0 100 2"><path d="M0 1 H100" vector-effect="non-scaling-stroke"/></svg></div>
            @foreach ($steps as $i => $step)
                <div class="process-step" data-reveal data-delay="{{ $i * .15 }}">
                    <div class="process-step__icon"><i class="{{ $step->icon ?: 'ri-checkbox-circle-line' }}"></i><b>{{ fa_num($i + 1) }}</b></div>
                    <h3>{{ $step->title }}</h3>
                    <p>{{ $step->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
