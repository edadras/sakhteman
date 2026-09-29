<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Activity;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::query()
            ->when($request->query('q'), function ($q, $term) {
                $q->where(fn ($q) => $q->where('name', 'like', "%$term%")
                    ->orWhere('email', 'like', "%$term%")
                    ->orWhere('phone', 'like', "%$term%")
                    ->orWhere('subject', 'like', "%$term%"));
            })
            ->when($request->query('status') === 'unread', fn ($q) => $q->where('is_read', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        Activity::log('delete', 'messages', 'پیام '.$message->name.($message->subject ? ' — '.$message->subject : ''));
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'پیام حذف شد.');
    }
}
