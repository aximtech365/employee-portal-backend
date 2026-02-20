<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Document::with(['category', 'department', 'uploader']);

        if ($user->isAdmin()) {
        } elseif ($user->isManager()) {
            $query->where(function ($q) use ($user) {
                $q->where('access_level', 'public')
                    ->orWhere(function ($sq) use ($user) {
                        $sq->where('department_id', $user->department_id)
                            ->whereIn('access_level', ['public', 'department']);
                    })
                    ->orWhere(function ($sq) use ($user) {
                        $sq->where('access_level', 'private')
                            ->where('uploaded_by', $user->id);
                    });
            });
        } else {
            $query->where('access_level', 'public');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $documents = $query->latest()->get();

        return response()->json([
            'message' => 'Documents retrieved successfully',
            'count' => $documents->count(),
            'documents' => DocumentResource::collection($documents),
        ]);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        Gate::authorize('create', Document::class);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        $document = Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'category_id' => $request->category_id,
            'department_id' => $request->department_id,
            'uploaded_by' => $request->user()->id,
            'access_level' => $request->access_level,
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => new DocumentResource($document->load(['category', 'department', 'uploader'])),
        ], 201);
    }

    public function show(Document $document): JsonResponse
    {
        Gate::authorize('view', $document);

        return response()->json([
            'document' => new DocumentResource($document->load(['category', 'department', 'uploader'])),
        ]);
    }

    public function update(UpdateDocumentRequest $request, Document $document): JsonResponse
    {
        Gate::authorize('update', $document);

        $document->update($request->only([
            'title',
            'description',
            'category_id',
            'access_level',
        ]));

        return response()->json([
            'message' => 'Document updated successfully',
            'document' => new DocumentResource($document->load(['category', 'department', 'uploader'])),
        ]);
    }

    public function destroy(Document $document): JsonResponse
    {
        Gate::authorize('delete', $document);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json([
            'message' => 'Document deleted successfully',
        ]);
    }

    public function download(Request $request, Document $document)
    {
        Gate::authorize('view', $document);

        $document->incrementDownloadCount();

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
