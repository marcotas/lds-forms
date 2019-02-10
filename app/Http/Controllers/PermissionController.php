<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionController extends Controller
{
    public function index()
    {
        return DataResource::collection(
            QueryBuilder::for(Permission::class)->get()
        );
    }
}
