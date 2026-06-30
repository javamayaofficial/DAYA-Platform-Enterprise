# WHATSAPP NOTIFICATIONS

## Tujuan
- Dokumen ini menjelaskan setup dasar notifikasi WhatsApp DAYA Platform menggunakan Fonnte.
- Implementasi saat ini masih berupa lapisan integrasi reusable dan belum diikat ke flow bisnis tertentu karena codebase aktif belum memiliki field nomor WhatsApp yang stabil lintas modul.

## Konfigurasi Environment
- `WHATSAPP_MODE=off` untuk menonaktifkan pengiriman.
- `WHATSAPP_MODE=log` untuk mencatat payload ke `storage/logs/whatsapp.log` tanpa kirim ke Fonnte.
- `WHATSAPP_MODE=fonnte` untuk mengirim pesan ke API Fonnte.
- `WHATSAPP_DEFAULT_COUNTRY_CODE=62` untuk normalisasi nomor lokal.
- `WHATSAPP_ADMIN_TARGETS=` daftar nomor admin dipisahkan koma untuk menerima notifikasi internal.
- `WHATSAPP_EVENT_ADMIN_CREATOR_REGISTRATION=1` untuk mengaktifkan WA admin saat ada pengajuan Creator baru.
- `WHATSAPP_EVENT_ADMIN_CREATOR_REVIEW=1` untuk mengaktifkan WA admin saat status review Creator berubah.
- `WHATSAPP_FONNTE_ENDPOINT=https://api.fonnte.com/send`
- `WHATSAPP_FONNTE_TOKEN=` token device Fonnte.
- `WHATSAPP_FONNTE_TIMEOUT=15`
- `WHATSAPP_FONNTE_TYPING=0`
- `WHATSAPP_FONNTE_CONNECT_ONLY=1`

## Format Target
- `WHATSAPP_ADMIN_TARGETS` dapat diisi dengan nomor seperti `0812xxxx`, `62812xxxx`, atau `+62812xxxx`.
- Service akan membersihkan spasi, tanda hubung, tanda kurung, dan karakter non-angka lain sebelum dikirim.
- Jika nomor diawali `0`, service akan mengubahnya memakai `WHATSAPP_DEFAULT_COUNTRY_CODE`.
- Target yang terlalu pendek/panjang atau tidak bisa dinormalisasi akan ditolak lebih awal agar tidak mengirim request yang sia-sia ke Fonnte.

## Service
- Service inti berada di `app/core/Notifications/WhatsAppNotifier.php`.
- Method utama: `send(string $target, string $message, array $options = []): array`

## Flow Yang Sudah Tersambung
- `CreatorService::register()` mengirim notifikasi ke admin saat ada pengajuan Creator baru.
- `CreatorService::review()` mengirim notifikasi ke admin saat status review Creator diperbarui.
- Kedua flow menggunakan target dari `WHATSAPP_ADMIN_TARGETS`.
- Setiap flow bisa diaktifkan/nonaktifkan terpisah lewat `WHATSAPP_EVENT_ADMIN_CREATOR_REGISTRATION` dan `WHATSAPP_EVENT_ADMIN_CREATOR_REVIEW`.
- Jika notifikasi gagal, flow bisnis utama tetap berjalan dan kegagalan dicatat melalui log/error log.

## Opsi Payload Yang Didukung
- `countryCode`
- `typing`
- `connectOnly`
- `schedule`
- `delay`
- `url`
- `filename`

## Contoh Pemakaian
```php
use App\Core\Notifications\WhatsAppNotifier;

$notifier = new WhatsAppNotifier();
$notifier->send('081234567890', 'Halo, ini notifikasi DAYA Platform.');
```

## Logging
- Mode `off` mencatat `WHATSAPP_SKIPPED`
- Mode `log` mencatat `WHATSAPP_LOGGED`
- Mode `fonnte` mencatat `FONNTE_SENT` atau `FONNTE_FAILED`
- Log file: `storage/logs/whatsapp.log`

## Catatan Aktivasi
- Jangan aktifkan `WHATSAPP_MODE=fonnte` sebelum token device tersedia dan device Fonnte berstatus connected.
- Isi `WHATSAPP_ADMIN_TARGETS` sebelum mengharapkan notifikasi admin terkirim.
- Nonaktifkan event tertentu di env jika Anda hanya ingin sebagian flow admin yang live.
- Untuk nomor admin, gunakan nomor personal biasa, bukan link atau teks bebas.
- Jangan pernah memanggil API Fonnte langsung dari frontend karena token harus tetap rahasia.
- Saat flow bisnis yang memiliki nomor WhatsApp sudah final, integrasikan pemanggilan service ini di layer service modul terkait agar pola thin controller tetap terjaga.
