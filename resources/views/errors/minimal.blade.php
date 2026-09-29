<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title') | طهورنیان</title>
    <link rel="stylesheet" href="/assets/fonts/vazirmatn/vazirmatn.css">
    <link rel="stylesheet" href="/assets/vendor/remixicon/remixicon.css">
    <style>
        :root { color-scheme: dark; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px 16px; font-family: Vazirmatn, Tahoma, sans-serif; background: #0e1e21; color: #fff; background-image: radial-gradient(circle at 20% 20%, rgba(79, 154, 83, .18), transparent 45%), radial-gradient(circle at 85% 80%, rgba(251, 177, 46, .12), transparent 40%); }
        .box { max-width: 520px; text-align: center; }
        .logo { width: 64px; height: 64px; margin: 0 auto 18px; display: block; }
        .code { font-size: clamp(64px, 16vw, 120px); font-weight: 900; line-height: 1; background: linear-gradient(135deg, #6cc070, #fbb12e); -webkit-background-clip: text; background-clip: text; color: transparent; }
        h1 { font-size: clamp(20px, 5vw, 28px); margin: 12px 0 8px; }
        p { color: rgba(255, 255, 255, .65); line-height: 1.9; margin: 0; }
        .actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 26px; }
        a { display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 14px; font-weight: 700; text-decoration: none; background: #fbb12e; color: #0e1e21; }
        a.ghost { background: rgba(255, 255, 255, .08); color: #fff; }
    </style>
</head>
<body>
    <div class="box">
        <img class="logo" src="/assets/img/logo-mark.svg" alt="">
        <div class="code">@yield('code')</div>
        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>
        <div class="actions">
            @hasSection('actions')
                @yield('actions')
            @else
                <a href="javascript:history.back()" class="ghost"><i class="ri-arrow-right-line"></i>صفحه قبل</a>
                <a href="/"><i class="ri-home-5-line"></i>صفحه اصلی</a>
            @endif
        </div>
    </div>
</body>
</html>
