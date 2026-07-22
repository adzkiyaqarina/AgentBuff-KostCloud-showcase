<?php

/**
 * InteractsWithOwner — setiap tool MCP di-scope ke kos pemilik bearer token.
 * Owner → dirinya; admin → owner-nya. Agent AI tidak bisa menyentuh data kos lain.
 */
trait InteractsWithOwner
{
    protected function ownerId(/* MCP Request */ $request): ?int
    {
        $user = $request->user();

        if (! $user || ! method_exists($user, 'isManager') || ! $user->isManager()) {
            return null;
        }

        return $user->ownerId();
    }
}
