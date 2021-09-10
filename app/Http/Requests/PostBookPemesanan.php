<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostBookPemesanan extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "nama" => "required",
            "alamat" => "required",
            "email" => "required",
            "no_hp" => "required",
            "catatan" => "required",
            "user_id" => "required",
            "jml_jemaah" => "required",
            "book" => "required",
        ];
    }
}
