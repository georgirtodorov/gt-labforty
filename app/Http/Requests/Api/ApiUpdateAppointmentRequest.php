<?php
declare(strict_types=1);

namespace App\Http\Requests\Api;

class ApiUpdateAppointmentRequest extends ApiCreateAppointmentRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => 'required|in:requested,confirmed',
        ]);
    }
}
