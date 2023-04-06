<?php

namespace App\Http\Requests\Vacation;

use App\Http\Requests\FiltersDatesMutatorTrait;
use App\Rules\FilterOperator;
use App\Rules\Vacation\DateFormat;
use Illuminate\Foundation\Http\FormRequest;

class FetchVacationsRequest extends FormRequest
{
    use FiltersDatesMutatorTrait;

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
            'price' => ['sometimes', 'array'],
            'price.*' => ['numeric', new FilterOperator()],
            'start' => ['sometimes', 'array'],
            'start.*' => [new DateFormat(), new FilterOperator()],
            'end' => ['sometimes', 'array'],
            'end.*' => [new DateFormat(), new FilterOperator()],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function passedValidation()
    {
        $filterFieldsToConvert = [
            'start',
            'end',
        ];

        foreach ($filterFieldsToConvert as $field) {
            if ($this->filled($field)) {
                $values = [];
                $start = $this->get($field);
                foreach ($start as $key => $item) {
                    $values[$key] = $this->convertJsDate($item);
                }

                $this->merge([
                    $field => $values,
                ]);
            }
        }

    }
}
