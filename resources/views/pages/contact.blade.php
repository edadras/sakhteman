@extends('layouts.app')

@section('title', 'تماس با ما')

@section('content')
@include('partials.page-hero', ['title' => 'تماس با ما', 'eyebrow' => 'در ارتباط باشید', 'crumbs' => ['تماس با ما' => null]])

<section class="section">
    <div class="container contact-grid">
        <div>
            @include('partials.sec-head', ['title' => 'اطلاعات تماس', 'en' => 'Contact us', 'icon' => 'ri-customer-service-2-line'])
            <div class="contact-cards" data-stagger=".1">
                @if (setting('phone') || setting('mobile'))
                    <a class="contact-card" href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('phone') ?: setting('mobile'))) }}">
                        <i class="ri-phone-line"></i>
                        <div>
                            <small>تلفن تماس</small>
                            @if (setting('phone'))<strong class="ltr" style="text-align:right">{{ fa_num(setting('phone')) }}</strong>@endif
                            @if (setting('mobile'))<strong class="ltr" style="text-align:right">{{ fa_num(setting('mobile')) }}</strong>@endif
                        </div>
                    </a>
                @endif
                @if (setting('email'))
                    <a class="contact-card" href="mailto:{{ setting('email') }}">
                        <i class="ri-mail-send-line"></i>
                        <div><small>ایمیل</small><strong>{{ setting('email') }}</strong></div>
                    </a>
                @endif
                @if (setting('address'))
                    <div class="contact-card">
                        <i class="ri-map-pin-2-line"></i>
                        <div><small>آدرس دفتر مرکزی</small><strong>{{ setting('address') }}</strong></div>
                    </div>
                @endif
                @if (setting('working_hours'))
                    <div class="contact-card">
                        <i class="ri-time-line"></i>
                        <div><small>ساعات کاری</small><strong>{{ fa_num(setting('working_hours')) }}</strong></div>
                    </div>
                @endif
            </div>
            <div style="margin-top:30px" data-reveal>@include('partials.social', ['class' => 'social social--light'])</div>
        </div>

        <div class="form-box" data-reveal="left">
            <h3 style="font-size:26px">فرم درخواست مشاوره</h3>
            <p class="muted">فرم زیر را تکمیل کنید؛ کارشناسان ما در کوتاه‌ترین زمان با شما تماس می‌گیرند.</p>

            <div id="formAlert" class="alert {{ session('success') ? 'alert--success' : '' }}" @unless (session('success')) hidden @endunless>
                <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
            </div>

            <form id="contactForm" action="{{ route('contact.send') }}" method="POST" novalidate>
                @csrf
                <input class="hp-field" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                <div class="form-grid">
                    <div class="field">
                        <input type="text" id="f-name" name="name" value="{{ old('name') }}" placeholder=" " required>
                        <label for="f-name">نام و نام خانوادگی *</label>
                        @error('name')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <input type="tel" id="f-phone" name="phone" value="{{ old('phone') }}" placeholder=" " required dir="ltr" style="text-align:right">
                        <label for="f-phone">شماره تماس *</label>
                        @error('phone')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <input type="email" id="f-email" name="email" value="{{ old('email') }}" placeholder=" " dir="ltr" style="text-align:right">
                        <label for="f-email">ایمیل</label>
                        @error('email')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <input type="text" id="f-subject" name="subject" value="{{ old('subject') }}" placeholder=" ">
                        <label for="f-subject">موضوع</label>
                        @error('subject')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field full">
                        <textarea id="f-body" name="body" placeholder=" " required>{{ old('body') }}</textarea>
                        <label for="f-body">توضیحات پروژه یا پیام شما *</label>
                        @error('body')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="full">
                        <button type="submit" class="btn" data-magnetic=".15"><span>ارسال پیام</span><i class="ri-send-plane-line"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (setting('map_embed'))
        <div class="container">
            @php $map = setting('map_embed'); @endphp
            <div class="map-box" data-reveal>
                @if (str_contains($map, '<iframe'))
                    {!! $map !!}
                @else
                    <iframe src="{{ $map }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="نقشه"></iframe>
                @endif
            </div>
        </div>
    @endif
</section>

@include('partials.faq')
@endsection
