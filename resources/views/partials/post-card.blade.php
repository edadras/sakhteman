<a href="{{ $post->url }}" class="post-card">
    <div class="post-card__media">
        <img src="{{ media_url($post->cover) }}" alt="{{ $post->title }}" loading="lazy">
        @if ($post->published_at)
            <span class="post-card__date"><b>{{ jdate($post->published_at, 'j') }}</b><small>{{ jdate($post->published_at, 'F') }}</small></span>
        @endif
    </div>
    <div class="post-card__body">
        @if ($post->category)<span class="post-card__cat">{{ $post->category }}</span>@endif
        <h3 class="post-card__title">{{ $post->title }}</h3>
        <p class="post-card__excerpt">{{ $post->excerpt }}</p>
        <div class="post-card__foot">
            <span><i class="ri-time-line"></i> {{ fa_num(reading_time($post->body)) }} دقیقه مطالعه</span>
            <span class="link-arrow">ادامه <i class="ri-arrow-left-line"></i></span>
        </div>
    </div>
</a>
