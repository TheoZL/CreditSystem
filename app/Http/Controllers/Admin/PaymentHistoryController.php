<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\PaymentHistory;
use App\Models\PaymentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class PaymentHistoryController extends Controller
{

    /**
     * Show the Credit and his history Payment View
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showHistory(int $id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $credit = Credit::findOrFail($id);
        $paymentHistory = PaymentHistory::where('credit_id', '=', $id)->get();
        $paymentType = PaymentType::all();

        return view('admin.paymentHistory.index')
            ->with('credit',$credit)
            ->with('paymentHistory',$paymentHistory)
            ->with('paymentType',$paymentType);
    }


    /**
     * Function to apply Payment in Line
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function applyPayment(Request $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try{

            $credit_id = $request->get('credit_id');
            $amount = $request->get('amount');

            $payment = PaymentHistory ::create([
                'credit_id'=> $credit_id ,
                'day' => now('America/Costa_Rica'),
                'amount' => $amount ,
                'type_id' => $request->get('paymentType'),
            ]);

            $credit = Credit::findOrFail($id);
            $credit->balance = $credit->balance - $amount;
            $credit->balance == 0
                ? $credit->credit_status_id =2
                : $credit->credit_status_id =1;
            $credit->save();

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json(['message' => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Se realiza el Pago con Exito'],Response::HTTP_OK);
    }

    /**
     *
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updatePayment(Request $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try{
            $paymentHistory = PaymentHistory::findOrFail($id);
            $newAmount = $request->get('amount');
            $currentAmount = $paymentHistory->amount;
            $credit = Credit::findOrFail($paymentHistory->credit_id);

            $paymentHistory->day = $request->get('day');
            $paymentHistory->amount = $newAmount;
            $paymentHistory->type_id = $request->get('type_id');
            $paymentHistory->save();

            if($currentAmount > $newAmount){
                $credit->balance = $credit->balance - ($currentAmount -$newAmount );
            }elseif ($newAmount > $currentAmount){
                $credit->balance = $credit->balance + ($currentAmount - $newAmount );
            }
            $credit->save();

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json(['message' => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Se actualiza el pago'],Response::HTTP_OK);
    }


    /**
     * Delete a History Payment and
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deletePayment( int $id): JsonResponse
    {
        DB::beginTransaction();
        try{
            $paymentHistory = PaymentHistory::findOrFail($id);
            $credit = Credit::findOrFail($paymentHistory->credit_id);
            $credit->balance = $credit->balance + $paymentHistory->amount;
            $credit->save();

            $paymentHistory->delete();
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json(['message' => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Se elimina el Pago'],Response::HTTP_OK);
    }
}
