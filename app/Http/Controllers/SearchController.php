<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function user(Request $request)
    {
        $searchContent = $request->get('content');
        $users = User::search($searchContent)->paginate(16);

        return new UserCollection($users);
    }
}
