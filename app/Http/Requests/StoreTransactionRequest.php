<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'min:2', 'max:30'],
            'description' => ['nullable', 'string', 'min:2', 'max:255'],
            'type' => ['required', 'in:IN,OUT'],
            'value' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'uuid', 'exists:categories,id'],
            'account' => ['required', 'uuid', 'exists:accounts,id'],
            'method' => ['nullable', 'in:CASH,CARD,TED,PIX,OTHER,UNKNOWN'],
            'card' => ['nullable', 'uuid', 'exists:cards,id'],
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $data = $this->all();

        $data['value'] = Helper::extractNumbersFromString($data['value'], forceInteger: true);

        return $data;
    }
}
