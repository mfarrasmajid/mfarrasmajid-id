# Panduan Keamanan Website Portfolio

## Ringkasan Implementasi Security Measures

Dokumen ini menjelaskan langkah-langkah keamanan yang telah diimplementasikan pada website portfolio ini untuk melindungi dari ancaman web yang umum.

---

## 1. Validasi & Sanitasi Input ✅

### Implementasi:
- **File**: `email-templates/security-functions.php`
- Semua input user divalidasi dan disanitasi menggunakan `htmlspecialchars()` dengan flag `ENT_QUOTES` dan encoding UTF-8
- Fungsi `sanitizeInput()` mencegah XSS (Cross-Site Scripting) attacks
- Validasi khusus untuk:
  - Email addresses (mencegah email header injection)
  - Nomor telepon (hanya karakter yang diizinkan)
  - Panjang input (mencegah buffer overflow)

### File yang Diupdate:
- `email-templates/contact-form.php`
- `email-templates/contact-form-budget.php`
- `email-templates/subscribe-newsletter.php`

---

## 2. Proteksi Email Header Injection ✅

### Implementasi:
- Fungsi `validateEmail()` memvalidasi format email menggunakan `filter_var()` dengan `FILTER_VALIDATE_EMAIL`
- Mendeteksi dan memblokir karakter berbahaya: `\r`, `\n`, `%0a`, `%0d`, `Content-Type:`, `bcc:`, `cc:`, `to:`
- Mencegah penyerang menambahkan header email tambahan

### Contoh Validasi:
```php
$from = validateEmail($_POST['email']);
if (!$from) {
    sendJsonResponse('alert-danger', 'Invalid email address!');
}
```

---

## 3. Keamanan Kredensial SMTP ✅

### Implementasi:
- **File**: `email-templates/config.php.example`
- Kredensial SMTP dipindahkan ke file konfigurasi terpisah
- File `config.php` ditambahkan ke `.gitignore` untuk mencegah commit ke repository
- Menggunakan PHP constants untuk menyimpan kredensial
- File `.htaccess` di `email-templates/` mencegah akses langsung ke `config.php`

### Setup:
1. Copy `config.php.example` menjadi `config.php`
2. Isi dengan kredensial SMTP yang sebenarnya
3. Pastikan `config.php` tidak di-commit ke Git

```php
// email-templates/config.php
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USERNAME', 'your-username');
define('SMTP_PASSWORD', 'your-secure-password');
```

---

## 4. CSRF Token Protection ✅

### Implementasi:
- Session-based CSRF tokens menggunakan `random_bytes(32)`
- Token divalidasi menggunakan `hash_equals()` untuk mencegah timing attacks
- Setiap form submission harus menyertakan CSRF token

### Cara Menggunakan di Frontend:
```php
<?php
session_start();
require_once 'email-templates/security-functions.php';
$csrf_token = generateCsrfToken();
?>

<form method="POST" action="email-templates/contact-form.php">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    <!-- form fields lainnya -->
</form>
```

**PENTING**: Setiap HTML form yang mengirim ke PHP handlers harus menambahkan hidden field `csrf_token`.

---

## 5. Rate Limiting ✅

### Implementasi:
- Fungsi `checkRateLimit()` membatasi jumlah submission per IP address
- Default: 5 percobaan per 3600 detik (1 jam)
- Newsletter subscription: 3 percobaan per jam (lebih ketat)
- Data disimpan di `rate_limit.json` (otomatis dibersihkan)

### Konfigurasi:
```php
// Di config.php
define('RATE_LIMIT_MAX_ATTEMPTS', 5);
define('RATE_LIMIT_TIME_WINDOW', 3600);
```

---

## 6. Kontrol Akses Direktori ✅

### File `.htaccess` yang Dibuat:

#### Root `.htaccess`:
- Enforce HTTPS untuk semua request
- Security headers (CSP, X-Frame-Options, dll)
- Proteksi file sensitif
- Mencegah directory listing

#### `email-templates/.htaccess`:
- Deny akses langsung ke `config.php`, `security-functions.php`
- Proteksi file log dan rate limiting
- Disable directory listing

#### `bak/.htaccess`:
- Disable directory listing untuk folder backup
- Proteksi directory traversal

---

## 7. Content Security Policy (CSP) ✅

### Implementasi:
CSP headers ditambahkan di root `.htaccess`:

```apache
Header set Content-Security-Policy "default-src 'self'; 
    script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com; 
    style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; 
    font-src 'self' https://fonts.gstatic.com data:; 
    img-src 'self' data: https:;"
```

### Catatan:
- `'unsafe-inline'` dan `'unsafe-eval'` digunakan karena jQuery dan beberapa library memerlukan inline scripts
- Untuk keamanan lebih baik, pertimbangkan refactoring untuk menghapus inline scripts

---

## 8. Proteksi Open Redirect ✅

### Implementasi:
- Fungsi `validateRedirectUrl()` memvalidasi URL redirect
- Hanya mengizinkan relative URLs atau same-domain URLs
- Mencegah attacker mengarahkan user ke situs phishing

```php
$redirect_page_url = !empty($_POST['redirect']) ? 
    validateRedirectUrl($_POST['redirect']) : '';
```

---

## 9. Error Handling yang Aman ✅

### Implementasi:
- `display_errors` di-set ke `Off` di production
- Error logging enabled dengan fungsi `logError()`
- Error messages yang user-friendly tanpa mengekspos detail sistem
- Log file dilindungi dengan `.htaccess`

### PHP Settings di `.htaccess`:
```apache
php_flag display_errors Off
php_flag log_errors On
php_flag expose_php Off
```

---

## 10. Proteksi Directory Traversal ✅

### Implementasi:
- Rewrite rules di `.htaccess` mendeteksi pattern `../` dan `..\`
- Validasi path di backend jika ada file operations
- Semua direktori dilindungi dengan Options `-Indexes`

```apache
RewriteCond %{REQUEST_URI} (\.\.\/|\.\.\\)
RewriteRule .* - [F,L]
```

---

## 11. Security Headers Tambahan ✅

Headers yang ditambahkan:
- `X-Frame-Options: SAMEORIGIN` - Mencegah clickjacking
- `X-XSS-Protection: 1; mode=block` - XSS protection di browser
- `X-Content-Type-Options: nosniff` - Mencegah MIME sniffing
- `Referrer-Policy: strict-origin-when-cross-origin` - Kontrol referrer info
- `Permissions-Policy` - Disable fitur browser yang tidak digunakan

---

## 12. SSL/TLS Verification ✅

### Perubahan:
- `CURLOPT_SSL_VERIFYPEER` diubah dari `false` menjadi `true` di MailChimp integration
- Memastikan koneksi HTTPS terverifikasi dengan benar

---

## Checklist Setup untuk Production

### ⚠️ Wajib Dilakukan:

- [ ] Copy `email-templates/config.php.example` menjadi `config.php`
- [ ] Isi kredensial SMTP/MailChimp di `config.php`
- [ ] Update semua HTML forms dengan CSRF token field
- [ ] Test form submissions untuk memastikan CSRF token berfungsi
- [ ] Pastikan SSL certificate ter-install dan valid
- [ ] Verifikasi `.htaccess` aktif dan berfungsi
- [ ] Test rate limiting dengan multiple submissions
- [ ] Pastikan file `.gitignore` mencegah commit file sensitif
- [ ] Review dan adjust CSP headers sesuai kebutuhan external resources
- [ ] Setup monitoring untuk error logs

### 🔒 Security Best Practices:

1. **Update Dependencies Reguler**
   - Check untuk update PHPMailer dan library lainnya
   - Monitor security advisories

2. **Backup Reguler**
   - Backup database dan files secara berkala
   - Simpan backup di lokasi terpisah yang aman

3. **Monitoring**
   - Monitor `error.log` untuk suspicious activities
   - Review `rate_limit.json` untuk detection spam attempts

4. **Password Management**
   - Gunakan strong passwords untuk SMTP
   - Rotasi credentials secara berkala
   - Jangan share credentials via email/chat

5. **Server Configuration**
   - Pastikan PHP version up-to-date
   - Disable dangerous PHP functions jika tidak digunakan
   - Configure firewall untuk additional protection

---

## Testing Security Measures

### 1. Test CSRF Protection:
```bash
# Kirim request tanpa CSRF token - harus ditolak
curl -X POST https://yourdomain.com/email-templates/contact-form.php \
  -d "email=test@example.com&name=Test&comment=Hello"
```

### 2. Test Rate Limiting:
```bash
# Kirim 6 request dalam waktu singkat - request ke-6 harus ditolak
for i in {1..6}; do
  curl -X POST https://yourdomain.com/email-templates/contact-form.php \
    -d "email=test@example.com&name=Test&comment=Hello&csrf_token=xxx"
done
```

### 3. Test Email Header Injection:
```bash
# Email dengan karakter berbahaya - harus ditolak
curl -X POST https://yourdomain.com/email-templates/contact-form.php \
  -d "email=test@example.com%0aBcc:hacker@evil.com&name=Test&comment=Hello"
```

### 4. Test XSS Protection:
```bash
# Input dengan script tags - harus di-sanitize
curl -X POST https://yourdomain.com/email-templates/contact-form.php \
  -d "email=test@example.com&name=<script>alert('XSS')</script>&comment=Hello"
```

---

## Troubleshooting

### Forms Tidak Berfungsi Setelah Update:

1. **CSRF Token Error**:
   - Pastikan session_start() dipanggil di halaman form
   - Tambahkan hidden field csrf_token di form
   - Clear browser cookies dan coba lagi

2. **Rate Limit Error**:
   - Tunggu 1 jam atau hapus `email-templates/rate_limit.json`
   - Adjust limit di `config.php`

3. **Email Tidak Terkirim**:
   - Check `email-templates/error.log` untuk details
   - Verifikasi kredensial SMTP di `config.php`
   - Test SMTP connection secara manual

4. **.htaccess Error 500**:
   - Check Apache error log
   - Pastikan mod_rewrite, mod_headers enabled
   - Comment out sections yang menyebabkan error

---

## Contact & Support

Jika menemukan security vulnerability atau butuh bantuan:
1. Jangan post di public issue tracker
2. Email langsung ke admin dengan detail vulnerability
3. Berikan waktu untuk patch sebelum public disclosure

---

## Changelog

### Version 1.0 - Security Audit Implementation
- ✅ Input validation & sanitization
- ✅ Email header injection protection
- ✅ CSRF token protection
- ✅ Rate limiting
- ✅ SMTP credential security
- ✅ Directory access control
- ✅ Content Security Policy
- ✅ Security headers
- ✅ Error handling
- ✅ HTTPS enforcement

---

**Last Updated**: November 2025
**Security Level**: Production Ready ✅
