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
            'use_due_date' => ['required', 'boolean'],
            'due_date' => [Rule::RequiredIf(fn() => $this->input('use_due_date')), 'date'],
            'category' => ['required', 'uuid', Rule::exists('categories', 'id')],
            'category_sub' => ['required', 'uuid', Rule::exists('category_subs', 'id')],
            'ref_doc_id' => ['nullable', 'uuid', Rule::exists('documents', 'id')],
            'is_locked' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
            'is_public' => ['required', 'boolean'],
            "files" => ["required"],
            "files.*" => ["required", File::types(config('setting.other.file_doc_allowed'))->max(config('setting.other.max_file_size'))],
            'approval_workflow_type' => ['required',  Rule::in(WorkflowType::cases())],
            "approval_users" => ["required", "array"],
            "approval_users.*.id" => ["required", "uuid", Rule::exists('users', 'id')],
            "approval_users.*.name" => ["required", "string"],
            "approval_users.*.email" => ["required", "email"],
            "approval_users.*.order" => ["required", "integer"],
            "informed_users" => ["nullable", "array"],
            "informed_users.*.id" => ["nullable", "uuid", Rule::exists('users', 'id')],
            "informed_users.*.name" => ["nullable", "string"],
            "informed_users.*.email" => ["nullable", "email"],
            'is_review_required' => ['required', 'boolean'],
            'review_workflow_type' => [Rule::RequiredIf(fn() => $this->input('is_review_required')), Rule::in(WorkflowType::cases())],
            "review_users" => [Rule::RequiredIf(fn() => $this->input('is_review_required')), "array"],
            "review_users.*.id" => [Rule::RequiredIf(fn() => $this->input('is_review_required')), "uuid", Rule::exists('users', 'id')],
            "review_users.*.name" => [Rule::RequiredIf(fn() => $this->input('is_review_required')), "string"],
            "review_users.*.email" => [Rule::RequiredIf(fn() => $this->input('is_review_required')), "email"],
            "review_users.*.order" => [Rule::RequiredIf(fn() => $this->input('is_review_required')), "integer"],
            'is_acknowledgement_required' => ['required', 'boolean'],
            'acknowledgement_workflow_type' => [Rule::RequiredIf(fn() => $this->input('is_acknowledgement_required')), Rule::in(WorkflowType::cases())],
            "acknowledgement_users" => [Rule::RequiredIf(fn() => $this->input('is_acknowledgement_required')), "array"],
            "acknowledgement_users.*.id" => [Rule::RequiredIf(fn() => $this->input('is_acknowledgement_required')), "uuid", Rule::exists('users', 'id')],
            "acknowledgement_users.*.name" => [Rule::RequiredIf(fn() => $this->input('is_acknowledgement_required')), "string"],
            "acknowledgement_users.*.email" => [Rule::RequiredIf(fn() => $this->input('is_acknowledgement_required')), "email"],
            "acknowledgement_users.*.order" => [Rule::RequiredIf(fn() => $this->input('is_acknowledgement_required')), "integer"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'use_due_date' => $this->has('use_due_date') ? true : false,
            'ref_doc_id' => $this->has('ref_doc_id') && $this->post('ref_doc_id') === 'null' ? null : $this->post('ref_doc_id'),
            'is_locked' => $this->has('is_locked') ? filter_var($this->post('is_locked'), FILTER_VALIDATE_BOOLEAN) : false,
            'is_public' => $this->has('is_public') ? filter_var($this->post('is_public'), FILTER_VALIDATE_BOOLEAN) : false,
            'approval_users' => str($this->post('approval_users'))->isJson() ? json_decode($this->post('approval_users'), true) : null,
            'informed_users' => str($this->post('informed_users'))->isJson() ? json_decode($this->post('informed_users'), true) : null,
            'is_review_required' => $this->has('is_review_required') ? filter_var($this->post('is_review_required'), FILTER_VALIDATE_BOOLEAN) : false,
            'review_workflow_type' => $this->filled('review_workflow_type') ? $this->post('review_workflow_type') : WorkflowType::INORDER->value,
            'review_users' => str($this->post('review_users'))->isJson() ? json_decode($this->post('review_users'), true) : null,
            'is_acknowledgement_required' => $this->has('is_acknowledgement_required') ? filter_var($this->post('is_acknowledgement_required'), FILTER_VALIDATE_BOOLEAN) : false,
            'acknowledgement_workflow_type' => $this->filled('acknowledgement_workflow_type') ? $this->post('acknowledgement_workflow_type') : WorkflowType::INORDER->value,
            'acknowledgement_users' => str($this->post('acknowledgement_users'))->isJson() ? json_decode($this->post('acknowledgement_users'), true) : null,
        ]);
    }
}
