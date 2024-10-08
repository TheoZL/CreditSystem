<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Models\PaymentHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $credit_id
     * @return JsonResponse
     */
    public function index(int $credit_id):JsonResponse
    {
        return response()->json(PaymentHistory::all()->where('credit_id','=',$credit_id), Response::HTTP_OK);
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

            $paymentHistory = PaymentHistory::create([
                'credit_id' => $request->get('plate'),
                'day' => $request->get('day'),
                'amount' => $request->get('amount'),
                'type_id' => $request->get('type_id'),

            ]);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($paymentHistory, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id):JsonResponse
    {
        $paymentHistory = PaymentHistory::find($id);

        if($paymentHistory == null){
            return response()->json([
                'success'   => false,
                'message'   => 'No Found',
                'data'      => null
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($paymentHistory, Response::HTTP_OK);
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

            $paymentHistory = PaymentHistory::whereId($id)->update([
                'credit_id' => $request->get('plate'),
                'day' => $request->get('day'),
                'amount' => $request->get('amount'),
                'type_id' => $request->get('type_id'),
            ]);

        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json($paymentHistory, Response::HTTP_OK);
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
            $paymentHistory = PaymentHistory::find($id);
            $paymentHistory->delete();

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
