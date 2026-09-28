{{-- ساختمانی که با اسکرول، مرحله به مرحله ساخته می‌شود --}}
@php $floors = 7; $h = 50; $base = 500; @endphp
<svg class="bld" viewBox="0 0 420 570" role="img" aria-label="مراحل ساخت ساختمان" data-building>
    {{-- زمین و درختان --}}
    <line class="bld-ground draw" x1="10" y1="525" x2="410" y2="525"/>
    <g class="bld-tree"><circle cx="45" cy="500" r="22"/><circle cx="65" cy="508" r="16"/><line x1="52" y1="512" x2="52" y2="525"/></g>
    <g class="bld-tree"><circle cx="378" cy="502" r="20"/><circle cx="360" cy="510" r="14"/><line x1="372" y1="512" x2="372" y2="525"/></g>

    {{-- مرحله ۱: طرح اولیه --}}
    <rect class="bld-sketch draw" x="110" y="{{ $base - $floors * $h }}" width="200" height="{{ $floors * $h + 25 }}" rx="4"/>
    <path class="bld-sketch draw" d="M110 {{ $base - $floors * $h }} L210 {{ $base - $floors * $h - 40 }} L310 {{ $base - $floors * $h }}"/>

    {{-- مرحله ۲: ابعاد و نقشه --}}
    <g class="bld-dim">
        <line class="draw" x1="86" y1="{{ $base - $floors * $h }}" x2="86" y2="525"/>
        <line class="draw" x1="80" y1="{{ $base - $floors * $h }}" x2="92" y2="{{ $base - $floors * $h }}"/>
        <line class="draw" x1="80" y1="525" x2="92" y2="525"/>
        <text x="74" y="{{ ($base - $floors * $h + 525) / 2 }}" transform="rotate(-90 74 {{ ($base - $floors * $h + 525) / 2 }})">{{ fa_num($floors * 3) }} متر</text>
        <line class="draw" x1="110" y1="548" x2="310" y2="548"/>
        <line class="draw" x1="110" y1="542" x2="110" y2="554"/>
        <line class="draw" x1="310" y1="542" x2="310" y2="554"/>
        <text x="210" y="566" text-anchor="middle">{{ fa_num(18) }} متر</text>
    </g>

    {{-- مرحله ۳: فونداسیون --}}
    <g class="bld-found">
        <rect x="98" y="{{ $base }}" width="224" height="25" rx="3"/>
        @foreach ([120, 165, 210, 255, 300] as $x)<line x1="{{ $x }}" y1="525" x2="{{ $x }}" y2="545"/>@endforeach
    </g>

    {{-- مرحله ۴: اسکلت و سقف‌ها --}}
    @foreach ([116, 163, 210, 257, 304] as $x)
        <line class="bld-col draw" x1="{{ $x }}" y1="{{ $base }}" x2="{{ $x }}" y2="{{ $base - $floors * $h }}"/>
    @endforeach
    @for ($i = 1; $i <= $floors; $i++)
        <line class="bld-slab draw" x1="106" y1="{{ $base - $i * $h }}" x2="314" y2="{{ $base - $i * $h }}"/>
    @endfor
    <g class="bld-crane">
        <line class="draw" x1="352" y1="525" x2="352" y2="120"/>
        <line class="draw" x1="352" y1="128" x2="210" y2="128"/>
        <line class="draw" x1="352" y1="128" x2="398" y2="128"/>
        <line class="draw" x1="352" y1="100" x2="240" y2="128"/>
        <line class="draw" x1="352" y1="100" x2="398" y2="128"/>
        <line class="draw bld-hook" x1="262" y1="128" x2="262" y2="175"/>
        <rect x="390" y="128" width="14" height="14" class="bld-weight"/>
    </g>

    {{-- مرحله ۵: نما، پنجره‌ها و تحویل --}}
    @for ($i = 0; $i < $floors; $i++)
        <g class="bld-floor" data-floor="{{ $i }}">
            <rect class="bld-panel" x="116" y="{{ $base - ($i + 1) * $h + 3 }}" width="188" height="{{ $h - 6 }}"/>
            @foreach ([124, 171, 218, 265] as $wx)
                <rect class="bld-win" x="{{ $wx }}" y="{{ $base - ($i + 1) * $h + 10 }}" width="31" height="{{ $h - 20 }}" rx="2"/>
            @endforeach
        </g>
    @endfor
    <rect class="bld-roof" x="104" y="{{ $base - $floors * $h - 10 }}" width="212" height="12" rx="3"/>
    <g class="bld-door"><rect x="190" y="{{ $base - 38 }}" width="40" height="38" rx="3"/></g>
    <g class="bld-flag"><line x1="210" y1="{{ $base - $floors * $h - 10 }}" x2="210" y2="{{ $base - $floors * $h - 55 }}"/><path d="M210 {{ $base - $floors * $h - 55 }} L240 {{ $base - $floors * $h - 47 }} L210 {{ $base - $floors * $h - 39 }}Z"/></g>
</svg>
