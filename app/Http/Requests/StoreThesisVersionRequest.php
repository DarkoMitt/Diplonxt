<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
class StoreThesisVersionRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('update', $this->route('thesis')) ?? false; }
    public function rules(): array { return ['document' => ['required', File::types(['pdf', 'doc', 'docx'])->max('20mb')], 'notes' => ['nullable', 'string', 'max:2000']]; }
}
