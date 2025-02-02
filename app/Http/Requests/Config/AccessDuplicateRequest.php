<?php

namespace App\Http\Requests\Config;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccessDuplicateRequest extends FormRequest
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
            "from_role" => ["required", "uuid", Rule::exists("roles", "id")],
            "to_role" => ["required", "uuid", Rule::exists("roles", "id")],
            "exclude_accesses" => ["nullable", "array"],
            "exclude_accesses.*" => ["alpha_dash", "max:50"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            "exclude_accesses" => str($this->post('exclude_accesses'))->isJson() ? json_decode($this->post('exclude_accesses'), true) : [],
        ]);
    }
}
