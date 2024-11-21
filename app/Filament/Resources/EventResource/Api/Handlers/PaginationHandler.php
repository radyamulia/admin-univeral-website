<?php

namespace App\Filament\Resources\EventResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\EventResource;

class PaginationHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = EventResource::class;

    public static $allowedSorts = ['created_at'];

    public function handler(Request $request)
    {
        $query = static::getEloquentQuery();
        $lang = $request->query('lang', 'id'); // Default language is English if not provided
        $model = static::getModel();

        $query = QueryBuilder::for($query->where('lang', $lang))
            ->defaultSort('-created_at')
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->paginate(10)
            // ->paginate(request()->query('per_page'))
            ->appends(request()->query());

        return static::getApiTransformer()::collection($query);
    }
}
