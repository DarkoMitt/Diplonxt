<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id', 'receiver_id', 'thesis_id', 'message', 'read_at'];
    protected function casts(): array { return ['read_at' => 'datetime']; }
    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'receiver_id'); }
    public function thesis(): BelongsTo { return $this->belongsTo(Thesis::class); }
}
