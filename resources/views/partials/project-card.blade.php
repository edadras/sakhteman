<a href="{{ $project->url }}" class="project-card {{ $class ?? '' }}" data-category="{{ $project->category_id }}" data-cursor="مشاهده">
    <div class="project-card__media">
        <img src="{{ media_url($project->cover) }}" alt="{{ $project->title }}" loading="lazy">
    </div>
    @if ($project->category)
        <span class="project-card__cat">{{ $project->category->name }}</span>
    @endif
    <div class="project-card__body">
        <div>
            <h3 class="project-card__title">{{ $project->title }}</h3>
            <div class="project-card__meta">
                @if ($project->location)<span><i class="ri-map-pin-line"></i>{{ $project->location }}</span>@endif
                @if ($project->year)<span><i class="ri-calendar-line"></i>{{ fa_num($project->year) }}</span>@endif
            </div>
        </div>
        <span class="project-card__arrow"><i class="ri-arrow-left-up-line"></i></span>
    </div>
</a>
