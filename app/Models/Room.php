<?php

namespace App\Models;

use App\Traits\HasHomeScope;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasHomeScope;

    protected $fillable = ['home_id', 'floor_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Floor, $this>
     */
    public function floor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Location, $this>
     */
    public function locations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Location::class);
    }
}
