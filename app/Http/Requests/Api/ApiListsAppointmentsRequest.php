<?php
declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApiListsAppointmentsRequest extends FormRequest
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
            'identifier' => 'nullable|string|max:64',
            'date_from' => [
                'nullable',
                'date',
                'date_format:Y-m-d\TH:i:s\Z',
            ],
            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
                'date_format:Y-m-d\TH:i:s\Z',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.date' => 'Началната дата не е валидна.',
            'date_to.date' => 'Крайната дата не е валидна.',
            'date_to.after_or_equal' => 'Крайната дата ("До") не може да бъде преди началната ("От").',
        ];
    }
}
