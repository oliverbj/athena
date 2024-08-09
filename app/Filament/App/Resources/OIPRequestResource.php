<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\OIPRequestResource\Pages;
use App\Filament\App\Resources\OIPRequestResource\RelationManagers;
use App\Models\OIPRequest;
use App\Models\OIPRequestField;
use App\Enums\OIPBusinessType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Services\OIPBusinessTypeFieldService;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\HtmlString;

class OIPRequestResource extends Resource
{
    protected static ?string $model = OIPRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'user';

    // Add this line to set the parent navigation group
    protected static ?string $navigationGroup = 'Tools';




    public static function getModelLabel(): string
    {
        return 'OIP Request';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ...OIPBusinessTypeFieldService::getCommonFields(),
                ...self::getDynamicFields(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
                ->columns([
                    Tables\Columns\TextColumn::make('business_type')
                        ->formatStateUsing(function ($state) {
                            $name = OIPBusinessType::from($state)->name;
                            return str_replace('_', ' ', ucwords(strtolower($name), '_'));
                        }),
                        Tables\Columns\TextColumn::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'secondary',
                        })
                        ->formatStateUsing(fn (string $state): string => ucfirst($state))
                        ->extraAttributes(fn (OIPRequest $record): Array => [ 
                            'x-tooltip.html' => new HtmlString(),
                            'x-tooltip.raw' => match ($record->status) {
                                'rejected' => new HtmlString(
                                    "<strong>Comment:</strong> {$record->reject_reason}<br>" . // Comment
                                    "<strong>Rejected By:</strong> {$record->statusUser->name}, at {$record->status_updated_at}"
                                ),
                                'rejected' => new HtmlString(
                                    "<strong>Approved By:</strong> {$record->statusUser->name}, at {$record->status_updated_at}"
                                ),
                                'pending' => $record->comment ? new HtmlString(
                                    "<strong>Comment:</strong> {$record->comment}"
                                ) : "No comments provided",
                                default => "No comments provided",
                            },
                        ])
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('origin')->placeholder('-')->toggleable(),
                    Tables\Columns\TextColumn::make('destination')->placeholder('-')->toggleable(),
                    Tables\Columns\TextColumn::make('mode')
                        ->badge()
                        ->placeholder('-')
                        ->toggleable()
                        ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('expire_at')
                        ->dateTime()
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('user.name')
                        ->label('Created By')
                        ->toggleable(),
                ])
            ->filters([
                Tables\Filters\SelectFilter::make('business_type')
                        ->options(function() {
                            return collect(OIPBusinessType::cases())->mapWithKeys(function ($type) {
                                return [$type->value => str_replace('_', ' ', ucwords(strtolower($type->name), '_'))];
                            })->toArray();
                        }),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        // Add more status options as needed
                    ]),
            ])
            ->actions([
                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (OIPRequest $record): bool => $record->status === 'pending')
                        ->action(function (OIPRequest $record) {
                            $record->update(['status' => 'approved', 'status_updated_by' => auth()->id(), 'status_updated_at' => now()]);
                        }),
                        Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (OIPRequest $record): bool => $record->status === 'pending')
                        ->form([
                            Forms\Components\Textarea::make('reason')
                                ->label('Rejection Reason')
                                ->required(),
                        ])
                        ->action(function (OIPRequest $record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'reject_reason' => $data['reason'],
                                'status_updated_by' => auth()->id(), 
                                'status_updated_at' => now()
                            ]);
                        }),
                    Tables\Actions\EditAction::make()
                        ->visible(fn (OIPRequest $record): bool => $record->status === 'pending'),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (OIPRequest $record): bool => $record->status === 'pending'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (OIPRequest $record): bool => $record->status === 'pending'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOIPRequests::route('/'),
            'incentive' => Pages\IncentiveOverview::route('incentive-overview'),
        ];
    }

 
    private static function getDynamicFields(): array
    {
        return [
            Forms\Components\Grid::make()
                ->schema(function (Forms\Get $get) {
                    $businessType = $get('business_type');
                    return $businessType
                        ? OIPBusinessTypeFieldService::getBusinessTypeFields(OIPBusinessType::from($businessType))
                        : [];
                })
                ->columns(2)
        ];
    }

     
}
