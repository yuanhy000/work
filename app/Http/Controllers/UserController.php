<?php

namespace App\Http\Controllers;

use App\Chinese_zodiac;
use App\Constellation;
use App\Http\Resources\ConstellationResource;
use App\Http\Resources\ZodiacResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getInfo(Request $request)
    {

    }

    public function getZodiac(Request $request)
    {
        $zodiac = Chinese_zodiac::all();
        return ZodiacResource::collection($zodiac);
    }

    public function getConstellation(Request $request)
    {
        $zodiac = Constellation::all();
        return ConstellationResource::collection($zodiac);
    }
}
