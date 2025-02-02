<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AccessAddRequest extends FormRequest
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
            "role" => ["required", "uuid", "max:50"],
            "access_lists.*" => ["required", "alpha_dash", "max:50", Rule::in(collect(config('access.roleList'))->pluck("code")->toArray())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            "access_lists" => str($this->post('access_lists'))->isJson() ? json_decode($this->post('access_lists'), true) : null,
        ]);
    }
}
