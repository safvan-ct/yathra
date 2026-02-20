<?php
namespace App\Http\Requests\Bus;

use App\Enums\OperatorAuthStatus;
use App\Enums\OperatorType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class OperatorStoreRequest extends FormRequest
{
    protected $errorBag = 'register';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('bus_operator');

        $rules = [
            "name" => "required",
            "type" => ['required', new Enum(OperatorType::class)],
        ];

        if (empty($id)) {
            $rules['phone']        = "required|unique:operators,phone";
            $rules['register_pin'] = "required|min:4|max:4";
        } else {
            $rules['phone']       = "required|unique:operators,phone," . $id;
            $rules['auth_status'] = ['required', new Enum(OperatorAuthStatus::class)];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'register_pin.min' => 'Pin must be 4 digits',
            'register_pin.max' => 'Pin must be 4 digits',
        ];
    }
}
