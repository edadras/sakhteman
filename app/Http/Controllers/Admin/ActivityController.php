<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * گزارش فعالیت کاربران پنل
 */
class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user:id,name')
            ->when($request->query('user'), fn ($q, $u) => $q->where('user_id', $u))
            ->when($request->query('action'), fn ($q, $a) => $q->where('action', $a))
            ->when($request->query('q'), fn ($q, $t) => $q->where('subject', 'like', '%'.$t.'%'))
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.activity.index', [
            'logs' => $logs,
            'users' => User::where('is_admin', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
