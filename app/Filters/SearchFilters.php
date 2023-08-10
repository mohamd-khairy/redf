<?php

namespace App\Filters;

use Carbon\Carbon;
use Closure;

class SearchFilters
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        $query = $query->where('name', 'like', '%' . request('search') . '%');

        return $query;
    }
}
