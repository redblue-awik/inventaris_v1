<?php

namespace App\Providers;

use App\Helpers\AuthorizationHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade Directives untuk Role Checking
        Blade::if('admin', function () {
            return AuthorizationHelper::isAdmin();
        });

        Blade::if('gudang', function () {
            return AuthorizationHelper::isGudang();
        });

        Blade::if('staff', function () {
            return AuthorizationHelper::isStaff();
        });

        Blade::if('adminOrGudang', function () {
            return AuthorizationHelper::isAdminOrGudang();
        });

        Blade::if('canManageBarang', function () {
            return AuthorizationHelper::canManageBarang();
        });

        Blade::if('canManageKategori', function () {
            return AuthorizationHelper::canManageKategori();
        });

        Blade::if('canManageSupplier', function () {
            return AuthorizationHelper::canManageSupplier();
        });

        Blade::if('canApprovePermintaan', function () {
            return AuthorizationHelper::canApprovePermintaan();
        });

        Blade::if('canCreateMutasi', function () {
            return AuthorizationHelper::canCreateMutasi();
        });

        Blade::if('canViewAllPermintaan', function () {
            return AuthorizationHelper::canViewAllPermintaan();
        });

        Blade::if('canViewLaporan', function () {
            return AuthorizationHelper::canViewLaporan();
        });

        // Alias untuk helper
        if (!function_exists('auth_helper')) {
            function auth_helper() {
                return new AuthorizationHelper();
            }
        }
    }
}
