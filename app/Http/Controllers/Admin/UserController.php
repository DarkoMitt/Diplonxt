<?php
namespace App\Http\Controllers\Admin;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
class UserController extends Controller
{
    public function index(Request $request): View { $users = User::query()->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%')->orWhere('email', 'like', '%'.$request->string('search').'%')))->when($request->filled('role'), fn ($q) => $q->where('role', $request->string('role')))->with(['thesis', 'supervisedTheses'])->latest()->paginate(12)->withQueryString(); return view('admin.users.index', ['users' => $users, 'roles' => UserRole::cases()]); }
    public function store(StoreUserRequest $request): RedirectResponse { User::create([...$request->safe()->except(['password_confirmation']), 'password' => Hash::make($request->validated('password')), 'email_verified_at' => now()]); return back()->with('success', 'User created.'); }
    public function update(Request $request, User $user): RedirectResponse { $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'role' => ['required', 'in:student,professor,admin']]); abort_if($user->is($request->user()) && $data['role'] !== 'admin', 422, 'You cannot remove your own administrator role.'); $user->update($data); return back()->with('success', 'User updated.'); }
    public function destroy(Request $request, User $user): RedirectResponse { abort_if($user->is($request->user()), 422, 'You cannot delete your own account.'); $user->delete(); return back()->with('success', 'User deleted.'); }
}
