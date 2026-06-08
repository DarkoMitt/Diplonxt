<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreThesisRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isStudent() && $this->user()->thesis === null; }
    public function rules(): array { return ['title' => ['required', 'string', 'max:255'], 'description' => ['required', 'string', 'min:50', 'max:5000']]; }
}
