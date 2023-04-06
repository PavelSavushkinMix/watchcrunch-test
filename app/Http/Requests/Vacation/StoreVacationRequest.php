<?php

namespace App\Http\Requests\Vacation;

use App\Rules\Vacation\DateFormat;
use Illuminate\Foundation\Http\FormRequest;

class StoreVacationRequest extends FormRequest
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
            'start' => ['required', new DateFormat()],
            'end' => ['required', new DateFormat(), 'after:start', 'before_or_equal:today'],
            'price' => ['required', 'numeric'],
        ];
    }
}
