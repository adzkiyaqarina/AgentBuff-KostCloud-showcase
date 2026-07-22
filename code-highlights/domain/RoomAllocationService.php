<?php

/**
 * RoomAllocationService (excerpt) — satu jalur penempatan penyewa ke kamar.
 *
 * Dipakai transaksi manual, verifikasi pembayaran, dan MCP create-transaksi
 * supaya okupansi tidak terduplikasi. WAJIB dipanggil di dalam DB::transaction
 * milik pemanggil: gagal penempatan → rollback seluruh pembayaran.
 *
 * Highlight:
 * 1. Periode sewa CHAINING — perpanjangan menyambung dari akhir masa aktif.
 * 2. Reaktivasi pivot histori (UNIQUE kamar+user) alih-alih insert baru.
 * 3. Status kamar capacity-aware: occupied hanya bila penuh.
 */
final class RoomAllocationService
{
    /**
     * @return array{start: \DateTimeInterface, end: \DateTimeInterface}
     */
    public function computeRentalPeriod(int $tenantId, int $months, callable $latestVerifiedEnd): array
    {
        $currentEnd = $latestVerifiedEnd($tenantId); // max(period_end_date) status verified
        $base = ($currentEnd && $currentEnd > new DateTimeImmutable('now'))
            ? $currentEnd
            : new DateTimeImmutable('now');

        return [
            'start' => $base,
            'end' => $base->modify("+{$months} months"),
        ];
    }

    /**
     * @return array{ok: bool, error?: string}
     */
    public function assignRoomAfterPayment(
        int $roomId,
        int $tenantId,
        DateTimeInterface $checkIn,
        ?DateTimeInterface $leaseEnd,
        object $room // kamar + tipe (capacity)
    ): array {
        $isActiveOccupant = $room->occupants()->where('user.id', $tenantId)->exists();

        if (! $isActiveOccupant) {
            if (! $room->hasAvailableSlot()) {
                $capacity = $room->roomType?->capacity ?? 1;

                return ['ok' => false, 'error' => "Kamar sudah penuh (kapasitas {$capacity} orang)."];
            }

            // UNIQUE(kamar_id, user_id): histori checkout → reaktivasi, bukan insert.
            if ($room->hasOccupancyHistory($tenantId)) {
                $room->reactivateOccupant($tenantId, $checkIn);
            } else {
                $room->occupants()->attach($tenantId, ['check_in_date' => $checkIn]);
            }
        }

        $room->refresh();
        $room->update([
            'status' => $room->isFull() ? 'occupied' : 'available',
            'lease_end_date' => $leaseEnd ?? $room->lease_end_date,
        ]);

        return ['ok' => true];
    }
}
