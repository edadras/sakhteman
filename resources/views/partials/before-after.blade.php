{{-- اسلایدر مقایسه قبل و بعد: $before, $after, $alt --}}
<div class="ba" data-ba style="--pos: 50%">
    <img class="ba__after" src="{{ $after }}" alt="{{ $alt ?? '' }} — بعد از اجرا" loading="lazy" draggable="false">
    <div class="ba__before"><img src="{{ $before }}" alt="{{ $alt ?? '' }} — قبل از اجرا" loading="lazy" draggable="false"></div>
    <span class="ba__label ba__label--before">{{ $beforeLabel ?? 'قبل / نقشه' }}</span>
    <span class="ba__label ba__label--after">{{ $afterLabel ?? 'بعد از اجرا' }}</span>
    <div class="ba__handle" role="slider" tabindex="0" aria-label="مقایسه قبل و بعد" aria-valuemin="0" aria-valuemax="100" aria-valuenow="50">
        <span class="ba__knob"><i class="ri-arrow-left-right-line"></i></span>
    </div>
</div>
