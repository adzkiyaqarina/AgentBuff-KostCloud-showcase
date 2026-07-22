<?php

/**
 * KostCloudServer — katalog tool MCP (excerpt).
 * 14 tools: list/create/update/delete kamar & penyewa & transaksi + laporan.
 * Semua otomatis di-scope via InteractsWithOwner + diaudit via AuditsMcpActions.
 */
#[Name('AgentBuff KostCloud')]
#[Version('1.1.0')]
#[Instructions(
    'Semua tool di-scope ke kos milik bearer token. create-penyewa hanya mendata; '
    .'penempatan kamar WAJIB lewat create-transaksi (harus ada pembayaran). '
    .'Delete: kamar harus kosong; penyewa wajib reason & tidak boleh masih menghuni; '
    .'hapus transaksi tidak mengubah hunian.'
)]
class KostCloudServer extends Server
{
    protected array $tools = [
        DashboardSummaryTool::class,
        ListKamarTool::class,
        ListTipeKamarTool::class,
        CreateKamarTool::class,
        UpdateKamarStatusTool::class,
        DeleteKamarTool::class,
        DeleteTipeKamarTool::class,
        ListPenyewaTool::class,
        CreatePenyewaTool::class,
        DeletePenyewaTool::class,
        ListTransaksiTool::class,
        CreateTransaksiTool::class,
        DeleteTransaksiTool::class,
        GenerateLaporanTool::class,
    ];
}
