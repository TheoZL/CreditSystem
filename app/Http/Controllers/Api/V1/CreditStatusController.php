<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CreditStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CreditStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        return response()->json(CreditStatus::all(),Response::HTTP_OK);
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
        try{

            $creditStatus = CreditStatus::create([
                'name' => $request->get('name'),
            ]);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($creditStatus, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id):JsonResponse
    {
        $creditStatus = CreditStatus::find($id);

        if($creditStatus == null){
            return response()->json([
                'success'   => false,
                'message'   => 'No Found',
                'data'      => null
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($creditStatus, Response::HTTP_OK);
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

            $creditStatus = CreditStatus::whereId($id)->update([
                'name' => $request->get('name'),
            ]);

        }catch (\Exception $e){
            DB::rollBack();

            app()->hasDebugModeEnabled()
                ? $message =$e->getMessage()
                : $message = __('app.internal_error');

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json($creditStatus, Response::HTTP_OK);
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
            $creditStatus = CreditStatus::find($id);
            $creditStatus->delete();

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
