<?php

namespace App\Http\Requests\Basic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AprovalSetRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            "users" => ["required", "array"],
            "users.*.id" => ["required", "uuid", Rule::exists("users", "id")],
            "users.*.order" => ["required", "integer"],
        ];

        // update
        if (in_array($this->method(), ["PUT", "PATCH"])) {
            $rules = array_merge($rules, [
                "id" => ["required", "uuid", Rule::exists("approval_sets", "id")],
            ]);
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        // update
        if (in_array($this->method(), ["PUT", "PATCH"])) {
            $this->merge([
                'id' => $this->route('id')
            ]);
        }
    }
}
