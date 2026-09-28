<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $cards = [
            ['label' => 'پروژه‌ها', 'value' => Project::count(), 'icon' => 'ri-building-4-line', 'color' => 'amber', 'route' => route('admin.resources.index', 'projects')],
            ['label' => 'خدمات', 'value' => Service::count(), 'icon' => 'ri-tools-line', 'color' => 'blue', 'route' => route('admin.resources.index', 'services')],
            ['label' => 'مقالات', 'value' => Post::count(), 'icon' => 'ri-article-line', 'color' => 'green', 'route' => route('admin.resources.index', 'posts')],
            ['label' => 'پیام‌های جدید', 'value' => Message::where('is_read', false)->count(), 'icon' => 'ri-mail-unread-line', 'color' => 'rose', 'route' => route('admin.messages.index')],
        ];

        // نمودار پیام‌های ۱۴ روز اخیر
        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());
        $counts = Message::where('created_at', '>=', $days->first())
            ->get(['created_at'])
            ->groupBy(fn ($m) => $m->created_at->toDateString())
            ->map->count();

        $chart = [
            'labels' => $days->map(fn ($d) => jdate($d, 'j F'))->values(),
            'data' => $days->map(fn ($d) => $counts[$d->toDateString()] ?? 0)->values(),
        ];

        return view('admin.dashboard', [
            'cards' => $cards,
            'chart' => $chart,
            'latestMessages' => Message::latest()->take(6)->get(),
            'latestProjects' => Project::with('category')->latest()->take(5)->get(),
            'popularPosts' => Post::orderByDesc('views')->take(5)->get(),
            'extra' => [
                'team' => TeamMember::count(),
                'testimonials' => Testimonial::count(),
                'messages' => Message::count(),
            ],
        ]);
    }
}
