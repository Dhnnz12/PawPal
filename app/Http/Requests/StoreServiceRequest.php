<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isServiceProvider();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'duration_minutes' => 'required|integer|min:15|max:480',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama layanan harus diisi.',
            'price.required' => 'Harga layanan harus diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'duration_minutes.required' => 'Durasi layanan harus diisi.',
            'duration_minutes.min' => 'Durasi minimal 15 menit.',
            'duration_minutes.max' => 'Durasi maksimal 480 menit (8 jam).',
        ];
    }
}
