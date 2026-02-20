<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function index(): JsonResponse
    {
        $departments = Department::all();

        return response()->json([
            'departments' => DepartmentResource::collection($departments),
        ]);
    }
}
