<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isPetOwner();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:dog,cat,rabbit,bird,hamster,guinea_pig,other',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:50',
            'weight' => 'nullable|numeric|min:0|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'health_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama hewan peliharaan harus diisi.',
            'type.required' => 'Jenis hewan harus dipilih.',
            'type.in' => 'Jenis hewan tidak valid.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
