<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalRequest extends FormRequest
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
            'total' => ['required', 'integer', 'numeric'],
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

        $data['total'] = Helper::extractNumbersFromString($data['total'], forceInteger: true);

        return $data;
    }
}
