<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        return response()->json(Credit::all() ,Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request):JsonResponse
    {
        DB::beginTransaction();
        try
        {
            $credit = Credit::create([
                'customer_id' => $request->get('customer_id'),
                'date' => $request->get('date'),
                'first_payment' => $request->get('first_payment'),
                'total_payment'  => $request->get('total_payment'),
                'route_id'  => $request->get('route_id'),
                'billing_schedule_id' => $request->get('billing_schedule_id'),
                'credit_status_id' => $request->get('credit_status_id'),
            ]);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($credit, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id):JsonResponse
    {
        $credit = Credit::find($id);

        if($credit == null){
            return response()->json([
                'success'   => false,
                'message'   => 'No Found',
                'data'      => null
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($credit, Response::HTTP_OK);
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

            $credit = Credit::whereId($id)->update([
                'customer_id' => $request->get('customer_id'),
                'date' => $request->get('date'),
                'first_payment' => $request->get('first_payment'),
                'total_payment'  => $request->get('total_payment'),
                'route_id'  => $request->get('route_id'),
                'billing_schedule_id' => $request->get('billing_schedule_id'),
                'credit_status_id' => $request->get('credit_status_id'),
            ]);

        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json($credit, Response::HTTP_OK);
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
            $credit = Credit::find($id);
            $credit->delete();

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
