<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'label' => 'required|string|max:50',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_primary' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Label alamat harus diisi (Rumah, Kantor, dll).',
            'address_line.required' => 'Alamat lengkap harus diisi.',
            'city.required' => 'Kota harus diisi.',
            'latitude.between' => 'Koordinat lintang tidak valid.',
            'longitude.between' => 'Koordinat bujur tidak valid.',
        ];
    }
}
