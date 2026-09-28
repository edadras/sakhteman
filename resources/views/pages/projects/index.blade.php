@extends('layouts.app')

@section('title', 'پروژه‌ها')

@section('content')
@include('partials.page-hero', ['title' => 'پروژه‌های ما', 'eyebrow' => 'نمونه کارها', 'crumbs' => ['پروژه‌ها' => null]])

<section class="section">
    <div class="container">
        @if ($categories->count() > 1)
            <div class="filter-bar" data-reveal>
                <button class="filter-btn is-active" type="button" data-filter="*">همه<sup>{{ fa_num($projects->count()) }}</sup></button>
                @foreach ($categories as $category)
                    <button class="filter-btn" type="button" data-filter="{{ $category->id }}">
                        {{ $category->name }}<sup>{{ fa_num($projects->where('category_id', $category->id)->count()) }}</sup>
                    </button>
                @endforeach
            </div>
        @endif

        @if ($projects->count())
            <div class="projects-grid" data-filterable>
                @foreach ($projects as $i => $project)
                    @include('partials.project-card', ['project' => $project])
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="ri-building-4-line"></i>هنوز پروژه‌ای ثبت نشده است.</div>
        @endif
    </div>
</section>

@include('partials.projects-map', ['projects' => $projects])
@include('partials.cta')
@endsection

@push('vendor_scripts')
    <script src="{{ asset('assets/vendor/gsap/Flip.min.js') }}"></script>
@endpush

@push('scripts')
<script>
    // نمایش تدریجی کارت‌ها
    if (window.gsap && window.ScrollTrigger && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
        gsap.set('.projects-grid .project-card', { y: 80, opacity: 0 });
        ScrollTrigger.batch('.projects-grid .project-card', {
            start: 'top 90%',
            onEnter: (els) => gsap.fromTo(els, { y: 80, opacity: 0 }, { y: 0, opacity: 1, duration: 1, ease: 'power3.out', stagger: .1, overwrite: true }),
        });
    }
</script>
@endpush
