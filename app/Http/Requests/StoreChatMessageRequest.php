<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreChatMessageRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('view', $this->route('thesis')) ?? false; }
    public function rules(): array { return ['message' => ['required', 'string', 'max:3000']]; }
}
