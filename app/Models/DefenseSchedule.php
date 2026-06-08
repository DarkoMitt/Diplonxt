<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DefenseSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['thesis_id', 'scheduled_at', 'location', 'committee_members', 'notes'];
    protected function casts(): array { return ['scheduled_at' => 'datetime', 'committee_members' => 'array']; }
    public function thesis(): BelongsTo { return $this->belongsTo(Thesis::class); }
}
