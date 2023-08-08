<?php

namespace App\Filters;

use Carbon\Carbon;
use Closure;

class SortFilters
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        /************ Sort filter ********* */
        if (in_array(request('sortCoulmn', 'id'), ['id', 'date'])) {

            $query = $query->orderBy(request('sortCoulmn', 'id'), request('sortDirection', 'desc'));
        } else {
            $query = $query->latest();
        }

        return $query;
    }
}
