@php
    $badge = ['completed' => 'badge--green', 'in_progress' => 'badge--amber'][$project->status] ?? 'badge--gray';
@endphp
<a href="{{ $project->url }}" class="project-card {{ $class ?? '' }}" data-category="{{ $project->category_id }}">
    <div class="card-top">
        <div class="project-card__media">
            <img src="{{ media_url($project->cover) }}" alt="{{ $project->title }}" loading="lazy">
            @if ($project->video)
                <video class="card-video" data-src="{{ media_url($project->video) }}" muted loop playsinline preload="none" aria-hidden="true"></video>
                <span class="card-video__badge"><i class="ri-play-fill"></i></span>
            @endif
        </div>
        <span class="badge {{ $badge }} card-top__badge">{{ $project->status_label }}</span>
    </div>
    <div class="project-card__body">
        <h3 class="project-card__title">{{ $project->title }}</h3>
        @if ($project->location)<span class="project-card__loc"><i class="ri-map-pin-2-line"></i>{{ $project->location }}</span>@endif
    </div>
    <div class="project-card__stats">
        <div><small>تعداد طبقات</small><strong>{{ fa_num($project->floors ?: '—') }}</strong></div>
        <div><small>تعداد واحدها</small><strong>{{ fa_num($project->units ?: ($project->area ?: '—')) }}</strong></div>
    </div>
    <div class="project-card__foot"><span class="tab-shape"><span>مشاهده پروژه</span></span></div>
</a>
