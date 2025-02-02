<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserAccessUpdateRequest extends FormRequest
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
            "user" => ["required", "uuid", "max:50", Rule::exists("users", "id")],
            "code" => ["required", "alpha_dash", "max:50", Rule::in(collect(config('access.userList'))->pluck("code")->toArray())],
            "permissions" => ["required", "array"],
            "permissions.*.*" => ["boolean"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $permissions = json_decode($this->post('permissions'), true);

        $this->merge([
            'permissions' => $permissions,
            'user' => $this->route('userId'),
            'code' => $this->post('code'),
        ]);
    }
}
