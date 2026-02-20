<?php
namespace App\Http\Requests\Bus;

use App\Enums\BusAuthStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BusStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('bus');

        if ($this->bus_number) {
            $slug = strtolower($this->bus_number);
            $slug = preg_replace('/\s+/', '', $slug);
            $this->merge(['slug' => $slug]);
        }

        $rules = [
            'bus_name'  => 'required',
            'bus_color' => 'required|in:info,danger,success,white',
            'active'    => 'nullable|in:0,1',
        ];

        if (Auth::guard('web')->check()) {
            $rules['operator_id'] = 'required|exists:operators,id';
            if (! empty($id)) {
                $rules['auth_status'] = ['required', new Enum(BusAuthStatus::class)];
            }
        }

        if (! empty($id)) {
            $rules['bus_number'] = ['required', Rule::unique('buses', 'bus_number')->ignore($id)];
            $rules['slug']       = ['required', Rule::unique('buses', 'slug')->ignore($id)];
        } else {
            $rules['bus_number'] = 'required|unique:buses,slug';
            $rules['slug']       = 'required|unique:buses,slug';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'operator_id.required' => Auth::guard('operator')->check() ? 'You logged as an admin on another window' : 'Select a valid operator',
            'bus_color.in'         => 'Select a valid color',
            'slug.unique'          => 'Bus number already exists',
            'bus_number.unique'    => 'Bus number already exists',
        ];
    }
}
