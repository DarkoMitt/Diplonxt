<?php
namespace App\Http\Requests;
use App\Enums\ThesisStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateThesisStatusRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('review', $this->route('thesis')) ?? false; }
    public function rules(): array { return ['status' => ['required', Rule::enum(ThesisStatus::class)], 'note' => ['nullable', 'string', 'max:2000'], 'deadline' => ['nullable', 'date']]; }
}
