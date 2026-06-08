<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreChatMessageRequest;
use App\Models\Thesis;
use App\Notifications\ThesisActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class ChatController extends Controller
{
    public function show(Thesis $thesis): View { $this->authorize('view', $thesis); $thesis->messages()->where('receiver_id', auth()->id())->whereNull('read_at')->update(['read_at' => now()]); return view('chat.show', ['thesis' => $thesis->load(['student', 'professor', 'messages.sender'])]); }
    public function store(StoreChatMessageRequest $request, Thesis $thesis): RedirectResponse
    {
        $receiver = $request->user()->id === $thesis->student_id ? $thesis->professor : $thesis->student; abort_unless($receiver, 422, 'A mentor must be assigned before messaging.');
        $thesis->messages()->create(['sender_id' => $request->user()->id, 'receiver_id' => $receiver->id, 'message' => $request->validated('message')]); $receiver->notify(new ThesisActivityNotification('New message', "{$request->user()->name} sent you a message.", route('chat.show', $thesis)));
        return back();
    }
}
