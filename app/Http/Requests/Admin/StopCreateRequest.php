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
            'name'       => "required|string|max:255",
            'code'       => [
                'required', 'string', 'max:255',
                Rule::unique('stops', 'code')->ignore($stop),
            ],

            'local_body' => "nullable|string|max:255",
            'assembly'   => "nullable|string|max:255",
            'district'   => "nullable|string|max:255",
            'state'      => "nullable|string|max:255",
            'pincode'    => "nullable|integer",

            'latitude'   => "nullable|numeric",
            'longitude'  => "nullable|numeric",
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'code.unique' => 'The code has already been taken.',
        ];
    }
}
