# Email Templates - Panduan Setup

## Setup Konfigurasi

### 1. Buat File Konfigurasi

Copy file template ke file konfigurasi aktual:

```bash
cp config.php.example config.php
```

### 2. Edit Konfigurasi

Edit `config.php` dan isi dengan kredensial yang sebenarnya:

```php
// Email Configuration
define('RECEIVER_EMAIL', 'your-email@domain.com');
define('RECEIVER_NAME', 'Your Name');

// SMTP Configuration (jika menggunakan SMTP)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);

// MailChimp Configuration (jika menggunakan MailChimp)
define('MAILCHIMP_API_KEY', 'your-api-key');
define('MAILCHIMP_LIST_ID', 'your-list-id');
```

### 3. Pastikan File Permissions

```bash
chmod 600 config.php  # Hanya owner yang bisa read/write
```

## Fitur Keamanan

### ✅ Input Validation & Sanitization
- Semua input di-sanitize untuk mencegah XSS
- Email validation mencegah header injection
- Length validation untuk semua fields

### ✅ CSRF Protection
- Token validation untuk semua form submissions
- Session-based token generation

### ✅ Rate Limiting
- Maksimal 5 submissions per hour per IP untuk contact forms
- Maksimal 3 submissions per hour per IP untuk newsletter
- Otomatis cleanup old entries

### ✅ Error Handling
- Secure error logging ke `error.log`
- User-friendly error messages
- No system information disclosure

## Cara Menggunakan di Frontend

### Contact Form

```html
<?php
session_start();
require_once 'email-templates/security-functions.php';
$csrf_token = generateCsrfToken();
?>

<form method="POST" action="email-templates/contact-form.php">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    
    <input type="text" name="name" required>
    <input type="email" name="email" required>
    <input type="tel" name="phone">
    <textarea name="comment" required></textarea>
    
    <button type="submit">Send</button>
</form>
```

### Contact Form with Budget

```html
<form method="POST" action="email-templates/contact-form-budget.php">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    
    <input type="text" name="name" required>
    <input type="email" name="email" required>
    <input type="tel" name="phone">
    <input type="text" name="budget">
    <textarea name="comment" required></textarea>
    
    <button type="submit">Send</button>
</form>
```

### Newsletter Subscription

```html
<form method="POST" action="email-templates/subscribe-newsletter.php">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    
    <input type="text" name="name">
    <input type="email" name="email" required>
    
    <button type="submit">Subscribe</button>
</form>
```

## Testing

### Test Validasi Email
```php
$valid = validateEmail('test@example.com'); // Returns email
$invalid = validateEmail('test@example.com%0aBcc:'); // Returns false
```

### Test Rate Limiting
```php
$allowed = checkRateLimit(5, 3600); // true or false
```

### Test CSRF Token
```php
$token = generateCsrfToken();
$valid = validateCsrfToken($_POST['csrf_token']);
```

## Troubleshooting

### Forms tidak berfungsi

1. **Check error.log**:
   ```bash
   tail -f email-templates/error.log
   ```

2. **Verify CSRF token**:
   - Pastikan `session_start()` dipanggil sebelum `generateCsrfToken()`
   - Check browser cookies enabled

3. **Rate limit exceeded**:
   - Tunggu 1 jam atau hapus `rate_limit.json`
   - Adjust limit di `config.php`

### Email tidak terkirim

1. **SMTP Mode**:
   - Verify credentials di `config.php`
   - Check SMTP port dan host
   - Enable less secure apps (untuk Gmail) atau gunakan App Password

2. **Simple Mail Mode**:
   - Pastikan PHP `mail()` function enabled
   - Check server mail configuration

## File Structure

```
email-templates/
├── config.php.example      # Template konfigurasi
├── config.php             # Konfigurasi aktual (gitignored)
├── security-functions.php # Helper functions untuk security
├── contact-form.php       # Contact form handler
├── contact-form-budget.php # Contact form with budget
├── subscribe-newsletter.php # Newsletter subscription
├── .htaccess             # Directory protection
├── rate_limit.json       # Rate limiting data (auto-generated)
├── error.log             # Error log (auto-generated)
└── phpmailer/            # PHPMailer library
```

## Security Notes

⚠️ **PENTING**:
- Jangan commit `config.php` ke Git
- Gunakan HTTPS untuk production
- Monitor `error.log` secara berkala
- Update PHPMailer secara reguler
- Gunakan strong passwords untuk SMTP

## Changelog

### v1.0.0 - Security Enhancement
- ✅ Added input validation & sanitization
- ✅ Added CSRF protection
- ✅ Added rate limiting
- ✅ Added email header injection protection
- ✅ Moved credentials to config file
- ✅ Added secure error logging
- ✅ Added redirect URL validation
