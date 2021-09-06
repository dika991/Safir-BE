<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaketRequest extends FormRequest
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
            "code" => "required|string",
            "name" => "required|string",
            "season" => "required|string",
            "start_date" => "required|date",
            "end_date" => "required|date",
            "hotel_id" => "required|integer",
            "airlines_id" => "required|integer"
        ];
    }
}
