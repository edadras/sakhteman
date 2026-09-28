@if ($faqs->count())
<section class="section {{ $class ?? '' }}">
    <div class="container faq-grid">
        <div>
            @include('partials.sec-head', ['title' => 'سوالات متداول', 'en' => 'FAQ', 'icon' => 'ri-question-answer-line'])
            <p class="muted" data-reveal style="margin-top:-20px">اگر پاسخ سوال خود را پیدا نکردید، کارشناسان ما آماده پاسخگویی هستند.</p>
            <a href="{{ route('contact') }}" class="btn" data-reveal data-magnetic=".2">ارتباط با کارشناس <i class="ri-arrow-left-line"></i></a>
        </div>
        <div class="accordion" data-stagger=".1">
            @foreach ($faqs as $faq)
                <div class="accordion__item">
                    <button class="accordion__head" type="button" aria-expanded="false"><span>{{ $faq->question }}</span><i class="ri-add-line"></i></button>
                    <div class="accordion__body"><div>{!! nl2br(e($faq->answer)) !!}</div></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
