<?php

namespace App\Http\Requests\DMS;

use App\Enums\WorkflowType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class DocumentCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'due_date' => ['nullable', 'date'],
            'category' => ['required', 'uuid', Rule::exists('categories', 'id')],
            'category_sub' => ['required', 'uuid', Rule::exists('category_subs', 'id')],
            'is_locked' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
            'is_public' => ['required', 'boolean'],
            "files" => ["required"],
            "files.*" => ["required", File::types(array_merge(config('setting.other.file_doc_attachment_allowed'), config('setting.other.file_img_allowed')))->max(config('setting.other.max_file_size'))],
            'approval_workflow_type' => ['required', 'string', Rule::in(WorkflowType::cases())],
            "approval_users" => ["required", "array"],
            "approval_users.*.id" => ["required", "uuid", Rule::exists('users', 'id')],
            "approval_users.*.name" => ["required", "string"],
            "approval_users.*.email" => ["required", "email"],
            "approval_users.*.order" => ["required", "integer"],
            'is_review_required' => ['required', 'boolean'],
            'review_workflow_type' => [$this->input('is_review_required'), 'string', Rule::in(WorkflowType::cases())],
            "review_users" => [$this->input('is_review_required'), "array"],
            "review_users.*.id" => [$this->input('is_review_required'), "uuid", Rule::exists('users', 'id')],
            "review_users.*.name" => [$this->input('is_review_required'), "string"],
            "review_users.*.email" => [$this->input('is_review_required'), "email"],
            "review_users.*.order" => [$this->input('is_review_required'), "integer"],
            'is_acknowledgement_required' => ['required', 'boolean'],
            'acknowledgement_workflow_type' => [$this->input('is_acknowledgement_required'), 'string', Rule::in(WorkflowType::cases())],
            "acknowledgement_users" => [$this->input('is_acknowledgement_required'), "array"],
            "acknowledgement_users.*.id" => [$this->input('is_acknowledgement_required'), "uuid", Rule::exists('users', 'id')],
            "acknowledgement_users.*.name" => [$this->input('is_acknowledgement_required'), "string"],
            "acknowledgement_users.*.email" => [$this->input('is_acknowledgement_required'), "email"],
            "acknowledgement_users.*.order" => [$this->input('is_acknowledgement_required'), "integer"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_locked' => $this->has('is_locked') ? filter_var($this->post('is_locked'), FILTER_VALIDATE_BOOLEAN) : false,
            'is_public' => $this->has('is_public') ? filter_var($this->post('is_public'), FILTER_VALIDATE_BOOLEAN) : false,
            'approval_users' => str($this->post('approval_users'))->isJson() ? json_decode($this->post('approval_users'), true) : null,
            'is_review_required' => $this->has('is_review_required') ? filter_var($this->post('is_review_required'), FILTER_VALIDATE_BOOLEAN) : false,
            'review_users' => str($this->post('review_users'))->isJson() ? json_decode($this->post('review_users'), true) : null,
            'acknowledgement_workflow_type' => $this->has('acknowledgement_workflow_type') ? filter_var($this->post('acknowledgement_workflow_type'), FILTER_VALIDATE_BOOLEAN) : false,
            'acknowledgement_users' => str($this->post('acknowledgement_users'))->isJson() ? json_decode($this->post('acknowledgement_users'), true) : null,
        ]);
    }
}
