# AgentBuff KostCloud — Design System

> Token & aturan UI untuk landing SaaS + panel owner/admin KostCloud.
> Arah visual: **trust emerald**, cloud/property management, gelap/terang, mobile-friendly.

## 1. Direction

| Aspek | Keputusan | Alasan |
|---|---|---|
| **Style** | Marketing landing yang beratmosfer + dashboard data-dense | Landing menjual produk SaaS; panel fokus operasional kos |
| **Mode** | Light default; dark mode penuh | Owner sering kerja malam; toggle tersimpan di `localStorage` |
| **Brand** | Emerald / teal trust + aksen soft | Asosiasi “aman, rapi, cloud” — bukan purple-AI klise |
| **Motion** | Scroll-reveal ringan, hover scale CTA, particle hero | Presence tanpa mengganggu; hormati `prefers-reduced-motion` |

## 2. Color tokens

Brand per-owner bisa diganti (emerald / teal / blue / indigo / violet / rose / amber / …) lewat CSS variables `--c-em-*` yang memetakan ulang utilitas Tailwind “emerald”.

### Light (default)
| Token | Nilai | Pakai |
|---|---|---|
| `--color-primary` | `#059669` (emerald-600) | CTA, sidebar aktif, fokus |
| `--color-primary-soft` | `#ecfdf5` | Badge, chip, hover ring |
| `--color-surface` | `#ffffff` | Background utama |
| `--color-surface-muted` | `#f9fafb` | Strip / section |
| `--color-text` | `#111827` | Body |
| `--color-text-muted` | `#6b7280` | Secondary |
| `--color-border` | `#e5e7eb` | Divider, card border |
| `--color-danger` | `#dc2626` | Hapus / error |
| `--color-warning` | `#d97706` | Maintenance / reminder |

### Dark
Surface gelap netral (`#0f172a` family), primary emerald lebih terang untuk kontras AA, border `rgba(255,255,255,0.10)`.

## 3. Typography

```css
--font-sans: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
```

- Landing: heading extrabold, tracking-tight; body 16–18px relaxed.
- Panel: tabular numbers untuk harga/invoice; label form 14px medium.

## 4. Spacing & radius

- Spacing base 4px (Tailwind scale).
- Radius: `rounded-xl` (12) untuk input/CTA; `rounded-2xl` untuk modal & card panel.
- Touch target ≥ 44px di mobile (sidebar off-canvas, tombol autentikasi).

## 5. Komponen kunci

| Surface | Pola |
|---|---|
| **Landing hero** | Satu komposisi: brand + headline + 1 kalimat + CTA + mockup dashboard |
| **Auth** | Modal di landing — Google untuk owner; email/password khusus admin |
| **Panel** | Sidebar emerald + content putih/gelap; tabel overflow-x; modal Alpine |
| **MCP panel** | URL server + bearer token + contoh `mcp.json` |

## 6. Accessibility

- Status kamar tidak hanya warna (label teks: available / occupied / maintenance).
- Fokus ring jelas pada CTA emerald.
- Dark mode menjaga kontras teks muted.
