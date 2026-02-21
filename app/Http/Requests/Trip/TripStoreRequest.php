<?php
namespace App\Http\Requests\Trip;

use App\Enums\AuthStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class TripStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('trip_schedule');

        $rules = [
            'bus_id'                 => 'required|exists:buses,id',
            'origin_stop_id'         => 'required|exists:stops,id',
            'destination_stop_id'    => 'required|exists:stops,id',
            'departure_time'         => 'required',
            'arrival_time'           => 'required|after_or_equal:departure_time',
            'days_of_week'           => 'required|array',
            'time_between_stops_sec' => 'required|numeric',
            'status'                 => 'nullable|in:0,1',
        ];

        if (Auth::guard('web')->check()) {
            $rules['effective_from'] = 'nullable|date';
            $rules['effective_to']   = 'nullable|date|after_or_equal:effective_from';

            if (! empty($id)) {
                $rules['auth_status'] = ['required', new Enum(AuthStatus::class)];
            }
        }

        return $rules;
    }
}
