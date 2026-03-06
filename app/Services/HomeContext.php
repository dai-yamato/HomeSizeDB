<?php

namespace App\Services;

use App\Models\Home;
use Illuminate\Support\Facades\Session;

class HomeContext
{
    private const SESSION_KEY = 'current_home_id';

    public function setCurrentHomeId(int $homeId): void
    {
        Session::put(self::SESSION_KEY, $homeId);
    }

    public function getCurrentHomeId(): ?int
    {
        return Session::get(self::SESSION_KEY);
    }

    public function getCurrentHome(): ?Home
    {
        $id = $this->getCurrentHomeId();
        return $id ? Home::find($id) : null;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
