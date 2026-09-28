@php
    $nav = [
        ['route' => 'home', 'label' => 'خانه', 'match' => 'home'],
        ['route' => 'about', 'label' => 'درباره ما', 'match' => 'about'],
        ['route' => 'services.index', 'label' => 'خدمات', 'match' => 'services.*', 'children' => $menuServices ?? collect()],
        ['route' => 'projects.index', 'label' => 'پروژه‌ها', 'match' => 'projects.*'],
        ['route' => 'blog.index', 'label' => 'مقالات', 'match' => 'blog.*'],
        ['route' => 'contact', 'label' => 'تماس با ما', 'match' => 'contact'],
    ];
    $logo = setting('logo');
@endphp

<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ setting('site_title') }}">
            @if ($logo)
                <img src="{{ media_url($logo) }}" alt="{{ setting('site_title') }}">
            @else
                <img class="brand__mark" src="{{ asset('assets/img/logo-mark.svg') }}" alt="">
                <span class="brand__text">
                    <span class="brand__name">{{ setting('site_title', 'طاهورنیان') }}</span>
                    <span class="brand__tag">{{ setting('site_tagline') }}</span>
                </span>
            @endif
        </a>

        <nav class="main-nav" aria-label="منوی اصلی">
            <ul>
                @foreach ($nav as $item)
                    <li class="{{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <a href="{{ route($item['route']) }}">
                            <span class="nav-roll"><span>{{ $item['label'] }}</span><span>{{ $item['label'] }}</span></span>
                            @if (! empty($item['children']) && $item['children']->count())
                                <i class="ri-arrow-down-s-line"></i>
                            @endif
                        </a>
                        @if (! empty($item['children']) && $item['children']->count())
                            <div class="dropdown">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ route('services.show', $child->slug) }}"><i class="{{ $child->icon ?: 'ri-checkbox-blank-circle-line' }}"></i>{{ $child->title }}</a>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="header-actions">
            @if (setting('phone'))
                <a href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('phone'))) }}" class="header-phone">
                    <span>{{ fa_num(setting('phone')) }}</span>
                    <i class="ri-phone-line"></i>
                </a>
            @endif
            <a href="{{ route('contact') }}" class="btn btn--sm" data-magnetic=".25"><span>مشاوره رایگان</span><i class="ri-arrow-left-up-line"></i></a>
            <button class="burger" id="burger" type="button" aria-label="منو" aria-expanded="false" aria-controls="menuOverlay">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<div class="menu-overlay" id="menuOverlay">
    <ul class="menu-overlay__links">
        @foreach ($nav as $i => $item)
            <li><a href="{{ route($item['route']) }}"><small>{{ fa_num(str_pad($i + 1, 2, '0', STR_PAD_LEFT)) }}</small>{{ $item['label'] }}</a></li>
        @endforeach
    </ul>
    <div class="menu-overlay__info">
        @if (setting('phone'))<a href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('phone'))) }}" class="ltr" style="text-align:right">{{ fa_num(setting('phone')) }}</a>@endif
        @if (setting('email'))<a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>@endif
        @include('partials.social')
    </div>
</div>
