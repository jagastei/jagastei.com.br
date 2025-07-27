<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Helper;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

final class StoreTransactionOutRequest extends FormRequest
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
            'value' => ['required', 'integer', 'min:0'],
            'category' => ['required', 'exists:categories,id,wallet_id,'.auth('web')->user()->currentWallet->id.',type,OUT'],
            'account' => ['required', 'exists:accounts,id,wallet_id,'.auth('web')->user()->currentWallet->id],
            'method' => ['required', 'in:'.implode(',', Transaction::METHODS)],
            'card' => ['nullable', 'exists:cards,id,wallet_id,'.auth('web')->user()->currentWallet->id],

            'metadata' => ['nullable', 'array'],

            'ai' => ['nullable', 'array'],
            'file_path' => ['nullable', 'string'],
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
