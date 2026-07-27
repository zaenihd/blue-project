<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
            //
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'mimes:png,jpg', 'max:2048'],
            'about' => ['required', 'string'],
            'phone' => ['required', 'string',],
            'address_id' => ['required'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama Toko',
            'logo' => 'Logo toko',
            'about' => 'Tentang toko',
            'phone' => 'Nomer Telepon',
            'address_id' => 'Alamat Toko',
            'city' => 'Kota',
            "address" => 'Alamat',
            'postal_code' => 'Kode Pos'

        ];
    }
}
