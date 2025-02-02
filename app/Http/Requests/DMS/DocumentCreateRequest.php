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
            'notes' => ['nullable', 'string'],
            'approval_workflow_type' => ['required', 'string', Rule::in(WorkflowType::cases())],
            'is_locked' => ['required', 'boolean'],
            'review_workflow_type' => ['required', 'string', Rule::in(WorkflowType::cases())],
            'req_review' => ['required', 'boolean'],
            'acknowledgement_workflow_type' => ['required', 'string', Rule::in(WorkflowType::cases())],
            'req_acknowledgement' => ['required', 'boolean'],
            "files" => ["required"],
            "files.*" => ["required", File::types(array_merge(config('setting.other.file_doc_attachment_allowed'), config('setting.other.file_img_allowed')))->max(config('setting.other.max_file_size'))],
            "approval_users" => ["required", "array"],
            "approval_users.*.id" => ["required", "uuid", Rule::exists('users', 'id')],
            "approval_users.*.name" => ["required", "string"],
            "approval_users.*.email" => ["required", "email"],
            "approval_users.*.order" => ["required", "integer"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_locked' => $this->has('is_locked') ? filter_var($this->post('is_locked'), FILTER_VALIDATE_BOOLEAN) : false,
            'review_workflow_type' => WorkflowType::INORDER->value,
            'req_review' => $this->has('req_review') ? filter_var($this->post('req_review'), FILTER_VALIDATE_BOOLEAN) : false,
            'acknowledgement_workflow_type' => WorkflowType::INORDER->value,
            'req_acknowledgement' => $this->has('req_acknowledgement') ? filter_var($this->post('req_acknowledgement'), FILTER_VALIDATE_BOOLEAN) : false,
            'approval_users' => str($this->post('approval_users'))->isJson() ? \json_decode($this->post('approval_users'), true) : null,
        ]);
    }
}
