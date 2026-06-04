# 🎯 ACTION PLAN & FIX TIMELINE
## Travel India - Remediation Roadmap

**Generated:** June 4, 2026  
**Status:** Implementation Plan  
**Target Completion:** 5-7 weeks (3-5 person team recommended)

---

## QUICK START: CRITICAL FIXES (Must fix first)

If you have only 1 week and need to deploy, focus on these 7 items:

1. **SQL Injection Fix** (~8-10 hours) - Convert queries to prepared statements
2. **Admin Access Control** (~2-3 hours) - Add session checks to admin pages
3. **Password Hashing** (~4-5 hours) - Hash passwords with bcrypt
4. **API Key Security** (~1 hour) - Move to environment variables
5. **Email Credentials** (~1 hour) - Move to environment variables
6. **Input Validation** (~6-8 hours) - Validate all form inputs
7. **File Upload Security** (~2-3 hours) - Validate uploaded files

**Minimum Effort: 24-32 hours (3-4 days for 1 person, OR 1 day for 4 people)**

---

## DETAILED REMEDIATION PLAN

### WEEK 1: DATABASE & AUTHENTICATION SECURITY

#### Task 1.1: Create Prepared Statement Helper Function (2 hours)
```php
// config/database.php - NEW FILE
function preparedQuery($conn, $sql, $types, ...$values) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    $stmt->bind_param($types, ...$values);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
    return $stmt;
}
```

**Owner:** Lead Developer | **Due:** Day 1 | **Time:** 2h

#### Task 1.2: Fix SQL Injection in Signup (3 hours)
**File:** `index.php` line 61  
**Current (VULNERABLE):**
```php
$sql = "INSERT INTO users (...) VALUES('$fname','$lname','$email','$password',...)";
```

**Fixed:**
```php
$stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password, user_type, otp, activation_code, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $fname, $lname, $email, $hashed_password, $user_type, $otp, $activation_code, $status);
$stmt->execute();
```

**Owner:** Developer 1 | **Due:** Day 1-2 | **Time:** 3h

#### Task 1.3: Fix SQL Injection in Login (2 hours)
**File:** `other/login.php` line 6  
**Fix:** Convert to prepared statement (same pattern as 1.2)  
**Owner:** Developer 1 | **Due:** Day 2 | **Time:** 2h

#### Task 1.4: Audit All SQL Queries (4 hours)
Use grep to find all remaining SQL injections:
```bash
grep -r "\$sql = " --include="*.php" | grep -v "bind_param"
```
Create list of all vulnerable queries and schedule fixes.

**Owner:** QA/Lead | **Due:** Day 2 | **Time:** 4h

#### Task 1.5: Implement Password Hashing (5 hours)
**File:** `index.php` (signup), `Authentication/otp_2.php` (registration completion)

**Change 1 - Signup:**
```php
$password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
// Store $password_hash in database, NOT plaintext password
```

**Change 2 - Login:**
```php
// index.php lines 196-200
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && password_verify($_POST['password'], $row['password'])) {
    // Login successful
} else {
    // Invalid credentials
}
```

**Owner:** Developer 1 | **Due:** Day 3 | **Time:** 5h

#### Task 1.6: Create Database Backup & Migration Plan (3 hours)
```sql
-- Backup current user table
CREATE TABLE users_backup AS SELECT * FROM users;

-- Add new hashed_password column (temporary)
ALTER TABLE users ADD COLUMN password_hash VARCHAR(255);

-- Migrate existing passwords (one-time)
UPDATE users SET password_hash = SHA2(password, 256) WHERE password_hash IS NULL;

-- After verification, drop old password column and rename
ALTER TABLE users DROP COLUMN password;
ALTER TABLE users RENAME COLUMN password_hash TO password;
```

**Owner:** DBA | **Due:** Day 3 | **Time:** 3h

---

### WEEK 2: ACCESS CONTROL & SESSION SECURITY

#### Task 2.1: Create Admin Session Guard (2 hours)
**File:** `config/guard.php` (NEW)
```php
<?php
session_start();

function requireAdmin() {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
        header('HTTP/1.0 403 Forbidden');
        die('Access Denied: Admin privileges required');
    }
}

function requireLogin() {
    if (!isset($_SESSION['email'])) {
        header('Location: /index.php');
        exit;
    }
}
?>
```

**Owner:** Developer 1 | **Due:** Day 4 | **Time:** 2h

#### Task 2.2: Implement Admin Guards on All Admin Pages (3 hours)
Add to TOP of each file in `admin/` directory:
```php
<?php
include("../config/guard.php");
requireAdmin();
// ... rest of page code
```

**Files to update:**
- admin/adminhomepage.php
- admin/add_packages.php
- admin/add_hotels.php
- admin/add_intern_package.php
- admin/user_data.php
- admin/feedbackdata.php
- admin/tourlist.php
- admin/hotellist.php
- admin/International_tourlist.php
- admin/update_tour.php
- admin/update_hotel.php
- admin/update_intern.php
- admin/edit_user.php
- admin/booking_approvel/book.php
- admin/booking_approvel/hotel_book.php

**Owner:** Developers 1 & 2 | **Due:** Day 5 | **Time:** 3h

#### Task 2.3: Fix Admin Login Session Variables (1 hour)
**File:** `other/login.php` line 16-18

**Current (BROKEN):**
```php
} else if($row["user_type"]=="admin") {
    if($row['status']== 1){
        header('location:../admin/adminhomepage.php');  // Missing session set!
    }
}
```

**Fixed:**
```php
} else if($row["user_type"]=="admin") {
    if($row['status']== 1){
        $_SESSION['email'] = $row['email'];
        $_SESSION['user_type'] = 'admin';
        $_SESSION['admin_id'] = $row['id'];
        header('location:../admin/adminhomepage.php');
    }
}
```

**Owner:** Developer 1 | **Due:** Day 5 | **Time:** 1h

#### Task 2.4: Fix User Login Session Variables (1 hour)
Same pattern as Task 2.3 but check for "user" type.

**Owner:** Developer 1 | **Due:** Day 5 | **Time:** 1h

#### Task 2.5: Fix Logout Function (0.5 hours)
**File:** `config/logout.php`

**Current (INCOMPLETE):**
```php
session_destroy();
```

**Fixed:**
```php
session_start();
$_SESSION = [];
session_unset();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
header("Location: ../index.php");
exit;
```

**Owner:** Developer 1 | **Due:** Day 5 | **Time:** 0.5h

#### Task 2.6: Secure Session Cookies (1 hour)
**File:** `config/connection.php` after `session_start()`

```php
session_start();
session_set_cookie_params([
    'lifetime' => 3600,      // 1 hour
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,        // HTTPS only
    'httponly' => true,      // No JavaScript access
    'samesite' => 'Strict'   // CSRF protection
]);
```

**Owner:** Developer 1 | **Due:** Day 5 | **Time:** 1h

---

### WEEK 3: INPUT VALIDATION & FILE SECURITY

#### Task 3.1: Create Input Validation Helper (3 hours)
**File:** `config/validation.php` (NEW)
```php
<?php
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }
    if (strlen($email) > 255) {
        return "Email too long";
    }
    return null;
}

function validatePhone($phone) {
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        return "Invalid phone number (10 digits required)";
    }
    return null;
}

function validatePrice($price) {
    if (!is_numeric($price) || $price <= 0 || $price > 999999) {
        return "Invalid price";
    }
    return null;
}

function sanitizeText($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
```

**Owner:** Developer 2 | **Due:** Day 6 | **Time:** 3h

#### Task 3.2: Add Form Validation to Signup (2 hours)
**File:** `index.php` lines 44-55

Add before database insertion:
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    
    $errors[] = validateEmail($_POST['email']);
    $errors[] = validatePhone($_POST['Mobile_No']);
    
    if (strlen($_POST['fname']) < 2 || strlen($_POST['fname']) > 50) {
        $errors[] = "First name must be 2-50 characters";
    }
    
    if (strlen($_POST['password']) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    
    $errors = array_filter($errors);
    if (!empty($errors)) {
        echo "<script>alert('Validation errors: " . implode(", ", $errors) . "');</script>";
        exit;
    }
    // Continue with database insertion
}
```

**Owner:** Developer 2 | **Due:** Day 7 | **Time:** 2h

#### Task 3.3: Add Form Validation to All Forms (6 hours)
Apply same validation to:
- Booking forms (`book_files/book_form.php`, etc.)
- Admin forms (`admin/add_packages.php`, `add_hotels.php`, etc.)
- Contact form (`Get_in_Touch/contact.php`)
- Password reset (`Authentication/password_reset.php`)

**Owner:** Developers 2 & 3 | **Due:** Day 9 | **Time:** 6h

#### Task 3.4: Implement File Upload Validation (3 hours)
**File:** `admin/add_packages.php` line 16-21

**Current (VULNERABLE):**
```php
$file = $_FILES['package-img']['name'];
$tempname = $_FILES['package-img']['tmp_name'];
$folder = '../image/'.$file;
move_uploaded_file($tempname, $folder);
```

**Fixed:**
```php
// File upload validation
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 5 * 1024 * 1024; // 5MB

$file = $_FILES['package-img'];
$error = null;

if ($file['size'] > $max_size) {
    $error = "File too large (max 5MB)";
} else if (!in_array($file['type'], $allowed_types)) {
    $error = "Invalid file type (jpg, png, gif only)";
} else {
    // Verify it's actually an image
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $allowed_types)) {
        $error = "File content doesn't match extension";
    }
}

if ($error) {
    echo "<script>alert('Upload error: $error');</script>";
} else {
    // Rename file to prevent overwrite and execution
    $new_filename = uniqid() . '_' . basename($file['name']);
    $upload_path = '../image/' . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Continue with database insertion
        $folder = $new_filename;
        // ... INSERT INTO database
    }
}
```

**Owner:** Developer 2 | **Due:** Day 9 | **Time:** 3h

#### Task 3.5: Apply File Upload Validation to All Upload Points (2 hours)
- `admin/add_hotels.php`
- `admin/add_intern_package.php`
- Any other file upload forms

**Owner:** Developer 2 | **Due:** Day 10 | **Time:** 2h

---

### WEEK 4: ENVIRONMENT CONFIGURATION & SECURITY HEADERS

#### Task 4.1: Create Environment Configuration System (2 hours)
**Files to create:**
- `.env.example`
- `.env` (DO NOT COMMIT)
- `config/env.php`

```php
// config/env.php
function getEnv($key, $default = null) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    
    // Load from .env file
    static $env = null;
    if ($env === null) {
        $env = [];
        if (file_exists(__DIR__ . '/.env')) {
            $lines = file(__DIR__ . '/.env');
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0 || !trim($line)) continue;
                list($key, $value) = explode('=', $line, 2);
                $env[trim($key)] = trim($value);
            }
        }
    }
    
    return $env[$key] ?? $default;
}

// Usage:
define('DB_HOST', getEnv('DB_HOST', 'localhost'));
define('DB_USER', getEnv('DB_USER', 'root'));
define('DB_PASS', getEnv('DB_PASS', ''));
define('RAZORPAY_KEY', getEnv('RAZORPAY_KEY_ID'));
define('MAIL_USERNAME', getEnv('MAIL_USERNAME'));
```

**Owner:** Developer 1 | **Due:** Day 11 | **Time:** 2h

#### Task 4.2: Move Razorpay Keys to Environment (1 hour)
**Files:** 
- `book_files/payment/razorpay.php`
- `International_book/razorpay.php`
- `Lakshadweep/razorpay.php`

**Change from:**
```php
$apikey = "rzp_test_Pl81xvWKLN0yIB";  // EXPOSED
```

**Change to:**
```php
$apikey = getEnv('RAZORPAY_KEY_ID');
```

**Owner:** Developer 1 | **Due:** Day 11 | **Time:** 1h

#### Task 4.3: Move Email Credentials to Environment (1 hour)
**File:** `config/email_config.php` lines 16-17

**Change from:**
```php
define('MAIL_USERNAME', 'bd2537a2c7f91b');  // EXPOSED
define('MAIL_PASSWORD', '6eb575eefadd55');  // EXPOSED
```

**Change to:**
```php
define('MAIL_USERNAME', getEnv('MAIL_USERNAME'));
define('MAIL_PASSWORD', getEnv('MAIL_PASSWORD'));
```

**Owner:** Developer 1 | **Due:** Day 11 | **Time:** 1h

#### Task 4.4: Remove Debug Settings from Production Code (1 hour)
**Files:** `index.php`, `other/login.php`, `admin/add_packages.php`, etc.

**Remove:**
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(0);
```

**Replace with:**
```php
if (getEnv('ENVIRONMENT') === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '/var/log/php_errors.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
```

**Owner:** Developer 1 | **Due:** Day 12 | **Time:** 1h

#### Task 4.5: Add Security Headers (1 hour)
**File:** `config/connection.php` (add after session_start)

```php
// Security Headers
if (getEnv('ENVIRONMENT') === 'production') {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    header('Content-Security-Policy: default-src \'self\'');
}
```

**Owner:** Developer 1 | **Due:** Day 12 | **Time:** 1h

---

### WEEK 5: TESTING & FINAL VERIFICATION

#### Task 5.1: Create Test Suite for SQL Injection Prevention (4 hours)
**File:** `tests/SecurityTest.php`

```php
<?php
use PHPUnit\Framework\TestCase;

class SecurityTest extends TestCase {
    public function testSQLInjectionPrevention() {
        $malicious_email = "' OR '1'='1";
        // Test that prepared statements block this
        // Verify no results are returned for malicious input
    }
    
    public function testPasswordHashing() {
        $password = "TestPassword123";
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify("WrongPassword", $hash));
    }
    
    public function testAdminAccessControl() {
        // Test that non-admin users cannot access admin pages
    }
}
?>
```

**Owner:** QA | **Due:** Day 13 | **Time:** 4h

#### Task 5.2: Penetration Testing (8 hours)
Test all fixed vulnerabilities:
- Attempt SQL injections (should fail)
- Attempt admin access without login (should redirect)
- Attempt file upload with malicious content (should reject)
- Test session timeout
- Test logout functionality

**Owner:** QA/Security | **Due:** Day 15 | **Time:** 8h

#### Task 5.3: Code Review & Approval (2 hours)
- Review all security fixes with team
- Verify no new vulnerabilities introduced
- Document changes

**Owner:** Lead Developer | **Due:** Day 16 | **Time:** 2h

#### Task 5.4: Create Deployment Checklist (1 hour)
```
Pre-Deployment Checklist:
- [ ] All SQL queries converted to prepared statements
- [ ] All passwords using bcrypt
- [ ] Admin pages have access guards
- [ ] No hardcoded credentials in code
- [ ] All forms validated
- [ ] File uploads validated
- [ ] Security headers added
- [ ] Session cookies secured
- [ ] Logout works properly
- [ ] .env file created and not committed
- [ ] All tests passing
- [ ] Security tests passing
- [ ] Backup strategy in place
```

**Owner:** QA | **Due:** Day 17 | **Time:** 1h

---

## TIMELINE SUMMARY

```
Week 1 (Days 1-5): Authentication & Database Security
├── Task 1.1: DB Helper Function (2h)
├── Task 1.2: Fix SQL Injection in Signup (3h)
├── Task 1.3: Fix SQL Injection in Login (2h)
├── Task 1.4: Audit All SQL (4h)
├── Task 1.5: Password Hashing (5h)
└── Task 1.6: DB Migration (3h)
TOTAL: ~19 hours

Week 2 (Days 6-10): Access Control & Sessions
├── Task 2.1: Session Guard (2h)
├── Task 2.2: Admin Guards (3h)
├── Task 2.3: Admin Login Sessions (1h)
├── Task 2.4: User Login Sessions (1h)
├── Task 2.5: Logout Fix (0.5h)
└── Task 2.6: Secure Cookies (1h)
TOTAL: ~8.5 hours

Week 3 (Days 11-15): Input Validation & Files
├── Task 3.1: Validation Helpers (3h)
├── Task 3.2: Signup Validation (2h)
├── Task 3.3: All Forms Validation (6h)
├── Task 3.4: File Upload Security (3h)
└── Task 3.5: Apply File Validation (2h)
TOTAL: ~16 hours

Week 4 (Days 16-20): Configuration & Headers
├── Task 4.1: Environment Config (2h)
├── Task 4.2: Razorpay Keys (1h)
├── Task 4.3: Email Creds (1h)
├── Task 4.4: Remove Debug Code (1h)
└── Task 4.5: Security Headers (1h)
TOTAL: ~6 hours

Week 5 (Days 21-25): Testing & Verification
├── Task 5.1: Test Suite (4h)
├── Task 5.2: Penetration Testing (8h)
├── Task 5.3: Code Review (2h)
└── Task 5.4: Deployment Checklist (1h)
TOTAL: ~15 hours

GRAND TOTAL: ~64.5 hours (~2-3 weeks for 2-3 person team)
```

---

## RESOURCE ALLOCATION RECOMMENDATION

### Minimum Team (3 people, 5 weeks)
- **Developer 1** (Senior): Security, database, authentication (28 hours)
- **Developer 2** (Mid-level): Input validation, file security (24 hours)
- **QA Engineer**: Testing, verification (15 hours)

### Recommended Team (4-5 people, 3 weeks)
- **Tech Lead**: Oversee security fixes, code review (15 hours)
- **Backend Dev 1**: Database & auth security (25 hours)
- **Backend Dev 2**: Input validation & configuration (20 hours)
- **QA/Security**: Testing & verification (20 hours)
- **DevOps** (part-time): Setup CI/CD, monitoring (10 hours)

---

## SUCCESS CRITERIA

✅ All 7 CRITICAL issues fixed and tested  
✅ All 6 HIGH priority issues fixed  
✅ Zero SQL injection vulnerabilities  
✅ All admin pages protected  
✅ All passwords hashed  
✅ No hardcoded credentials  
✅ Security tests passing  
✅ Penetration tests passing  
✅ Deployment checklist complete  

---

## ROLLBACK PLAN

If issues discovered after deployment:
1. Immediately take site offline
2. Restore from pre-deployment backup
3. Investigate issue in staging environment
4. Retest fix
5. Deploy again with monitoring enabled

---

**Document Version:** 1.0  
**Last Updated:** June 4, 2026  
**Status:** Ready for Implementation

