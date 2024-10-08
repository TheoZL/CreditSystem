<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Canton;
use App\Models\Distrit;
use App\Models\Province;
use Symfony\Component\HttpFoundation\Response;

class AddressDataController extends Controller
{

    public function getAllProvinces()
    {
        return response()->json(Province::all(),Response::HTTP_OK);
    }

    public function getAllCantons()
    {
        return response()->json(Canton::all(),Response::HTTP_OK);
    }

    public function getAllDistricts()
    {
        return response()->json(Distrit::all(),Response::HTTP_OK);
    }

    public function getAllInfo()
    {
        $provinces = Province::all();

        $response = [];

        foreach ($provinces as $province){

            $cantones = [];
            $cantons = $province->getCantons;

            foreach ($cantons as $canton){

                $districtos = [];
                $districts = $canton->getDistricts;

                foreach ($districts as $district)
                {
                    $distritValue = [
                        'id'=>$district->id,
                        'name' =>$district->name
                    ];
                    $districtos[] = $distritValue;
                }

                $cantonValue = [
                    'id'=> $canton->id,
                    "name" => $canton->name,
                    'districtos' => $districtos
                ];
                $cantones[] =$cantonValue;
            }

            $provinceValue =[
                'id' => $province->id,
                'name' => $province->name,
                'cantones'=> $cantones
            ];

            $response[] = $provinceValue;
        }

        return response()->json($response,Response::HTTP_OK);
    }
}
