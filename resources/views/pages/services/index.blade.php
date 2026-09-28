@extends('layouts.app')

@section('title', 'خدمات')

@section('content')
@include('partials.page-hero', ['title' => 'خدمات ما', 'eyebrow' => 'آنچه ارائه می‌دهیم', 'crumbs' => ['خدمات' => null]])

<section class="section">
    <div class="container">
        @include('partials.sec-head', ['title' => 'خدمات تخصصی', 'en' => 'Our services', 'icon' => 'ri-building-3-line', 'desc' => 'از طراحی مفهومی تا اجرای کامل و خدمات پس از تحویل، همه را زیر یک سقف ارائه می‌دهیم.'])
        @if ($services->count())
            <div class="services-grid" data-stagger=".1">
                @foreach ($services as $service)
                    @include('partials.service-card', ['service' => $service])
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="ri-tools-line"></i>هنوز خدمتی ثبت نشده است.</div>
        @endif
    </div>
</section>

@include('partials.timeline')
@include('partials.calculator')
@include('partials.faq')
@include('partials.cta')
@endsection
