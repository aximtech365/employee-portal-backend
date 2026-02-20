<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'file_size_formatted' => $this->formatFileSize($this->file_size),
            'category_id' => $this->category_id,
            'category' => new DocumentCategoryResource($this->whenLoaded('category')),
            'department_id' => $this->department_id,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'uploaded_by' => $this->uploaded_by,
            'uploader' => new UserResource($this->whenLoaded('uploader')),
            'access_level' => $this->access_level,
            'download_count' => $this->download_count,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}
