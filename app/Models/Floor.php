<?php

namespace App\Models;

use App\Traits\HasHomeScope;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasHomeScope;

    protected $fillable = ['home_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Home, $this>
     */
    public function home(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Home::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Room, $this>
     */
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }
}
