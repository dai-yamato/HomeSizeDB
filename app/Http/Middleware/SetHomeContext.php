<?php

namespace App\Http\Middleware;

use App\Services\HomeContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetHomeContext
{
    public function __construct(protected HomeContext $homeContext)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($request->has('switch_home')) {
            $this->homeContext->setCurrentHomeId((int) $request->query('switch_home'));
        }

        if ($user && !$this->homeContext->getCurrentHomeId()) {
            $firstHome = $user->homes()->first();
            if ($firstHome) {
                $this->homeContext->setCurrentHomeId($firstHome->id);
            }
        }

        return $next($request);
    }
}
