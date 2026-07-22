<?php

/**
 * Tenant scope helpers — satu sumber kebenaran untuk "kos mana yang sedang
 * dikelola". Dipakai panel web (owner & admin) dan MCP tools.
 *
 * - Owner → dirinya sendiri
 * - Admin → owner yang menaungi (via profil admin.owner_id)
 * - Peran lain → null (tidak punya workspace manajemen)
 */
final class TenantScope
{
    public static function resolveOwnerId(object $user): ?int
    {
        if (($user->role ?? null) === 'owner') {
            return (int) $user->id;
        }

        if (($user->role ?? null) === 'admin') {
            return isset($user->adminProfile?->owner_id)
                ? (int) $user->adminProfile->owner_id
                : null;
        }

        return null;
    }

    public static function isManager(object $user): bool
    {
        return in_array($user->role ?? null, ['owner', 'admin'], true);
    }
}
