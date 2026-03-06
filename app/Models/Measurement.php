<?php

namespace App\Models;

use App\Traits\HasHomeScope;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasHomeScope;

    protected $fillable = ['home_id', 'location_id', 'label', 'value', 'unit'];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Location, $this>
     */
    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
