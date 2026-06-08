<?php
namespace App\Http\Controllers\Admin;
use App\Enums\ThesisStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleDefenseRequest;
use App\Models\DefenseSchedule;
use App\Models\Thesis;
use App\Notifications\ThesisActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class DefenseController extends Controller
{
    public function index(): View { return view('admin.defenses.index', ['theses' => Thesis::with(['student', 'professor', 'defenseSchedule'])->whereNotIn('status', [ThesisStatus::Archived, ThesisStatus::Fail])->orderBy('defense_date')->get(), 'schedules' => DefenseSchedule::with('thesis.student')->orderBy('scheduled_at')->get()]); }
    public function store(ScheduleDefenseRequest $request, Thesis $thesis): RedirectResponse { $schedule = $thesis->defenseSchedule()->updateOrCreate([], $request->validated()); $thesis->update(['defense_date' => $schedule->scheduled_at, 'status' => ThesisStatus::DefenseScheduled, 'current_phase' => ThesisStatus::DefenseScheduled->label(), 'progress_percentage' => ThesisStatus::DefenseScheduled->progress()]); foreach (array_filter([$thesis->student, $thesis->professor]) as $user) { $user->notify(new ThesisActivityNotification('Defense scheduled', 'The thesis defense is scheduled for '.$schedule->scheduled_at->format('M j, Y g:i A').'.', route('theses.show', $thesis))); } return back()->with('success', 'Defense scheduled.'); }
}
