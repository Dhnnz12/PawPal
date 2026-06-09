<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isPetOwner();
    }

    public function rules(): array
    {
        return [
            'provider_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'service_id' => 'nullable|exists:services,id',
            'address_id' => 'required|exists:addresses,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'provider_id.required' => 'Pilih penyedia layanan.',
            'provider_id.exists' => 'Penyedia layanan tidak ditemukan.',
            'pet_id.required' => 'Pilih hewan peliharaan.',
            'address_id.required' => 'Pilih alamat untuk layanan.',
            'booking_date.required' => 'Tanggal booking harus diisi.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'start_time.required' => 'Waktu mulai harus diisi.',
        ];
    }
}
