<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            "code" => ["required", "string", "min:1", "max:10", "regex:" . config("setting.regxp.forCode")],
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'def_path' => ['required', 'string', 'min:5', 'max:255'],
        ];

        // update
        if (in_array($this->method(), ["PUT", "PATCH"])) {
            $rules = array_merge($rules, [
                "id" => ["required", "uuid", Rule::exists("roles", "id")],
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
