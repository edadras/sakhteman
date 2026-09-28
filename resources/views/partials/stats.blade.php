@if ($stats->count())
<section class="section section--tight stats">
    <div class="container">
        <div class="stats-grid" data-stagger=".12">
            @foreach ($stats as $stat)
                <div class="stat">
                    @if ($stat->icon)<i class="stat__icon {{ $stat->icon }}"></i>@endif
                    <div class="stat__value"><span data-counter="{{ $stat->value }}">{{ fa_num($stat->value) }}</span><span class="suffix">{{ $stat->suffix }}</span></div>
                    <div class="stat__label">{{ $stat->title }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
