<?php

namespace App\Models;

use App\Enums\VersionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThesisVersion extends Model
{
    use HasFactory;
    protected $fillable = ['thesis_id', 'uploaded_by', 'version_number', 'file_path', 'original_file_name', 'mime_type', 'file_size', 'status', 'notes'];
    protected function casts(): array { return ['status' => VersionStatus::class]; }
    public function thesis(): BelongsTo { return $this->belongsTo(Thesis::class); }
    public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
    public function feedback(): HasMany { return $this->hasMany(Feedback::class); }
}
