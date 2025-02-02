<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            "email" => ["required", "email", "min:10", "max:255"],
            "name" => ["required", "string", "min:3", "max:255"],
            "password" => ["required", $password, "max:150", "confirmed"],
            "timezone" => ["required", "timezone:all"],
        ];
    }
}
