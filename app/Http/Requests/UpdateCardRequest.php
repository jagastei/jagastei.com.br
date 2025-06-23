<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Helper;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateCardRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'limit' => ['required', 'integer', 'min:0'],

            'digits' => ['nullable', 'integer'],
            'brand' => ['nullable'],

            'expiration_month' => ['required', 'integer', 'min:1', 'max:12'],
            'expiration_year' => ['required', 'integer', 'min:2000', 'max:2100'],

            'credit' => ['required', 'boolean'],
            'virtual' => ['required', 'boolean'],
            'international' => ['required', 'boolean'],
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
        $data['expiration_date'] = Carbon::create($data['expiration_year'], $data['expiration_month'], 1)->format('Y-m-d');

        unset($data['expiration_month'], $data['expiration_year']);

        return $data;
    }
}
