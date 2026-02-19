<?php
namespace App\Http\Requests\Bus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BusStoreRequest extends FormRequest
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
        $id = $this->route('bus');

        $rules = [
            'bus_name'  => 'required',
            'bus_color' => 'required|in:info,danger,success',
        ];

        if (Auth::guard('web')->check()) {
            $rules['operator_id'] = 'required|exists:operators,id';
        }

        if (! empty($id)) {
            $rules['bus_number'] = ['required', Rule::unique('buses', 'bus_number')->ignore($id)];
        } else {
            $rules['bus_number'] = 'required|unique:buses,bus_number';
        }

        return $rules;
    }
}
