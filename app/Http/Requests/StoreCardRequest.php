<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCardRequest extends FormRequest
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
            'account_id' => ['required', 'exists:accounts,id'],
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'limit' => ['required', 'integer', 'min:0'],
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

        $data['limit'] = Helper::extractNumbersFromString($data['limit'], forceInteger: true);

        return $data;
    }
}
