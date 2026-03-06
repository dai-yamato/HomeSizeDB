<?php

namespace App\Models;

use App\Traits\HasHomeScope;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasHomeScope;

    protected $fillable = ['home_id', 'room_id', 'name', 'icon', 'note'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Room, $this>
     */
    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Measurement, $this>
     */
    public function measurements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Measurement::class);
    }
}
