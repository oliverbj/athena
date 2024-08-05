<?php

namespace App\Queries;

use Illuminate\Support\Facades\Cache;

class ShipmentProfileReportQuery extends AbstractQuery
{
    protected function getQuery(): string
    {
        return "
            SELECT TOP 10 
                JH_JobNum
            FROM JobHeader
        ";
    }

    public function getDefaultParams(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Shipment Profile Report';
    }

    public function execute(array $params = []): array
    {
        $results = parent::execute($params);
        return $this->transformResults($results);
        /*
        $cacheKey = 'spr_query_' . md5(json_encode($params));
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($params) {
            $results = parent::execute($params);
            return $this->transformResults($results);
        });*/
    }

    public function transformResults(array $results): array
    {

        return collect($results)
            ->map(function ($row) {
                return [
                    'shipment_id' => $row->JH_JobNum,
                ];
            })
            ->toArray();
    }
}