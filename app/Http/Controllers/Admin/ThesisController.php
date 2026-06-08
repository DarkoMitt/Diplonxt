<?php
namespace App\Http\Controllers\Admin;
use App\Enums\ThesisStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\User;
use App\Notifications\ThesisActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class ThesisController extends Controller
{
    public function index(Request $request): View { $theses = Thesis::with(['student', 'professor', 'defenseSchedule'])->withCount('versions')->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%')->orWhereHas('student', fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))))->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))->latest()->paginate(12)->withQueryString(); return view('admin.theses.index', ['theses' => $theses, 'professors' => User::where('role', UserRole::Professor)->orderBy('name')->get(), 'statuses' => ThesisStatus::cases()]); }
    public function assign(Request $request, Thesis $thesis): RedirectResponse { $data = $request->validate(['professor_id' => ['required', 'exists:users,id']]); $professor = User::where('role', UserRole::Professor)->findOrFail($data['professor_id']); $thesis->update(['professor_id' => $professor->id]); $thesis->student->notify(new ThesisActivityNotification('Mentor assigned', "{$professor->name} is now your thesis mentor.", route('theses.show', $thesis))); return back()->with('success', 'Mentor assigned.'); }
    public function archive(Thesis $thesis): RedirectResponse { $thesis->update(['status' => ThesisStatus::Archived, 'current_phase' => ThesisStatus::Archived->label(), 'progress_percentage' => 100, 'is_archived' => true]); return back()->with('success', 'Thesis archived.'); }
}
