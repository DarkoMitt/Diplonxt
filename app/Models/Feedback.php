<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedback';
    protected $fillable = ['professor_id', 'thesis_id', 'thesis_version_id', 'comment', 'student_reply', 'status', 'resolved_at'];
    protected function casts(): array { return ['resolved_at' => 'datetime']; }
    public function professor(): BelongsTo { return $this->belongsTo(User::class, 'professor_id'); }
    public function thesis(): BelongsTo { return $this->belongsTo(Thesis::class); }
    public function version(): BelongsTo { return $this->belongsTo(ThesisVersion::class, 'thesis_version_id'); }
}
