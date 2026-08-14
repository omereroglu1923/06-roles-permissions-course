<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'due_date',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        self::addGlobalScope(function (Builder $query) {
            if (Auth::check() && !Auth::user()->is_admin) {
                $query->where('user_id', Auth::id());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
