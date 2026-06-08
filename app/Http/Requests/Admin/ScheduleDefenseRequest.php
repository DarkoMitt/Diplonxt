<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class ScheduleDefenseRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isAdministrator() ?? false; }
    public function rules(): array { return ['scheduled_at' => ['required', 'date'], 'location' => ['required', 'string', 'max:255'], 'committee_members' => ['required', 'array', 'min:2'], 'committee_members.*' => ['required', 'string', 'max:255'], 'notes' => ['nullable', 'string', 'max:2000']]; }
}
