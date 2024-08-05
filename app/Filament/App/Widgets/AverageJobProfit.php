<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Queries\ShipmentProfileReportQuery;
use Filament\Forms;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Collection;

class AverageJobProfit extends BaseWidget
{
    public ?string $jobBranch = null;
    
    protected function getStats(): array
    {
        $query = new ShipmentProfileReportQuery();
        $results = collect($query->execute());

        $avgProfit = $this->calculateAverageJobProfit($results);

        return [
            Card::make('Average Job Profit', number_format($avgProfit, 2))
                ->description($this->jobBranch ?? 'All Branches')
                ->descriptionIcon('heroicon-s-currency-dollar'),
        ];
    }



    private function calculateAverageJobProfit(Collection $data): float
    {
        $filteredData = $this->jobBranch
            ? $data->where('job_branch', $this->jobBranch)
            : $data;
        return 0.00;
        return $filteredData->avg('job_profit');
    }

}
