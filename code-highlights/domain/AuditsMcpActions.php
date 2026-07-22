<?php

/**
 * AuditsMcpActions — mutasi lewat AI Agent tetap teraudit & ternotifikasi.
 *
 * - Log masuk AdminActivityLog (dashboard owner)
 * - Notifikasi owner untuk aksi penting (boleh menyertakan nominal)
 * - Notifikasi admin hanya untuk operasional (TANPA nominal uang)
 */
trait AuditsMcpActions
{
    protected function logMcp($request, string $activityType, string $description, $model = null): void
    {
        $actor = $request->user();
        if (! $actor) {
            return;
        }

        LoggerService::log(
            $activityType,
            rtrim($description).' (via AI Agent)',
            $model,
            actor: $actor
        );
    }

    protected function notifyOwnerMcp(int $ownerId, string $title, string $message): void
    {
        Notification::create([
            'user_id' => $ownerId,
            'type' => 'info',
            'category' => 'system',
            'title' => $title,
            'message' => $message,
            'priority' => 'medium',
        ]);
    }

    /** Operasional saja — jangan sertakan nominal. */
    protected function notifyAdminsMcp(int $ownerId, string $title, string $message): void
    {
        $adminIds = User::where('role', 'admin')
            ->whereHas('adminProfile', fn ($q) => $q->where('owner_id', $ownerId))
            ->pluck('id');

        foreach ($adminIds as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'type' => 'info',
                'category' => 'system',
                'title' => $title,
                'message' => $message,
                'priority' => 'low',
            ]);
        }
    }
}
