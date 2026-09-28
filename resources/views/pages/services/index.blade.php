@extends('layouts.app')

@section('title', 'خدمات')

@section('content')
@include('partials.page-hero', ['title' => 'خدمات ما', 'eyebrow' => 'آنچه ارائه می‌دهیم', 'crumbs' => ['خدمات' => null]])

<section class="section">
    <div class="container">
        <div class="section-head section-head--center">
            <div class="section-head__text">
                <span class="eyebrow">خدمات تخصصی</span>
                <h2 class="section-title" data-split>هر آنچه برای <em>ساختن</em> نیاز دارید</h2>
                <p data-reveal>از طراحی مفهومی تا اجرای کامل و خدمات پس از تحویل، همه را زیر یک سقف ارائه می‌دهیم.</p>
            </div>
        </div>
        @if ($services->count())
            <div class="services-grid" data-stagger=".1">
                @foreach ($services as $i => $service)
                    @include('partials.service-card', ['service' => $service, 'index' => $i])
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="ri-tools-line"></i>هنوز خدمتی ثبت نشده است.</div>
        @endif
    </div>
</section>

@include('partials.process', ['class' => 'section--dark2'])
@include('partials.faq')
@include('partials.cta')
@endsection
