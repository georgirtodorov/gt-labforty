<?php
declare(strict_types=1);

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|in:requested,confirmed',
            'notification_type' => 'required|in:sms,email',
            'description' => 'nullable|string|max:1000',
            'time_slot_id' => 'required|exists:time_slots,id',
            'identifier' => [
                'required',
                'string',
                'size:10',
                'regex:/^\d+$/',
            ],
            'first_name' => 'required|string|min:2|max:64',
            'last_name' => 'required|string|min:2|max:64',
        ];
    }
}
