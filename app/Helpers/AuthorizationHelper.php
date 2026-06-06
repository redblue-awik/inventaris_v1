<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthorizationHelper
{
    /**
     * Check if user is Admin
     */
    public static function isAdmin()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Check if user is Gudang
     */
    public static function isGudang()
    {
        return Auth::check() && Auth::user()->role === 'gudang';
    }

    /**
     * Check if user is Staff
     */
    public static function isStaff()
    {
        return Auth::check() && Auth::user()->role === 'staff';
    }

    /**
     * Check if user is Admin or Gudang
     */
    public static function isAdminOrGudang()
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'gudang']);
    }

    /**
     * Check if user can manage barang (Admin & Gudang only)
     */
    public static function canManageBarang()
    {
        return self::isAdminOrGudang();
    }

    /**
     * Check if user can manage kategori (Admin only)
     */
    public static function canManageKategori()
    {
        return self::isAdmin();
    }

    /**
     * Check if user can manage supplier (Admin only)
     */
    public static function canManageSupplier()
    {
        return self::isAdmin();
    }

    /**
     * Check if user can manage user (Admin only)
     */
    public static function canManageUser()
    {
        return self::isAdmin();
    }

    /**
     * Check if user can approve permintaan (Admin & Gudang only)
     */
    public static function canApprovePermintaan()
    {
        return self::isAdminOrGudang();
    }

    /**
     * Check if user can create mutasi barang (Admin & Gudang only)
     */
    public static function canCreateMutasi()
    {
        return self::isAdminOrGudang();
    }

    /**
     * Check if user can view all permintaan (Admin & Gudang only)
     */
    public static function canViewAllPermintaan()
    {
        return self::isAdminOrGudang();
    }

    /**
     * Check if user can view laporan (Admin only)
     */
    public static function canViewLaporan()
    {
        return self::isAdmin();
    }

    /**
     * Check if user can edit permintaan (only pending requests, Staff can only edit their own)
     */
    public static function canEditPermintaan($permintaan)
    {
        if ($permintaan->status !== 'menunggu') {
            return false;
        }

        // Admin & Gudang can edit any pending permintaan
        if (self::isAdminOrGudang()) {
            return true;
        }

        // Staff can only edit their own pending permintaan
        if (self::isStaff() && Auth::user()->id === $permintaan->permohonan_id) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete permintaan (only pending requests)
     */
    public static function canDeletePermintaan($permintaan)
    {
        if ($permintaan->status !== 'menunggu') {
            return false;
        }

        // Admin & Gudang can delete any pending permintaan
        if (self::isAdminOrGudang()) {
            return true;
        }

        // Staff can only delete their own pending permintaan
        if (self::isStaff() && Auth::user()->id === $permintaan->permohonan_id) {
            return true;
        }

        return false;
    }
}
