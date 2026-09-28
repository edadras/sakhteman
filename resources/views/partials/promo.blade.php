<section class="section">
    <div class="container promo-grid" data-stagger=".15">
        <div class="promo-card">
            <div class="promo-card__media"><img src="{{ media_url(setting('catalogue_cover'), asset('assets/img/demo/p5.jpg')) }}" alt="" loading="lazy"></div>
            <div class="promo-card__body">
                <span class="hex"><i class="ri-file-list-3-fill"></i></span>
                <h3>{{ setting('catalogue_title', 'کاتالوگ پروژه ها') }}</h3>
                <small>{{ setting('catalogue_subtitle', 'Projects catalogue') }}</small>
                <div class="promo-card__actions">
                    @if (setting('catalogue_file'))
                        <a href="{{ media_url(setting('catalogue_file')) }}" class="btn btn--green" download data-no-transition>دانلود <i class="ri-download-2-line"></i></a>
                        <a href="{{ media_url(setting('catalogue_file')) }}" class="btn btn--light" target="_blank">مشاهده</a>
                    @else
                        <a href="{{ route('projects.index') }}" class="btn btn--green">نمونه پروژه‌ها <i class="ri-building-2-line"></i></a>
                        <a href="{{ route('contact') }}" class="btn btn--light">درخواست کاتالوگ</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="promo-card">
            <div class="promo-card__media"><img src="{{ media_url(setting('consult_image'), asset('assets/img/demo/about-2.jpg')) }}" alt="" loading="lazy"></div>
            <div class="promo-card__body">
                <span class="hex hex--amber"><i class="ri-question-answer-fill"></i></span>
                <h3>{{ setting('consult_title', 'درخواست مشاوره') }}</h3>
                <small>{{ setting('consult_subtitle', 'Request for advice') }}</small>
                <div class="promo-card__actions">
                    <a href="{{ route('contact') }}" class="btn">درخواست مشاوره</a>
                    @if (setting('phone'))
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('phone'))) }}" class="btn btn--light">تماس با ما</a>
                    @else
                        <a href="{{ route('contact') }}" class="btn btn--light">تماس با ما</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
