<?php

namespace App\Traits;

use App\Services\HomeContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

trait HasHomeScope
{
    public static function bootHasHomeScope(): void
    {
        static::addGlobalScope('home', function (Builder $builder) {
            $homeId = app(HomeContext::class)->getCurrentHomeId();
            if ($homeId) {
                $builder->where(static::getHomeIdColumn(), $homeId);
            } else {
                // If no home is selected, and we are not in a context where it's allowed,
                // we might want to return no results or all results.
                // For a multi-tenant app, usually we want to return nothing if no tenant is set.
                $builder->whereRaw('1 = 0');
            }
        });

        static::creating(function (Model $model) {
            $homeId = app(HomeContext::class)->getCurrentHomeId();
            if ($homeId && !isset($model->{static::getHomeIdColumn()})) {
                $model->{static::getHomeIdColumn()} = $homeId;
            }
        });
    }

    protected static function getHomeIdColumn(): string
    {
        return 'home_id';
    }
}
