<?php
declare(strict_types=1);

namespace App\Http\Requests\Appointments;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentListingRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identifier'   => 'nullable|string|max:64',
            'start_date'   => 'nullable|date_format:Y-m-d',
            'start_hour'   => 'nullable|string|size:02',
            'start_minute' => 'nullable|string|size:02',
            'end_date'     => 'nullable|date_format:Y-m-d',
            'end_hour'     => 'nullable|string|size:02',
            'end_minute'   => 'nullable|string|size:02',
        ];
    }
}
