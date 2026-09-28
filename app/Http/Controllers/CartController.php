<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = Cart::items();

        return view('pages.shop.cart', [
            'items' => $items,
            'total' => Cart::total($items),
            'user' => $request->user(),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if (! $product->purchasable) {
            $message = 'این محصول در حال حاضر قابل خرید آنلاین نیست؛ لطفا تماس بگیرید.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 422)
                : back()->with('error', $message);
        }

        Cart::add($product->id, (int) ($data['quantity'] ?? 1));

        if ($request->expectsJson()) {
            return response()->json(['message' => '«'.$product->title.'» به سبد خرید اضافه شد.', 'count' => Cart::count()]);
        }

        return back()->with('success', '«'.$product->title.'» به سبد خرید اضافه شد.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:99']]);
        Cart::put($product->id, (int) $data['quantity']);

        return redirect()->route('cart.index');
    }

    public function remove(Product $product)
    {
        Cart::put($product->id, 0);

        return redirect()->route('cart.index')->with('success', 'محصول از سبد خرید حذف شد.');
    }

    public function checkout(Request $request)
    {
        $items = Cart::items();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'سبد خرید شما خالی است.');
        }

        $request->merge(['mobile' => en_num($request->input('mobile')), 'postal_code' => en_num($request->input('postal_code'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:1000'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'note' => ['nullable', 'string', 'max:1000'],
        ], ['mobile.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.'], [
            'name' => 'نام', 'mobile' => 'موبایل', 'city' => 'شهر', 'address' => 'آدرس', 'postal_code' => 'کد پستی', 'note' => 'توضیحات',
        ]);

        $order = DB::transaction(function () use ($data, $items, $request) {
            $order = Order::create($data + [
                'user_id' => $request->user()?->id,
                'total' => Cart::total($items),
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product->id,
                    'title' => $item->product->title,
                    'price' => $item->product->final_price,
                    'quantity' => $item->quantity,
                ]);
            }

            return $order;
        });

        Cart::clear();
        session()->push('my_orders', $order->code);

        if ($request->user() && ! $request->user()->address) {
            $request->user()->update(['address' => $data['address']]);
        }

        return redirect()->route('cart.done', $order->code);
    }

    public function done(Request $request, string $code)
    {
        // فقط صاحب سفارش (در همین نشست یا حساب کاربری) صفحه را می‌بیند
        $order = Order::with('items')->where('code', $code)->firstOrFail();
        abort_unless(in_array($code, (array) session('my_orders', []), true) || ($request->user() && $order->user_id === $request->user()->id), 404);

        return view('pages.shop.done', compact('order'));
    }
}
