<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->when($request->query('q'), function ($q, $t) {
                $t = en_num($t);
                $q->where(fn ($q) => $q->where('name', 'like', "%$t%")->orWhere('mobile', 'like', "%$t%")->orWhere('email', 'like', "%$t%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function destroy(User $user)
    {
        abort_if($user->is_admin, 403);
        $user->delete();

        return back()->with('success', 'کاربر حذف شد.');
    }
}
