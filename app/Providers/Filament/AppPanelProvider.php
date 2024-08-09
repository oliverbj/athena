<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\Tenancy\EditTeamProfile;
use App\Filament\App\Pages\Tenancy\RegisterTeam;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Models\Team;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\MaxWidth;
use App\Filament\App\Widgets\AverageJobProfit;
use App\Filament\App\Resources\OIPRequestResource\Pages\IncentiveOverview;
use Filament\Navigation\NavigationItem;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('')
            ->colors([
                'primary' => Color::Green,
            ])
            ->topNavigation()
            ->navigationItems([

                NavigationItem::make('Incentive Overview')
                    ->url(fn (): string => IncentiveOverview::getUrl())
                    ->icon('heroicon-o-presentation-chart-line')
                    ->group('Tools')
                    ->sort(2)
                    
            ])
            ->maxContentWidth(MaxWidth::Full)
            ->spa()
            ->tenant(Team::class)
            ->tenantRoutePrefix('company')
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                AverageJobProfit::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
