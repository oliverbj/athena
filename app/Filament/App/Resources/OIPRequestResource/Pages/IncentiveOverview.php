<?php

namespace App\Filament\App\Resources\OIPRequestResource\Pages;

use App\Filament\App\Resources\OIPRequestResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Concerns\HasTabs;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Models\OIPRequest;

class IncentiveOverview extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use HasTabs;

    protected static string $resource = OIPRequestResource::class;

    protected static string $view = 'filament.app.resources.o-i-p-request-resource.pages.incentive-overview';

    protected static ?string $navigationGroup = 'Tools';
    
    /**
     * Get table
     * 
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(OIPRequest::query())
            ->columns([
                TextColumn::make('name'),
            ]);
    }

    /**
     * Get tabs
     */
    public function getTabs(): array
    {
        $tabs = [];
        return [
            'Hamburg' => Tab::make('Hamburg'),
        ];
    }
}
