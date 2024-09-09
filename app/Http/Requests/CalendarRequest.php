<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'visitor_name' => 'required|string|max:255',
            'visitor_mobile' => 'required|string|max:20',
            'visitor_email' => 'required|email|max:255',
            'vehicle_number' => 'required|string|max:20',
            'vehicle_color' => 'required|string|max:50',
            'vehicle_model' => 'required|string|max:100',
            'has_arrival_booking' => 'required|in:Yes,No',
            'arrival_mode' => 'nullable|string|max:100',
            'arrival_datetime' => 'nullable|date_format:Y-m-d\TH:i',
            'has_hold_luggage' => 'nullable|in:Yes,No',
            'has_departure_booking' => 'required|in:Yes,No',
            'departure_meeting_time' => 'nullable|date_format:Y-m-d\TH:i',
            'additional_info' => 'nullable|string',
        ];
    }
}
