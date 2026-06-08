<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Models\Thesis;
use App\Notifications\ThesisActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FeedbackController extends Controller
{
    public function store(StoreFeedbackRequest $request, Thesis $thesis): RedirectResponse
    {
        $feedback = DB::transaction(function () use ($request, $thesis) { $version = $thesis->versions()->findOrFail($request->integer('thesis_version_id')); $version->update(['status' => $request->validated('version_status')]); return $thesis->feedback()->create(['professor_id' => $request->user()->id, 'thesis_version_id' => $version->id, 'comment' => $request->validated('comment')]); });
        $thesis->student->notify(new ThesisActivityNotification('New mentor feedback', "Feedback was added to version {$feedback->version->version_number}.", route('theses.show', $thesis)));
        return back()->with('success', 'Feedback published.');
    }
    public function resolve(Request $request, Feedback $feedback): RedirectResponse
    {
        $this->authorize('view', $feedback->thesis); abort_unless($request->user()->id === $feedback->thesis->student_id, 403); $data = $request->validate(['student_reply' => ['nullable', 'string', 'max:3000']]); $feedback->update([...$data, 'status' => 'resolved', 'resolved_at' => now()]);
        return back()->with('success', 'Feedback marked as resolved.');
    }
}
