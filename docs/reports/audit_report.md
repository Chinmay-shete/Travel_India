# 🚀 Pre-Production Audit Report

**Project:** The Real Travel (travel_india-new)
**Stack:** PHP + MySQLi + PHPMailer (Gmail SMTP / Brevo)
**Audit Date:** 16 July 2026
**Overall Score:** 19/100 — 🔴 **NOT READY FOR PRODUCTION**

---

## Executive Summary

This PHP travel/hotel booking application has **critical, exploitable vulnerabilities across nearly every security domain**. The most severe findings are: plaintext passwords stored in the database, SMTP credentials and Gmail app-passwords hardcoded directly in PHP source files, SQL injection holes in more than 10 files, zero CSRF protection on any form, and a completely unprotected admin panel that requires no authentication. The application uses `SELECT *` on the `users` table and exposes the full database row (including password column) in session memory. No `.env` file or environment abstraction exists — all secrets are raw strings in PHP. OPcache is not configured, errors are displayed to end-users in `index.php`, and there are no security headers whatsoever. **This application must not be deployed to a public server in its current state.**

---

## Critical Findings Table

| # | Issue | Severity | File / Line | Recommended Fix |
|---|-------|----------|-------------|-----------------|
| 1 | Passwords stored in plaintext in DB | 🔴 CRITICAL | `index.php:61`, `password_change.php:11` | Hash with `password_hash($pwd, PASSWORD_BCRYPT)` before INSERT/UPDATE |
| 2 | Gmail SMTP password hardcoded in source | 🔴 CRITICAL | `config/feedback.php:35`, `Get_in_Touch/contact_index.php:35` | Move to `.env` file, never commit credentials |
| 3 | SQL injection — registration query | 🔴 CRITICAL | `index.php:55,61` | Replace raw query with prepared statement |
| 4 | SQL injection — OTP verification UPDATE | 🔴 CRITICAL | `Authentication/otp_verify.php:33`, `otp_2.php:30` | Use `prepare()` / `bind_param()` |
| 5 | SQL injection — password reset UPDATE | 🔴 CRITICAL | `Authentication/password_change.php:11`, `password_reset.php:71` | Use `prepare()` / `bind_param()` |
| 6 | SQL injection — admin user delete by GET | 🔴 CRITICAL | `admin/user_data.php:9`, `admin/hotellist.php:8` | Use prepared statement; require POST + CSRF |
| 7 | SQL injection — hotel booking GET id | 🔴 CRITICAL | `book_files/book_hotel.php:5`, `book_files/pay_now.php:12` | Use prepared statement |
| 8 | SQL injection — admin edit_user GET id | 🔴 CRITICAL | `admin/edit_user.php:6,20` | Use prepared statement |
| 9 | Admin panel: zero authentication guard | 🔴 CRITICAL | `admin/adminhomepage.php` (no session check) | Add session + role check to every admin page |
| 10 | Zero CSRF protection on every form | 🔴 CRITICAL | All forms site-wide | Generate + validate CSRF token in session |
| 11 | File upload: no MIME/extension validation | 🔴 CRITICAL | `admin/add_hotels.php:14-17` | Whitelist MIME types; store outside webroot |
| 12 | `display_errors = 1` in production entry | 🟠 HIGH | `index.php:5` | Set `display_errors = 0`; log to file instead |
| 13 | Plaintext password in login comparison | 🔴 CRITICAL | `index.php:199-200` | Migrate to `password_verify()` after hashing |
| 14 | Activation code exposed in URL / GET | 🟠 HIGH | `index.php:66`, `Authentication/otp_verify.php:7` | Use opaque token, pass only via server-side redirect |
| 15 | Zero XSS escaping on DB output to HTML | 🟠 HIGH | `admin/user_data.php:125-129`, `admin/hotellist.php:385-386` | Wrap every `echo $row[...]` with `htmlspecialchars()` |
| 16 | Password reset token is weak `md5(rand())` | 🟠 HIGH | `Authentication/password_reset.php:41` | Use `bin2hex(random_bytes(32))` |
| 17 | No rate-limiting on OTP / login endpoints | 🟠 HIGH | `index.php`, `Authentication/resend_otp.php` | Add brute-force protection (lockout after N fails) |
| 18 | `user_auth_acces.php` is empty | 🔴 CRITICAL | `config/user_auth_acces.php` | Implement actual auth guard; include on every protected page |
| 19 | `extract($_GET)` used in admin delete | 🔴 CRITICAL | `admin/hotellist.php:7` | Remove `extract()` entirely |
| 20 | `$_REQUEST` used for password update | 🟠 HIGH | `Authentication/password_change.php:6-8` | Use `$_POST` only; never `$_REQUEST` |

---

## 1. 🔐 Security Audit

### 1.1 SQL Injection

**Status: CRITICAL FAILURES throughout the codebase.**

The application mixes prepared statements (in some files) with raw string-interpolated queries (in most files):

**Vulnerable queries (raw string interpolation):**

| File | Line | Vulnerable Code Snippet |
|------|------|------------------------|
| `index.php` | 55 | `"SELECT * FROM users WHERE email = '$email'"` |
| `index.php` | 61 | `"INSERT INTO users … VALUES('$fname','$lname','$email','$password',…)"` |
| `Authentication/otp_verify.php` | 10 | `"SELECT * FROM users WHERE activation_code ='".$activation_code."'"` |
| `Authentication/otp_verify.php` | 33 | `"UPDATE users SET otp='' … WHERE otp='".$otp."' AND activation_code='".$activation_code."'"` |
| `Authentication/otp_2.php` | 10, 30 | Same pattern as otp_verify.php |
| `Authentication/password_reset.php` | 43, 49, 71 | `"WHERE email = '$email'"`, `"UPDATE users SET password='$pwd'"` |
| `Authentication/password_change.php` | 11 | `"UPDATE users SET password='$pwd' WHERE email='$email'"` |
| `admin/user_data.php` | 5, 9 | `"select * from users"` + `"DELETE FROM users WHERE user_Id=" . $_GET['ID']` |
| `admin/edit_user.php` | 6, 20 | `"where user_Id=" . $_GET['id']`, `"UPDATE users SET … where user_Id=" . $_GET['id']` |
| `admin/hotellist.php` | 4, 8 | `"select * from create_hotel"`, `"DELETE FROM create_hotel WHERE Hotel_Id=" . $_GET['ID']` |
| `admin/add_hotels.php` | 18-19 | Full raw INSERT with all POST variables unescaped |
| `book_files/book_hotel.php` | 5 | `"select * from create_hotel where Hotel_Id=" . $_GET['id']` |
| `book_files/pay_now.php` | 12, 32, 67-68 | Multiple raw queries with session/POST data |
| `config/feedback.php` | 16-17 | `"insert into feedback … values('$name','$email','$massage')"` |
| `Get_in_Touch/contact_index.php` | 15-16 | Same as feedback.php |

> **Only `Authentication/resend_otp.php` (lines 53–57) uses a prepared statement correctly.**

**Fix example:**
```php
// BEFORE (vulnerable):
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

// AFTER (safe):
$stmt = $conn->prepare("SELECT id, email, password_hash, role, status FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
```

---

### 1.2 XSS (Cross-Site Scripting)

**Status: HIGH — database output is echoed raw to HTML.**

Every admin page echoes database column values with no escaping:

```php
// admin/user_data.php lines 125-129 — all unescaped:
echo $row['user_Id'];   // attacker could store <script>alert(1)</script> as their name
echo $row['fname'];
echo $row['lname'];
echo $row['email'];
echo $row['user_type'];

// admin/hotellist.php lines 385-386, 392:
echo $row['Hotel_Name'];
echo $row['Hotel_Address'];
echo $row['amenities'];   // inserted via unvalidated POST — full stored XSS vector

// admin/edit_user.php lines 72-78 (form pre-population):
value="<?php echo $users['fname'] ?>"    // attacker can break attribute with "
value="<?php echo $users['email'] ?>"
```

`config/alert.php` (line 9) echoes `$_SESSION['status']` without escaping — stored XSS if session data originates from user input.

**Fix:** Every database value echoed to HTML:
```php
echo htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8');
```

---

### 1.3 CSRF

**Status: CRITICAL — ZERO CSRF protection anywhere.**

No form in the entire application generates or validates a CSRF token. Destructive DELETE operations are triggered via simple `GET` requests, exploitable via `<img>` tags:

```html
<!-- An attacker's webpage can trigger this silently: -->
<img src="https://site.com/admin/user_data.php?ID=1">
```

**Fix:**
```php
// In session_start block:
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// In every form:
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

// Validate before any state change:
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    die('CSRF validation failed');
}
```

---

### 1.4 Authentication & Password Security

**Status: CATASTROPHIC — passwords stored and compared as plaintext.**

**Registration (`index.php`, line 61):**
```php
// Password inserted directly from POST — plaintext in DB
$sql = "INSERT INTO users … VALUES('$fname','$lname','$email','$password',…)";
```

**Login (`index.php`, lines 199–200):**
```php
// Comparing plaintext password directly against DB column
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);   // password column is plaintext
```

**Password reset (`password_change.php:11`, `password_reset.php:71`):**
```php
"UPDATE users SET password ='$pwd' WHERE email = '$email'"  // still plaintext
```

**Weak reset token (`password_reset.php`, line 41):**
```php
$token = md5(rand());   // MD5 is cryptographically broken; rand() is not CSPRNG
```

**No brute-force protection:** No failed login counter, no account lockout, no CAPTCHA anywhere.

**Fix:**
```php
// Registration:
$password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $conn->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $password_hash);

// Login:
$stmt = $conn->prepare("SELECT password_hash, status, user_type FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row || !password_verify($password, $row['password_hash'])) {
    // fail — same generic message for both cases
}

// Reset token:
$token = bin2hex(random_bytes(32));
```

---

### 1.5 Authorization

**Status: CRITICAL — admin panel has zero access control.**

`admin/adminhomepage.php` has **no PHP at the top** — no `session_start()`, no role check, no redirect. Any unauthenticated user can browse directly to any admin page:

- `/admin/adminhomepage.php` — admin dashboard
- `/admin/user_data.php` — view + delete all users
- `/admin/add_hotels.php` — add hotel records
- `/admin/edit_user.php` — modify any user's data

The file `config/user_auth_acces.php` — presumably intended as an auth guard — is completely empty (5 bytes, contains only `<?php ?>`).

**Fix:** Add this to the top of every admin page:
```php
<?php
require_once '../config/connection.php'; // session_start() is here
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
```

---

### 1.6 Input Validation

**Status: No server-side validation exists on any input.**

- `admin/add_hotels.php` (lines 6–12): All 7 POST fields used directly in SQL with no type/length/format checks.
- `book_files/pay_now.php` (lines 39–60): 8 POST fields inserted into booking table with zero validation.
- `config/feedback.php` / `Get_in_Touch/contact_index.php`: name, email, message inserted raw.
- Registration in `index.php`: No server-side email format check, no password strength enforcement — done purely client-side with HTML `required`.

---

### 1.7 PHPMailer SMTP Credentials

**Status: CRITICAL — Gmail App Password hardcoded in two PHP source files.**

```php
// config/feedback.php lines 34-35:
$mail->Username = 'harsh1234vathare@gmail.com';
$mail->Password = 'olfq duvu rucq tvsv';   // LIVE Gmail App Password in source code

// Get_in_Touch/contact_index.php lines 34-35 (exact duplicate):
$mail->Username = 'harsh1234vathare@gmail.com';
$mail->Password = 'olfq duvu rucq tvsv';
```

This must be revoked immediately if this repository has ever been pushed to any remote.

---

### 1.8 File Uploads

**Status: CRITICAL — completely unrestricted file upload.**

`admin/add_hotels.php`, lines 14–17:
```php
$file = $_FILES['Hotel_Image']['name'];       // uses attacker-controlled filename
$tempname = $_FILES['Hotel_Image']['tmp_name'];
$folder = '../hotel_image/' . $file;          // stored inside webroot
move_uploaded_file($tempname, $folder);       // no MIME check, no extension check
```

An attacker uploads `shell.php`, receives the DB-stored path, then executes arbitrary PHP at `https://site.com/hotel_image/shell.php`.

**Fix:**
```php
$allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['Hotel_Image']['tmp_name']);
if (!in_array($mime, $allowedMime)) { die('Invalid file type'); }

$ext = pathinfo($_FILES['Hotel_Image']['name'], PATHINFO_EXTENSION);
$allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
if (!in_array(strtolower($ext), $allowedExt)) { die('Invalid extension'); }

$safeFilename = bin2hex(random_bytes(16)) . '.' . strtolower($ext);
$uploadDir = dirname(__DIR__) . '/private_uploads/'; // OUTSIDE webroot
move_uploaded_file($_FILES['Hotel_Image']['tmp_name'], $uploadDir . $safeFilename);
```

---

### 1.9 Sensitive Data Exposure

| Secret | Location | Line |
|--------|----------|------|
| MySQL root password `"root"` | `config/connection.php` | 10 |
| Gmail App Password `'olfq duvu rucq tvsv'` | `config/feedback.php` | 35 |
| Gmail App Password `'olfq duvu rucq tvsv'` | `Get_in_Touch/contact_index.php` | 35 |
| Mailtrap sandbox key `'6eb575eefadd55'` | `config/email_config.php` | 17 |
| Brevo placeholder `'YOUR_BREVO_SMTP_KEY'` | `config/email_config.php` | 17 |

There is **no `.env` file, no `vlucas/phpdotenv`, no `getenv()` usage**. All credentials are hardcoded PHP constants or variables.

---

### 1.10 HTTPS & Security Headers

**Status: Not enforced anywhere.**

- No HTTP → HTTPS redirect in `.htaccess` or PHP. No `.htaccess` file found in the project root.
- No security response headers: no `Content-Security-Policy`, no `Strict-Transport-Security`, no `X-Frame-Options`, no `X-Content-Type-Options`, no `Referrer-Policy`.

---

## 2. ⚡ Performance Analysis

### 2.1 N+1 Query Problems

- `admin/user_data.php` (line 5): `SELECT * FROM users` — unbounded with no `LIMIT`, returns all rows into memory.
- `admin/hotellist.php` (line 4): `SELECT * FROM create_hotel` — same, fetches all hotels.
- `book_files/pay_now.php` (line 32): Separate query to fetch user by session email — 2 queries where 1 JOIN suffices.

### 2.2 `SELECT *` Usage

Every single query uses `SELECT *`. This means:
- Full user rows including `password`, `otp`, `activation_code` are loaded into session memory (`index.php:205-210`).
- Admin views transfer unnecessary columns over the wire.

### 2.3 OPcache

No `php.ini` or OPcache configuration found anywhere in the project.

### 2.4 Session Storage

Default PHP file-based sessions. Session files lock under concurrent requests from the same session, creating sequential (queued) request processing for any single user.

### 2.5 PHPMailer Sending Mode

**Synchronous — blocks HTTP response thread.** Every email-sending operation (`index.php`, `Authentication/resend_otp.php`, `config/feedback.php`, `Authentication/password_reset.php`) blocks the response until SMTP handshake completes (500ms–3s per request).

### 2.6 Missing DB Indexes

Based on query patterns, the following indexes are likely missing:
- `users.email` — queried in every login, registration, and password reset
- `users.activation_code` — queried in OTP verification
- `create_hotel.Hotel_Id`

### 2.7 Static Assets & CDN

jQuery is loaded **twice** on `index.php` (lines 84 and 496), adding ~87KB of duplicate network transfer per page load. All CSS/JS libraries are loaded from multiple external CDNs. No local assets are minified.

### 2.8 Breaking Point Under Load

1. **DB connection limit** — MySQLi creates a new connection per PHP request; MySQL default `max_connections=151` exhausted at ~150 concurrent users.
2. **Session file locking** — concurrent requests from the same session queue up.
3. **Synchronous SMTP** — each email ties up an Apache/Nginx worker for 1–3 seconds.

---

## 3. 📈 Scalability Assessment

### 3.1 Stateless Architecture

**No.** Session-dependent. Login stores `$_SESSION['email']` and `pay_now.php:27` uses it to identify users. File-based sessions prevent horizontal scaling.

### 3.2 Multi-Server Compatibility

**Not compatible.** File-based sessions stored on local filesystem. A load balancer requires sticky sessions or Redis/Memcached session storage.

### 3.3 Database Scalability

- No connection pooling — MySQLi opens a new TCP connection per PHP process.
- No read replica support — all queries hit a single `$conn` hardcoded to `localhost`.
- Root credentials hardcoded — not parameterized for environment switching.

### 3.4 Email Scalability

Direct SMTP per request, no queue. Gmail free tier: 500 emails/day. Brevo free: 300/day. 100 registrations in an hour will exhaust Gmail's daily limit.

### 3.5 CDN Readiness

Uploaded hotel images stored in `/hotel_image/` inside the webroot — not CDN-ready and a security risk (PHP execution possible).

### 3.6 Heavy Task Handling

All heavy operations (email, file processing) are synchronous in the PHP request cycle. No queue system (Redis Queue, Gearman, etc.) exists.

---

## 4. 👥 Load Capacity Estimation

| Scenario | Est. Concurrent Users | Est. RPS | Breaking Point |
|----------|-----------------------|----------|----------------|
| Shared hosting (512MB RAM, no OPcache) | ~5–8 | ~2–3 | DB connections exhausted at ~10 users |
| VPS 2GB + PHP-FPM (10 workers) | ~15–20 | ~8–12 | Session locks + SMTP blocking |
| VPS 4GB + OPcache + PHP-FPM (25 workers) | ~30–50 | ~20–30 | MySQL `max_connections` + SMTP latency |
| With Redis sessions + DB indexes | ~200–300 | ~80–120 | MySQL connection pool limit |
| With async email + read replicas | ~800–1,000 | ~300–500 | App code must be refactored |

**To reach 1,000 concurrent users:** Redis sessions, prepared statements everywhere, connection pooling (PgBouncer/ProxySQL), async email queue, OPcache, proper indexes, CDN.

**To reach 10,000 concurrent users:** Rewrite to PDO + modern framework (Laravel/Slim), horizontal PHP-FPM scaling, MySQL read replicas, Redis pub/sub for email queue, object caching.

**To reach 100,000 concurrent users:** Full microservices, managed cloud DB (RDS/Aurora), event-driven email processing, global CDN, autoscaling compute.

**Gmail SMTP Rate Limit Risk:** ~50 OTP registrations will approach Gmail's 500/day daily limit. Brevo free at 300/day is even more limited.

---

## 5. 🧹 Code Quality & Stability

### 5.1 Error Handling

```php
// index.php lines 4-5:
error_reporting(E_ALL);
ini_set('display_errors', 1);   // leaks stack traces + DB structure to users in production

// admin/add_hotels.php:4, admin/edit_user.php:3, admin/user_data.php:3:
error_reporting(0);             // swallows all errors silently — debugging impossible

// Authentication/resend_otp.php:3:
error_reporting(0);             // same
```

No global `set_exception_handler()`, no centralized error logging to a file, no structured logging.

### 5.2 Event Logging

No login attempts, failed authentications, or critical events are logged. Failed queries return `false` silently.

### 5.3 PHP 8.x Compatibility Issues

| Issue | File / Line |
|-------|-------------|
| `mysqli_escape_string()` — deprecated style | `password_reset.php:40` |
| `extract()` — dangerous superglobal pollution | `admin/hotellist.php:7` |
| `str_shuffle("0123456789")` — not CSPRNG-safe for OTP | `index.php:38-39`, `resend_otp.php:38-39` |
| `rand()` for token — not CSPRNG | `password_reset.php:41` |

### 5.4 Dead Code

| Location | Content |
|----------|---------|
| `index.php:249-293` | 45-line commented-out old vulnerable login block |
| `Authentication/otp_2.php:49` | Mismatched `// }` suggesting broken logic |
| `book_files/book_hotel.php:84-115` | Multiple commented-out `<button>` elements |
| `book_files/pay_now.php:1-2` | `// session_start();` commented while `$_SESSION` used below |

### 5.5 Composer

No `composer.json` found. PHPMailer included via manual `require` statements. Unknown PHPMailer version — cannot run `composer audit` for CVE scanning.

### 5.6 Code Structure

**Spaghetti architecture.** PHP logic, HTML, JavaScript, and CSS are intermingled in single files. No MVC separation, no router, no controller layer, no model layer, no template engine. `index.php` (507 lines) handles registration logic, login logic, OTP sending, form HTML, navigation HTML, and GSAP animations — all in one file.

**Maintainability score: 2/10.**

### 5.7 Environment Separation

No `.env` file. No `getenv()`. Credentials hardcoded for both local and production environments. The `email_config.php` auto-detection by `$_SERVER['HTTP_HOST']` is fragile — a production server configured with `localhost` in a virtualhost name would incorrectly use dev config.

---

## 6. ⚙️ PHP & Server Configuration Recommendations

### 6.1 Recommended `php.ini` (Production)

```ini
; Error handling
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php/error.log
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT

; Resource limits
memory_limit = 128M
max_execution_time = 30
max_input_time = 30

; File uploads
upload_max_filesize = 5M
post_max_size = 8M

; Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.cookie_samesite = Strict
session.use_strict_mode = 1
session.gc_maxlifetime = 1800

; Misc security
expose_php = Off
```

### 6.2 Recommended PHP-FPM Pool

**2GB VPS:**
```ini
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 3
pm.max_spare_servers = 8
pm.max_requests = 500
```

**4GB VPS:**
```ini
pm.max_children = 40
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 15
```

### 6.3 Recommended OPcache

```ini
opcache.enable = 1
opcache.enable_cli = 0
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 16
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 60
opcache.validate_timestamps = 0
opcache.save_comments = 1
opcache.fast_shutdown = 1
```

### 6.4 Nginx Configuration

```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://code.jquery.com; style-src 'self' https://cdn.jsdelivr.net https://unpkg.com 'unsafe-inline';" always;

    # Rate limiting on auth endpoints
    location ~ ^/(index\.php|Authentication/) {
        limit_req zone=auth burst=5 nodelay;
    }

    # Block PHP execution in upload directories
    location ~* /hotel_image/.*\.php$ { deny all; }

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/javascript application/json;
    gzip_min_length 1024;

    # Static asset caching
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}

# HTTP -> HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$host$request_uri;
}
```

---

## 7. 📧 PHPMailer Specific Audit

### 7.1 SMTP Credentials

| Location | Credential | Status |
|----------|------------|--------|
| `config/feedback.php:34-35` | Gmail + App Password | 🔴 HARDCODED in source |
| `Get_in_Touch/contact_index.php:34-35` | Same Gmail + App Password | 🔴 HARDCODED (duplicate) |
| `config/email_config.php:17` | Mailtrap sandbox key | 🟠 Hardcoded (dev only) |
| `config/email_config.php:17` | Brevo placeholder string | 🟡 Not real — unfilled placeholder |

**Immediate action:** Revoke `olfq duvu rucq tvsv` at myaccount.google.com → Security → App Passwords.

### 7.2 TLS/SSL Configuration

- `config/email_config.php`: `MAIL_SECURE = 'tls'` (STARTTLS port 587) — ✅ Correct for Brevo/Mailtrap.
- `config/feedback.php:36`: `PHPMailer::ENCRYPTION_SMTPS` (port 465) — ✅ Implicit TLS, acceptable.
- `Get_in_Touch/contact_index.php:36`: Same as feedback.php — ✅.

TLS configuration itself is correct where applied. The issue is credential exposure, not TLS settings.

### 7.3 Email Header Injection / XSS in Email Body

`config/feedback.php:46-50` and `Get_in_Touch/contact_index.php:57-61`:
```php
$mail->Subject = 'Feedback From '. $_POST['name'] .'..!';
$mail->Body = '... You got a new message from '. $_POST['name'] .' ,</b>...
              Their Email_Id :'. $_POST['email'] .'...
              Messages :'. $_POST['massage'] .'...';
```

PHPMailer sanitizes the `Subject` header internally. However, unescaped `$_POST` data in the HTML `Body` creates an **XSS vector in the notification email** (clickable HTML injected into admin's inbox).

**Fix:**
```php
$safeName    = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$safeEmail   = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$safeMessage = htmlspecialchars($_POST['massage'], ENT_QUOTES, 'UTF-8');
```

### 7.4 SMTP Failure Handling

In `index.php`, `resend_otp.php`, `password_reset.php`:
```php
} catch (Exception $e) {
    error_log('Mailer Error: ' . $mail->ErrorInfo);
    // No user feedback — user gets success message but never receives email
}
```

If SMTP fails, users are told they succeeded but receive no OTP or reset link. No retry, no queue, no dead-letter storage.

### 7.5 Retry Logic

**None.** Failed sends are silently logged and forgotten.

### 7.6 Rate Limit Risk

| Provider | Daily Limit | Risk Level |
|----------|-------------|------------|
| Gmail App Password | 500/day | 🔴 HIGH — ~500 registrations exhausts limit |
| Brevo Free | 300/day | 🔴 HIGH — even lower |
| Brevo Starter ($25/mo) | 20,000/day | 🟡 MEDIUM — adequate for small launch |
| SendGrid Free | 100/day | 🔴 CRITICAL — nearly unusable |

### 7.7 SPF/DKIM/DMARC

`config/feedback.php` sets `setFrom('harsh1234vathare@gmail.com')` while authenticating as the same Gmail — SPF will pass for Gmail domain, but `noreply@travelindia.com` (set in `email_config.php:18`) does not match Gmail's SPF/DKIM records. Emails sent with a mismatched From domain **will fail DMARC** and land in spam or be rejected.

---

## 8. ✅ Pre-Deployment Checklist

### 🔴 CRITICAL — Must fix before ANY deployment

- [ ] **Hash all passwords** with `password_hash($pwd, PASSWORD_BCRYPT)` — `index.php:61`, `password_change.php:11`, `password_reset.php:71`
- [ ] **Migrate login** to `password_verify()` — `index.php:199-200`
- [ ] **Prepared statements** for ALL SQL queries — 15+ files listed in Section 1.1
- [ ] **Revoke Gmail App Password** `olfq duvu rucq tvsv` immediately
- [ ] **Add auth guard** to all admin pages (session + role check) — entire `admin/` directory
- [ ] **Implement CSRF tokens** on every form
- [ ] **Fix file upload** — MIME validation, extension whitelist, store outside webroot — `admin/add_hotels.php:14-17`
- [ ] **Set `display_errors = Off`** in production — `index.php:5`
- [ ] **Remove `extract($_GET)`** — `admin/hotellist.php:7`
- [ ] **Implement auth logic** in `config/user_auth_acces.php` (currently empty)

### 🟠 HIGH — Fix before public launch

- [ ] Move all credentials to `.env` file + `vlucas/phpdotenv`
- [ ] Add `htmlspecialchars()` to all DB output in HTML — `admin/user_data.php`, `admin/hotellist.php`, `admin/edit_user.php`
- [ ] Replace `md5(rand())` token with `bin2hex(random_bytes(32))` — `password_reset.php:41`
- [ ] Replace `str_shuffle("0123456789")` OTP with `random_int(100000, 999999)` — `index.php:38-39`
- [ ] Add rate limiting to login, OTP send, and password reset endpoints
- [ ] Verify reset token server-side before allowing password update (`password_change.php` ignores `verify_token`)
- [ ] Add HTTP → HTTPS redirect
- [ ] Add security response headers (CSP, HSTS, X-Frame-Options, etc.)
- [ ] Fix SPF/DMARC alignment — use matching From domain with configured SMTP provider
- [ ] Remove duplicate jQuery import (`index.php:84` and `index.php:496`)
- [ ] Change MySQL from `root:root` to a dedicated DB user with least privilege

### 🟡 MEDIUM — Fix within first week of launch

- [ ] Add Composer and manage PHPMailer via `composer.json`; run `composer audit`
- [ ] Add DB indexes on `users.email`, `users.activation_code`
- [ ] Implement centralized error logging (`set_error_handler()` + `set_exception_handler()`)
- [ ] Remove all commented-out dead code (`index.php:249-293`, etc.)
- [ ] Add server-side input validation (email format, length limits, numeric ranges)
- [ ] Replace `SELECT *` with specific column lists in all queries
- [ ] Configure secure session cookie flags (`httponly`, `secure`, `samesite`)
- [ ] Add email send failure feedback to user
- [ ] Configure OPcache

### 🟢 LOW — Long-term improvements

- [ ] Migrate to PDO (more portable than MySQLi)
- [ ] Adopt MVC architecture (Laravel, Slim, or custom router)
- [ ] Implement async email queue (Redis + worker) to eliminate SMTP blocking
- [ ] Migrate sessions to Redis for horizontal scalability
- [ ] Add SPF, DKIM, DMARC DNS records for sending domain
- [ ] Switch to Brevo/SendGrid for production transactional email
- [ ] Move `hotel_image/` uploads outside webroot; serve via PHP proxy
- [ ] Add structured logging (Monolog or similar)
- [ ] Add unit/integration tests

---

## 9. 📊 Production Readiness Scorecard

### Security: 3/25

| Deduction | Reason |
|-----------|--------|
| -10 | Plaintext password storage and comparison — catastrophic |
| -4 | Gmail App Password hardcoded in 2 committed PHP files |
| -3 | SQL injection in 15+ query locations |
| -2 | Zero CSRF protection on all forms including destructive operations |
| -1 | No authentication on admin panel (`config/user_auth_acces.php` is empty) |
| -1 | Unrestricted file upload — arbitrary PHP execution possible |
| -1 | No security headers (HSTS, CSP, X-Frame-Options) |

**Score: 3/25**

---

### Performance: 8/20

| Deduction | Reason |
|-----------|--------|
| -4 | Synchronous SMTP blocks every registration/contact response thread |
| -3 | `SELECT *` everywhere; no column specificity |
| -2 | jQuery loaded twice on index.php |
| -2 | No OPcache configuration |
| -1 | Missing DB indexes on `users.email`, `users.activation_code` |

**Score: 8/20**

---

### Scalability: 3/20

| Deduction | Reason |
|-----------|--------|
| -7 | File-based sessions — incompatible with horizontal scaling |
| -4 | No connection pooling; new MySQLi connection per request |
| -4 | Direct SMTP per request — rate-limited and blocks response |
| -2 | Uploaded files in webroot — cannot safely serve from CDN |

**Score: 3/20**

---

### Code Quality: 4/20

| Deduction | Reason |
|-----------|--------|
| -6 | No MVC — spaghetti PHP mixing logic, HTML, JS, CSS |
| -4 | No Composer — manual requires, unknown package versions |
| -3 | `display_errors` inconsistency (On in index.php, Off via `error_reporting(0)` in admin) |
| -2 | Significant dead code (250-line commented block in index.php) |
| -1 | Non-CSPRNG OTP generation (`str_shuffle`, `rand()`) |

**Score: 4/20**

---

### Infrastructure Readiness: 1/15

| Deduction | Reason |
|-----------|--------|
| -6 | No `.env` abstraction — hardcoded credentials for all environments |
| -4 | No HTTPS enforcement, no security headers |
| -2 | No `.htaccess` or Nginx config in project |
| -1 | No error log destination configured |
| -1 | Gmail SMTP not suitable for production transactional email |

**Score: 1/15**

---

### 📊 TOTAL: 19/100 — 🔴 NOT READY FOR PRODUCTION

---

## 10. 🗺️ Recommended Next Steps (Priority Order)

| # | Priority | Action | Est. Effort |
|---|----------|--------|-------------|
| 1 | 🔴 | **Revoke Gmail App Password** at myaccount.google.com | 5 min |
| 2 | 🔴 | **Hash all passwords** — add `password_hash()` to registration + reset, `password_verify()` to login | 2 hrs |
| 3 | 🔴 | **Prepared statements** — replace all raw SQL in every file | 4–6 hrs |
| 4 | 🔴 | **Admin auth guard** — session check + role check on every page in `admin/` | 2 hrs |
| 5 | 🔴 | **CSRF tokens** — add to every form and validate server-side | 2 hrs |
| 6 | 🔴 | **Fix file upload** — MIME/extension validation + store outside webroot | 2 hrs |
| 7 | 🟠 | **`.env` file + phpdotenv** — move all credentials out of PHP source | 2 hrs |
| 8 | 🟠 | **XSS escaping** — wrap all `echo $row[...]` in `htmlspecialchars()` | 2 hrs |
| 9 | 🟠 | **`display_errors = Off`** + configure `error_log` path | 30 min |
| 10 | 🟠 | **Security headers** via `.htaccess` or Nginx config | 1 hr |
| 11 | 🟠 | **Password reset token** — `bin2hex(random_bytes(32))` | 30 min |
| 12 | 🟠 | **Rate limiting** — brute-force protection on login/OTP | 3 hrs |
| 13 | 🟡 | **DB indexes** — `users.email`, `users.activation_code` | 30 min |
| 14 | 🟡 | **Composer** — install, add PHPMailer via Composer, run `composer audit` | 1 hr |
| 15 | 🟡 | **Switch to Brevo/SendGrid** for production email (not Gmail) | 2 hrs |
| 16 | 🟡 | **Remove dead code** — clean up all commented blocks | 1 hr |
| 17 | 🟢 | **Async email queue** — simple DB-backed queue to eliminate SMTP blocking | 1–2 days |
| 18 | 🟢 | **Redis sessions** — for production horizontal scaling | 1 day |
| 19 | 🟢 | **MVC refactor** — migrate to Laravel or Slim for long-term maintainability | 1–2 weeks |

---

*Audit performed on complete source code of `travel_india-new` read directly from `/Users/chinu/Developer/Code/travel_india-new`. All file references and line numbers match the actual code on disk.*
