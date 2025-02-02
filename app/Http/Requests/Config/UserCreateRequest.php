<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends FormRequest
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
        $password = !app()->isProduction() ? Password::min(6) : Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised();

        return [
            "username" => ["required", "string", "min:2", "max:255", "regex:" . config("setting.regxp.forUsername")],
            "email" => ["required", "email", "min:10", "max:255"],
            "password" => ["required", $password, "max:150",],
            "name" => ["required", "string", "min:3", "max:255"],
            "role" => ["required", "uuid", Rule::exists("roles", "id")],
            "timezone" => ["required", "timezone:all"],
            "is_active" => ["required", "boolean"],
            "picture" => ["nullable", "image", "max:500"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? filter_var($this->post('is_active'), FILTER_VALIDATE_BOOLEAN) : false,
        ]);
    }
}
