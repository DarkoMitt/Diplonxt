<?php

namespace App\Models;

use App\Enums\ThesisStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Thesis extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'professor_id', 'title', 'description', 'status', 'progress_percentage', 'current_phase', 'deadline', 'defense_date', 'is_archived'];

    protected function casts(): array
    {
        return ['status' => ThesisStatus::class, 'deadline' => 'date', 'defense_date' => 'datetime', 'is_archived' => 'boolean'];
    }

    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
    public function professor(): BelongsTo { return $this->belongsTo(User::class, 'professor_id'); }
    public function versions(): HasMany { return $this->hasMany(ThesisVersion::class)->latest('version_number'); }
    public function feedback(): HasMany { return $this->hasMany(Feedback::class)->latest(); }
    public function messages(): HasMany { return $this->hasMany(ChatMessage::class); }
    public function defenseSchedule(): HasOne { return $this->hasOne(DefenseSchedule::class); }
    public function statusHistory(): HasMany { return $this->hasMany(ThesisStatusHistory::class)->latest(); }
}
