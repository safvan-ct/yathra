<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StopCreateRequest extends FormRequest
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
        $stop = $this->route('stop');

        $rules = [
            'name'      => "required|string|max:255",

            'locality'  => "nullable|string|max:255",

            'latitude'  => "nullable|numeric",
            'longitude' => "nullable|numeric",
        ];

        if (! $stop) {
            $rules['city_id'] = 'required|string|max:50|exists:cities,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
