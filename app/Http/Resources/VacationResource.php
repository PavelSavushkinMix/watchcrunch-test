<?php

namespace App\Http\Resources;

use App\Models\Vacation;
use Illuminate\Http\Resources\Json\JsonResource;

class VacationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start' => $this->start->format(Vacation::DATES_FORMAT),
            'end' => $this->end->format(Vacation::DATES_FORMAT),
            'price' => $this->price,
            'created_at' => $this->created_at->format(Vacation::DATES_FORMAT),
            'updated_at' => $this->updated_at->format(Vacation::DATES_FORMAT),
            'deleted_at' => $this->deleted_at?->format(Vacation::DATES_FORMAT),
        ];
    }
}
