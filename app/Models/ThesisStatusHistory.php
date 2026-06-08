<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ThesisStatusHistory extends Model
{
    protected $fillable = ['thesis_id', 'changed_by', 'from_status', 'to_status', 'note'];
    public function thesis(): BelongsTo { return $this->belongsTo(Thesis::class); }
    public function changedBy(): BelongsTo { return $this->belongsTo(User::class, 'changed_by'); }
}
