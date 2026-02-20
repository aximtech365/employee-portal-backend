<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'category_id',
        'department_id',
        'uploaded_by',
        'access_level',
        'download_count',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
}
