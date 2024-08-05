<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

abstract class AbstractQuery implements QueryInterface
{
    protected string $connection = 'cargowise';

    abstract protected function getQuery(): string;

    public function execute(array $params = []): array
    {
        $query = $this->getQuery();
        $bindings = array_merge($this->getDefaultParams(), $params);

        if (empty($bindings)) {
            return DB::connection($this->connection)->select($query);
        }

        return DB::connection($this->connection)->select($query, $bindings);
    }

    public function getDefaultParams(): array
    {
        return [];
    }

    public function transformResults(array $results): array
    {
        // Default implementation: return results as-is
        return $results;
    }
}