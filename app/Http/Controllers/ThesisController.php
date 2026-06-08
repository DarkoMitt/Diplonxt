<?php

namespace App\Http\Controllers;

use App\Enums\ThesisStatus;
use App\Http\Requests\StoreThesisRequest;
use App\Http\Requests\UpdateThesisStatusRequest;
use App\Models\Thesis;
use App\Notifications\ThesisActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ThesisController extends Controller
{
    public function show(Thesis $thesis): View
    {
        $this->authorize('view', $thesis);

        return view('theses.show', [
            'thesis' => $thesis->load([
                'student',
                'professor',
                'versions.uploader',
                'versions.feedback.professor',
                'feedback.professor',
                'statusHistory.changedBy',
                'defenseSchedule',
            ]),
            'statuses' => ThesisStatus::cases(),
        ]);
    }

    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user && $user->isStudent(), 403);

        if ($user->thesis) {
            return redirect()->route('theses.show', $user->thesis);
        }

        return view('theses.create');
    }

    public function store(StoreThesisRequest $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isStudent(), 403);

        if ($user->thesis) {
            return redirect()
                ->route('theses.show', $user->thesis)
                ->with('info', 'You already have a submitted thesis.');
        }

        $thesis = DB::transaction(function () use ($request, $user) {
            $thesis = $user->thesis()->create([
                ...$request->validated(),
                'status' => ThesisStatus::TopicSubmitted,
                'progress_percentage' => ThesisStatus::TopicSubmitted->progress(),
                'current_phase' => ThesisStatus::TopicSubmitted->label(),
            ]);

            $thesis->statusHistory()->create([
                'changed_by' => $user->id,
                'to_status' => ThesisStatus::TopicSubmitted->value,
                'note' => 'Thesis topic submitted for review.',
            ]);

            return $thesis;
        });

        return redirect()
            ->route('theses.show', $thesis)
            ->with('success', 'Your thesis topic has been submitted.');
    }

    public function updateStatus(UpdateThesisStatusRequest $request, Thesis $thesis): RedirectResponse
    {
        $this->authorize('review', $thesis);

        $status = ThesisStatus::from($request->validated('status'));
        $previous = $thesis->status;

        DB::transaction(function () use ($request, $thesis, $status, $previous) {
            $thesis->update([
                'status' => $status,
                'progress_percentage' => $status->progress(),
                'current_phase' => $status->label(),
                'deadline' => $request->validated('deadline') ?? $thesis->deadline,
                'is_archived' => $status === ThesisStatus::Archived,
            ]);

            $thesis->statusHistory()->create([
                'changed_by' => $request->user()->id,
                'from_status' => $previous->value,
                'to_status' => $status->value,
                'note' => $request->validated('note'),
            ]);
        });

        $thesis->student->notify(
            new ThesisActivityNotification(
                'Thesis status updated',
                "Your thesis moved to {$status->label()}.",
                route('theses.show', $thesis)
            )
        );

        return back()->with('success', 'Thesis status updated.');
    }
}