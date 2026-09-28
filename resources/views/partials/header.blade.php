@php
    $shopOn = setting('shop_enabled', '1') === '1';
    $nav = array_values(array_filter([
        ['route' => 'home', 'label' => 'خانه', 'match' => 'home'],
        ['route' => 'about', 'label' => 'درباره ما', 'match' => 'about'],
        ['route' => 'services.index', 'label' => 'خدمات', 'match' => 'services.*', 'children' => $menuServices ?? collect()],
        ['route' => 'projects.index', 'label' => 'پروژه‌ها', 'match' => 'projects.*'],
        $shopOn ? ['route' => 'shop.index', 'label' => 'فروشگاه', 'match' => 'shop.*'] : null,
        ['route' => 'blog.index', 'label' => 'مقالات', 'match' => 'blog.*'],
        ['route' => 'contact', 'label' => 'تماس با ما', 'match' => 'contact'],
    ]));
    $logo = setting('logo');
    $cartCount = $shopOn ? \App\Support\Cart::count() : 0;
@endphp

<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ setting('site_title') }}">
            @if ($logo)
                <img src="{{ media_url($logo) }}" alt="{{ setting('site_title') }}">
            @else
                <img class="brand__mark" src="{{ asset('assets/img/logo-mark.svg') }}" alt="">
                <span class="brand__text">
                    <span class="brand__name">{{ \Illuminate\Support\Str::of(setting('site_title', 'طاهورنیان'))->explode(' ')->last() }}</span>
                    <span class="brand__tag">{{ setting('site_tagline') }}</span>
                </span>
            @endif
        </a>

        <nav class="main-nav" aria-label="منوی اصلی">
            <ul>
                @foreach ($nav as $item)
                    @php $hasChildren = ! empty($item['children']) && $item['children']->count(); @endphp
                    <li class="{{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <a href="{{ route($item['route']) }}">{{ $item['label'] }} @if ($hasChildren)<i class="ri-arrow-down-s-line"></i>@endif</a>
                        @if ($hasChildren)
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
            <div class="auth-pill">
                @auth
                    <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('account') }}">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 16) }}</a>
                    <i class="ri-user-3-line"></i>
                @else
                    <a href="{{ route('login') }}">ورود</a>
                    <span class="sep"></span>
                    <a href="{{ route('register') }}" class="auth-pill__reg">عضویت</a>
                    <i class="ri-user-3-line"></i>
                @endauth
            </div>
            @if ($shopOn)
                <a href="{{ route('cart.index') }}" class="icon-circle" id="cartIcon" aria-label="سبد خرید">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span class="cart-count" data-count="{{ $cartCount }}">{{ fa_num($cartCount) }}</span>
                </a>
            @endif
            <button class="burger" id="burger" type="button" aria-label="منو" aria-expanded="false" aria-controls="menuOverlay">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<div class="menu-overlay grid-bg" id="menuOverlay">
    <div class="container">
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
</div>
