<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
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
            'preco_un_catalogo' => 'nullable|numeric|gt:0',
            'preco_un_proprio' => 'nullable|numeric|gt:0',
            'preco_un_catalogo_desconto' => 'nullable|numeric|gt:0',
            'preco_un_proprio_desconto' => 'nullable|numeric|gt:0',
            'quantidade_desconto' => 'nullable|numeric|gt:0'
        ];
    }
}
