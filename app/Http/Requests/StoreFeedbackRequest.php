<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('review', $this->route('thesis')) ?? false; }
    public function rules(): array { return ['thesis_version_id' => ['required', Rule::exists('thesis_versions', 'id')->where('thesis_id', $this->route('thesis')->id)], 'comment' => ['required', 'string', 'max:5000'], 'version_status' => ['required', Rule::in(['reviewed', 'approved', 'rejected', 'revision_required'])]]; }
}
