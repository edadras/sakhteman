<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Message;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\Slide;
use App\Models\Stat;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $featured = Project::active()->with('category')->where('is_featured', true)->ordered()->take(8)->get();
        if ($featured->isEmpty()) {
            $featured = Project::active()->with('category')->ordered()->take(8)->get();
        }

        return view('pages.home', [
            'slides' => Slide::active()->ordered()->get(),
            'services' => Service::active()->ordered()->take(6)->get(),
            'projects' => $featured,
            'stats' => Stat::active()->ordered()->get(),
            'steps' => Step::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'team' => TeamMember::active()->ordered()->take(4)->get(),
            'posts' => Post::active()->latestFirst()->take(3)->get(),
            'partners' => Partner::active()->ordered()->get(),
            'faqs' => Faq::active()->ordered()->take(5)->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'stats' => Stat::active()->ordered()->get(),
            'team' => TeamMember::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'steps' => Step::active()->ordered()->get(),
            'faqs' => Faq::active()->ordered()->get(),
            'partners' => Partner::active()->ordered()->get(),
        ]);
    }

    public function services()
    {
        return view('pages.services.index', [
            'services' => Service::active()->ordered()->get(),
            'steps' => Step::active()->ordered()->get(),
            'faqs' => Faq::active()->ordered()->take(6)->get(),
        ]);
    }

    public function service(string $slug)
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();

        return view('pages.services.show', [
            'service' => $service,
            'others' => Service::active()->ordered()->get(),
            'projects' => Project::active()->ordered()->take(3)->get(),
        ]);
    }

    public function projects(Request $request)
    {
        return view('pages.projects.index', [
            'categories' => ProjectCategory::active()->ordered()->has('projects')->get(),
            'projects' => Project::active()->with('category')->ordered()->get(),
        ]);
    }

    public function project(string $slug)
    {
        $project = Project::active()->with('category')->where('slug', $slug)->firstOrFail();

        $ordered = Project::active()->ordered()->get(['id', 'title', 'slug', 'cover']);
        $index = $ordered->search(fn ($p) => $p->id === $project->id);

        return view('pages.projects.show', [
            'project' => $project,
            'prev' => $index > 0 ? $ordered[$index - 1] : null,
            'next' => $ordered[$index + 1] ?? null,
            'related' => Project::active()->with('category')
                ->where('id', '!=', $project->id)
                ->when($project->category_id, fn ($q) => $q->orderByRaw('category_id = ? desc', [$project->category_id]))
                ->ordered()->take(3)->get(),
        ]);
    }

    public function blog(Request $request)
    {
        $posts = Post::active()
            ->when($request->query('category'), fn ($q, $c) => $q->where('category', $c))
            ->when($request->query('q'), fn ($q, $t) => $q->where(fn ($q) => $q->where('title', 'like', "%$t%")->orWhere('excerpt', 'like', "%$t%")))
            ->latestFirst()
            ->paginate(9)
            ->withQueryString();

        return view('pages.blog.index', [
            'posts' => $posts,
            'categories' => Post::active()->whereNotNull('category')->distinct()->pluck('category'),
        ]);
    }

    public function post(string $slug)
    {
        $post = Post::active()->where('slug', $slug)->firstOrFail();
        $post->increment('views');

        return view('pages.blog.show', [
            'post' => $post,
            'recent' => Post::active()->where('id', '!=', $post->id)->latestFirst()->take(4)->get(),
            'categories' => Post::active()->whereNotNull('category')->distinct()->pluck('category'),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'faqs' => Faq::active()->ordered()->take(5)->get(),
        ]);
    }

    public function sendMessage(Request $request)
    {
        // فیلد مخفی ضد ربات
        if ($request->filled('website')) {
            return back()->with('success', 'پیام شما با موفقیت ارسال شد.');
        }

        $request->merge(['phone' => en_num($request->input('phone'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:190'],
            'subject' => ['nullable', 'string', 'max:190'],
            'body' => ['required', 'string', 'min:5', 'max:5000'],
        ], [], [
            'name' => 'نام',
            'phone' => 'شماره تماس',
            'email' => 'ایمیل',
            'subject' => 'موضوع',
            'body' => 'متن پیام',
        ]);

        Message::create($data + ['ip' => $request->ip()]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'پیام شما با موفقیت ارسال شد. به‌زودی با شما تماس می‌گیریم.']);
        }

        return back()->with('success', 'پیام شما با موفقیت ارسال شد. به‌زودی با شما تماس می‌گیریم.');
    }

    public function sitemap()
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('about'), 'priority' => '0.8'],
            ['loc' => route('services.index'), 'priority' => '0.8'],
            ['loc' => route('projects.index'), 'priority' => '0.9'],
            ['loc' => route('blog.index'), 'priority' => '0.7'],
            ['loc' => route('contact'), 'priority' => '0.6'],
        ]);

        Service::active()->get()->each(fn ($s) => $urls->push(['loc' => $s->url, 'lastmod' => $s->updated_at, 'priority' => '0.7']));
        Project::active()->get()->each(fn ($p) => $urls->push(['loc' => $p->url, 'lastmod' => $p->updated_at, 'priority' => '0.8']));
        Post::active()->get()->each(fn ($p) => $urls->push(['loc' => $p->url, 'lastmod' => $p->updated_at, 'priority' => '0.6']));

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }
}
