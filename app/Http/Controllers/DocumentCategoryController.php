<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentCategoryResource;
use App\Models\DocumentCategory;
use Illuminate\Http\JsonResponse;

class DocumentCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = DocumentCategory::all();

        return response()->json([
            'categories' => DocumentCategoryResource::collection($categories),
        ]);
    }
}
