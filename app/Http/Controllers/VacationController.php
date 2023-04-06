<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacation\FetchVacationsRequest;
use App\Http\Requests\Vacation\StoreVacationRequest;
use App\Http\Requests\Vacation\UpdateVacationRequest;
use App\Http\Resources\VacationResource;
use App\Models\Vacation;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchVacationsRequest $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(FetchVacationsRequest $request)
    {
        $request->merge([
            'page' => $request->get('offset', 0) + 1,
        ]);
        $perPage = $request->get('limit');

        $itemsQuery = Vacation::query();

        $request->whenFilled('price', function ($value) use ($itemsQuery) {
            $itemsQuery->filter('price', $value);
        });

        $request->whenFilled('start', function ($value) use ($itemsQuery) {
            $itemsQuery->filter('start', $value);
        });

        $request->whenFilled('end', function ($value) use ($itemsQuery) {
            $itemsQuery->filter('end', $value);
        });

        $items = $itemsQuery->paginate($perPage, ['*'], 'offset');

        return VacationResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVacationRequest $request
     *
     * @return VacationResource
     */
    public function store(StoreVacationRequest $request)
    {
        $item = Vacation::query()->create($request->only([
            'start',
            'end',
            'price',
        ]));

        return new VacationResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param Vacation $vacation
     *
     * @return VacationResource
     */
    public function show(Vacation $vacation)
    {
        return new VacationResource($vacation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVacationRequest $request
     * @param Vacation $vacation
     *
     * @return VacationResource
     */
    public function update(UpdateVacationRequest $request, Vacation $vacation)
    {
        $vacation->update($request->only([
            'start',
            'end',
            'price',
        ]));

        return new VacationResource($vacation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vacation $vacation
     *
     * @return Response
     */
    public function destroy(Vacation $vacation)
    {
        $vacation->delete();

        return response()->noContent();
    }
}
