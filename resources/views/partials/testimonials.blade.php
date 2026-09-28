@if ($testimonials->count())
<section class="section">
    <div class="container testimonials-wrap">
        <div>
            @include('partials.sec-head', ['title' => 'رضایت مشتریان', 'en' => 'Testimonials', 'icon' => 'ri-chat-quote-line'])
            <p class="muted" data-reveal style="margin-top:-24px">اعتماد کارفرمایان، بزرگ‌ترین سرمایه ماست و رضایت آن‌ها معیار موفقیت هر پروژه.</p>
            <div class="nav-btns" data-reveal>
                <button class="t-prev" type="button" aria-label="قبلی"><i class="ri-arrow-right-line"></i></button>
                <button class="t-next" type="button" aria-label="بعدی"><i class="ri-arrow-left-line"></i></button>
            </div>
        </div>
        <div class="swiper testimonials-swiper" data-reveal="left">
            <div class="swiper-wrapper">
                @foreach ($testimonials as $t)
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <i class="ri-double-quotes-r testimonial-card__quote"></i>
                            <div class="testimonial-card__stars">{{ str_repeat('★', max(1, min(5, $t->rating))) }}</div>
                            <p class="testimonial-card__text">{{ $t->content }}</p>
                            <div class="testimonial-card__author">
                                <img src="{{ media_url($t->avatar) }}" alt="{{ $t->name }}" loading="lazy">
                                <div><strong>{{ $t->name }}</strong><span>{{ $t->position }}</span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
