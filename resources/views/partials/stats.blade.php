@if ($stats->count())
<div class="stats-wrap {{ $class ?? '' }}">
    <div class="container">
        <div class="stats-grid" data-stagger=".1">
            @foreach ($stats as $stat)
                <div class="stat">
                    <span class="stat__icon"><i class="{{ $stat->icon ?: 'ri-award-line' }}"></i></span>
                    <div>
                        <div class="stat__value"><span data-counter="{{ $stat->value }}">{{ fa_num($stat->value) }}</span><span class="text-amber">{{ $stat->suffix }}</span></div>
                        <div class="stat__label">{{ $stat->title }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
