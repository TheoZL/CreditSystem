<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        return response()->json(Vehicle::all(), Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreVehicleRequest  $request
     * @return JsonResponse
     */
    public function store(StoreVehicleRequest $request):JsonResponse
    {
        DB::beginTransaction();
        try{

            $vehicle = Vehicle::create([
                'plate' => $request->get('plate'),
                'description' => $request->get('description')
            ]);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($vehicle, Response::HTTP_CREATED);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id):JsonResponse
    {
        $vehicle = Vehicle::find($id);

        if($vehicle == null){
            return response()->json([
                'success'   => false,
                'message'   => 'No Found',
                'data'      => null
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($vehicle, Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id):JsonResponse
    {
        DB::beginTransaction();
        try{

            $vehicle = Vehicle::whereId($id)->update([
                'plate' => $request->get('plate'),
                'description' => $request->get('description')
            ]);

        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json($vehicle, Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id):JsonResponse
    {
        DB::beginTransaction();
        try{
            $vehicle = Vehicle::find($id);
            $vehicle->delete();

            DB::commit();
        }catch (\Exception $e){
            DB::rollback();

            app()->hasDebugModeEnabled()
                ? $message = $e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'success'   => true,
            'message'   => 'Ok',
            'data'      => null
        ],Response::HTTP_OK);
    }
}
