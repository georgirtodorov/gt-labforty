<?php
declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ApiCreateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notification_type' => 'required|in:sms,email',
            'description' => 'nullable|string|max:1000',
            'identifier' => [
                'required',
                'string',
                'size:10',
                'regex:/^\d+$/',
            ],
            'first_name' => 'required|string|min:2|max:64',
            'last_name' => 'required|string|min:2|max:64',
            'start_at' => [
                'required',
                'date',
                'date_format:Y-m-d\TH:i:s\Z',
            ],
            'end_at' => [
                'required',
                'date',
                'after:start_at',
                'date_format:Y-m-d\TH:i:s\Z',
            ]
        ];
    }
}
