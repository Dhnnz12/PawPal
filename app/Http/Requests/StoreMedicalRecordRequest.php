<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isVet();
    }

    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'visit_date' => 'required|date',
            'diagnosis' => 'required|string|max:1000',
            'treatment' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'pet_id.required' => 'Pilih hewan peliharaan.',
            'diagnosis.required' => 'Diagnosis harus diisi.',
            'treatment.required' => 'Pengobatan harus diisi.',
        ];
    }
}
