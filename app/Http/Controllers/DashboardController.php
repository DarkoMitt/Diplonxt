<?php

namespace App\Http\Controllers;

use App\Enums\ThesisStatus;
use App\Enums\UserRole;
use App\Enums\VersionStatus;
use App\Models\DefenseSchedule;
use App\Models\Thesis;
use App\Models\ThesisVersion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return match ($request->user()->role) {
            UserRole::Student => $this->student($request),
            UserRole::Professor => $this->professor($request),
            UserRole::Administrator => $this->administrator(),
        };
    }

    private function student(Request $request): View
    {
        $thesis = $request->user()->thesis()->with(['professor', 'versions.feedback', 'feedback.professor', 'statusHistory', 'defenseSchedule'])->first();

        return view('dashboard.student', ['thesis' => $thesis, 'notifications' => $request->user()->notifications()->latest()->limit(6)->get(), 'unreadMessages' => $thesis?->messages()->where('receiver_id', $request->user()->id)->whereNull('read_at')->count() ?? 0]);
    }

    private function professor(Request $request): View
    {
        $query = $request->user()->supervisedTheses()->with(['student', 'versions'])->withCount(['versions', 'feedback']);
        if ($request->filled('search')) { $query->whereHas('student', fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%')); }
        if ($request->filled('status')) { $query->where('status', $request->string('status')); }
        $theses = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.professor', [
            'theses' => $theses,
            'statuses' => ThesisStatus::cases(),
            'assignedStudents' => $request->user()->supervisedTheses()->count(),
            'pendingReviews' => ThesisVersion::whereHas('thesis', fn ($q) => $q->where('professor_id', $request->user()->id))->where('status', VersionStatus::Pending)->count(),
            'approvedTheses' => $request->user()->supervisedTheses()->whereIn('status', [ThesisStatus::DefenseApproved, ThesisStatus::DefenseScheduled, ThesisStatus::Pass, ThesisStatus::Completed])->count(),
            'upcomingDefenses' => DefenseSchedule::whereHas('thesis', fn ($q) => $q->where('professor_id', $request->user()->id))->where('scheduled_at', '>=', now())->count(),
        ]);
    }

    private function administrator(): View
    {
        return view('dashboard.admin', [
            'totalStudents' => User::where('role', UserRole::Student)->count(),
            'totalProfessors' => User::where('role', UserRole::Professor)->count(),
            'activeTheses' => Thesis::whereNotIn('status', [ThesisStatus::Completed, ThesisStatus::Archived, ThesisStatus::Fail])->count(),
            'pendingApprovals' => Thesis::whereIn('status', [ThesisStatus::TopicSubmitted, ThesisStatus::DemoReview, ThesisStatus::DefenseApproved])->count(),
            'completedTheses' => Thesis::whereIn('status', [ThesisStatus::Completed, ThesisStatus::Archived])->count(),
            'upcomingDefenses' => DefenseSchedule::where('scheduled_at', '>=', now())->count(),
            'recentTheses' => Thesis::with(['student', 'professor'])->latest()->limit(6)->get(),
            'statusBreakdown' => Thesis::selectRaw('status, count(*) as total')->groupBy('status')->orderByDesc('total')->get(),
        ]);
    }
}
