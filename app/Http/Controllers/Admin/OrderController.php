<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Activity;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()->withCount('items')
            ->when($request->query('q'), function ($q, $t) {
                $t = en_num($t);
                $q->where(fn ($q) => $q->where('code', 'like', "%$t%")->orWhere('name', 'like', "%$t%")->orWhere('mobile', 'like', "%$t%"));
            })
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $before = $order->status;
        $order->update($data);
        Activity::log('update', 'orders', 'سفارش '.$order->code.($before !== $order->status ? ' — وضعیت: '.(Order::STATUSES[$order->status] ?? $order->status) : ''));

        return back()->with('success', 'سفارش به‌روزرسانی شد.');
    }

    public function destroy(Order $order)
    {
        Activity::log('delete', 'orders', 'سفارش '.$order->code);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'سفارش حذف شد.');
    }
}
