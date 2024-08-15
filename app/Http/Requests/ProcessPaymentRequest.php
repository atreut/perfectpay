<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
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
            'customer' => 'required|string',
            'billingType' => 'required|in:BOLETO,CREDIT_CARD,PIX', 
            'value' => 'required|numeric|min:0.01',
            'dueDate' => 'required|date|date_format:Y-m-d',
            'description' => 'required|string|max:255',
        ];
    }

    /**
     * Customize the error messages for the validator.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'customer.required' => 'O campo cliente é obrigatório.',
            'customer.exists' => 'O cliente selecionado é inválido.',
            'billingType.required' => 'O campo tipo de cobrança é obrigatório.',
            'billingType.in' => 'O tipo de cobrança deve ser um dos seguintes: BOLETO, CREDIT_CARD, PIX.',
            'value.required' => 'O campo valor é obrigatório.',
            'value.numeric' => 'O valor deve ser um número.',
            'value.min' => 'O valor deve ser no mínimo 0.01.',
            'dueDate.required' => 'O campo data de vencimento é obrigatório.',
            'dueDate.date' => 'A data de vencimento deve ser uma data válida.',
            'dueDate.date_format' => 'O formato da data de vencimento deve ser Y-m-d.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.string' => 'A descrição deve ser uma string.',
            'description.max' => 'A descrição não pode ter mais de 255 caracteres.',
        ];
    }
}
