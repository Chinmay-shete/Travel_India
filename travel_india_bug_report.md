# 🐛 Travel India (The Real Travel) — Bug Report

> **Project:** `travel_india-new`
> **Audit Date:** 2026-06-03
> **Total Bugs Found:** 42

---

## Summary by Severity

| Severity | Count | Category |
|----------|-------|----------|
| 🔴 CRITICAL | 14 | Security vulnerabilities that can be exploited immediately |
| 🟠 HIGH | 12 | Bugs that break functionality or cause runtime errors |
| 🟡 MEDIUM | 10 | Logic errors, broken navigation, bad UX |
| 🔵 LOW | 6 | Code quality, accessibility, minor HTML issues |

---

## 🔴 CRITICAL — Security Vulnerabilities

### BUG #1: SQL Injection in Registration (index.php)
- **File:** [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L63-L69)
- **Lines:** 63, 69
- **Description:** User inputs (`$fname`, `$lname`, `$email`, `$password`) are inserted directly into SQL queries without parameterization or escaping.
```php
$email_check = "SELECT * FROM users WHERE email = '$email'";
$sql = "INSERT INTO users (...) VALUES('$fname','$lname','$email','$password', ...)";
```
- **Impact:** An attacker can inject arbitrary SQL to dump the entire database, delete records, or bypass authentication.

---

### BUG #2: SQL Injection in Login (other/login.php)
- **File:** [login.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/login.php#L5-L6)
- **Lines:** 5–6
- **Description:** Uses `extract($_POST)` which blindly creates variables from user input, then directly interpolates `$email` and `$password` into SQL.
```php
extract($_POST);
$sql = "select * from users where email='$email' AND password='$password'";
```
- **Impact:** Full SQL injection + variable override attack via `extract()`.

---

### BUG #3: SQL Injection in Multiple Admin Files
- **Files:**
  - [add_hotels.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_hotels.php#L18-L19) — Lines 18–19
  - [edit_user.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/edit_user.php#L6-L20) — Lines 6, 20
  - [user_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/user_data.php#L9) — Line 9 (DELETE via GET)
  - [update_tour.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/update_tour.php#L6-L28) — Lines 6, 28
  - [update_hotel.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/update_hotel.php#L6-L31) — Lines 6, 31
  - [profile.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/profile/profile.php#L12-L41) — Lines 12, 41
  - [book_files/pay_now.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/pay_now.php#L12-L68) — Lines 12, 32, 67–68
  - [book_files/book_form.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_form.php#L9) — Line 9
  - [book_files/book_tour.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_tour.php#L4) — Line 4
  - [book_files/book_hotel.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_hotel.php#L5) — Line 5
- **Description:** All these files use raw `$_GET` / `$_POST` values concatenated directly into SQL strings.
- **Impact:** Every database-touching page is injectable.

---

### BUG #4: Passwords Stored in Plain Text
- **Files:**
  - [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L69) — Line 69 (INSERT)
  - [password_change.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/password_change.php#L11) — Line 11 (UPDATE)
  - [password_reset.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/password_reset.php#L82) — Line 82 (UPDATE)
  - [profile.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/profile/profile.php#L131-L139) — Lines 131, 139 (compare + UPDATE)
- **Description:** Passwords are stored as-is in the database and compared via plain string match. No `password_hash()` / `password_verify()`.
- **Impact:** If the database is breached, all user passwords are immediately readable.

---

### BUG #5: Hardcoded SMTP Credentials (App Password Exposed)
- **Files:**
  - [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L21-L22) — Line 22
  - [config/feedback.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/config/feedback.php#L34-L35) — Line 35
  - [Authentication/password_reset.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/password_reset.php#L19-L20) — Line 20
  - [Authentication/resend_otp.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/resend_otp.php#L21-L22) — Line 22
  - [Get_in_Touch/contact.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Get_in_Touch/contact.php#L34-L35) — Line 35
- **Description:** Gmail app password `olfq duvu rucq tvsv` is hardcoded in source code committed to Git.
- **Impact:** Anyone with repo access can send emails as the application's account or use it for spam/phishing.

---

### BUG #6: Razorpay API Key Exposed in Source
- **File:** [International_book/razorpay.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/International_book/razorpay.php#L5)
- **Line:** 5
- **Description:** `$apikey = "rzp_test_Pl81xvWKLN0yIB";` is hardcoded. Even though it's a test key, this pattern will leak real keys in production.
- **Impact:** Exposed payment credentials.

---

### BUG #7: DELETE via GET Request Without CSRF Protection
- **File:** [admin/user_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/user_data.php#L8-L16)
- **Lines:** 8–16
- **Description:** User records are deleted via a simple GET link: `user_data.php?ID=5`. No CSRF token, no confirmation, no POST method.
```php
$sql = "DELETE FROM users WHERE user_Id = " . $_GET['ID'];
```
- **Impact:** An attacker can trick an admin into clicking a link that deletes any user. Browser prefetch could also trigger deletions.

---

### BUG #8: No Authentication / Authorization Guards on Any Page
- **Files:**
  - [admin/adminhomepage.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/adminhomepage.php) — No session check
  - [admin/user_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/user_data.php) — No session check
  - [admin/add_packages.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_packages.php) — No session check
  - [admin/add_hotels.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_hotels.php) — No session check
  - [admin/edit_user.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/edit_user.php) — No session check
  - [config/user_auth_acces.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/config/user_auth_acces.php) — **Completely empty** (5 lines, no code)
- **Description:** The file `user_auth_acces.php` was intended to be an auth guard but is completely empty. No admin page checks if the user is logged in or is actually an admin.
- **Impact:** Anyone can directly access `/admin/adminhomepage.php`, `/admin/user_data.php`, etc. and perform admin actions (add/edit/delete packages, hotels, users).

---

### BUG #9: Anyone Can Register as Admin
- **File:** [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L359-L365)
- **Lines:** 359–365
- **Description:** The signup form has a `<select>` dropdown for `user_type` with `<option value="admin">Admin</option>`. Any user can select "Admin" during registration.
- **Impact:** Complete privilege escalation — anyone can create an admin account.

---

### BUG #10: XSS via Unescaped User Output
- **Files:**
  - [config/alert.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/config/alert.php#L4-L9) — echoes session data without `htmlspecialchars()`
  - [admin/user_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/user_data.php#L125-L129) — outputs user fname, lname, email directly
  - [other/Book_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/Book_data.php#L194-L205) — outputs booking data directly
  - [profile/profile.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/profile/profile.php#L92-L107) — outputs user data into form `value` attributes
- **Description:** Database values are echoed into HTML without any sanitization. If a user registers with `<script>alert('XSS')</script>` as their name, it will execute.
- **Impact:** Stored XSS — can steal admin cookies, redirect users, or deface the site.

---

### BUG #11: Unrestricted File Upload
- **Files:**
  - [admin/add_hotels.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_hotels.php#L14-L17) — Lines 14–17
  - [admin/update_tour.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/update_tour.php#L23-L26) — Lines 23–26
  - [admin/update_hotel.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/update_hotel.php#L26-L29) — Lines 26–29
- **Description:** Uploaded files are saved with their original name, no file type validation, no size check, no renaming.
```php
$file = $_FILES['Hotel_Image']['name'];
$folder = '../hotel_image/' . $file;
move_uploaded_file($tempname, $folder);
```
- **Impact:** An attacker can upload a `.php` shell file and execute arbitrary code on the server.

---

## 🟠 HIGH — Functionality-Breaking Bugs

### BUG #12: Double `session_start()` Causes Warning/Error
- **File:** [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L2-L202)
- **Lines:** 2 (via `connection.php`), 202
- **Description:** `connection.php` calls `session_start()` on line 2. Then `index.php` includes `connection.php` at line 2 and calls `session_start()` again at line 202 inside the login block. This triggers a PHP warning: "session already started".
- **Impact:** If `error_reporting(0)` is removed, the login form will display a visible PHP warning.

---

### BUG #13: Broken Cancel Logic in Book_data.php
- **File:** [other/Book_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/Book_data.php#L265-L279)
- **Lines:** 265–278
- **Description:** The legacy cancel handler on lines 265–278 uses `$row['id']` which is undefined at that point (the `while` loop ended, so `$row` holds the last row or `null`). This code will either cancel the wrong booking or fail silently.
```php
$sqlup = "UPDATE booking SET Status='Cancelled' where id = " . $row['id'];
```
- **Impact:** The form-based cancel (to `cancel_booking.php`) works, but this inline code block would silently corrupt data if ever reached.

---

### BUG #14: `bind_param` Count Mismatch in add_packages.php
- **File:** [admin/add_packages.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_packages.php#L27)
- **Line:** 27
- **Description:** The query has 8 placeholders `(?, ?, ?, ?, ?, ?, ?, ?)` but `bind_param` specifies `"ssssssss"` (8 types) with 8 variables. However, the type string `"ssssssss"` has 8 `s` chars and 8 variables are passed — this actually matches. **BUT** the `Price` field is bound as string `"s"` when it should be `"d"` or `"i"` for a number.
- **Impact:** Price may be stored incorrectly or cause type-related query failures on stricter MySQL modes.

---

### BUG #15: OTP Sent in Hidden Form Field — Leaks OTP to Client
- **File:** [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L345)
- **Line:** 345
- **Description:** The OTP is placed in a hidden input field: `<input type="hidden" name="otp" value="<?php echo "$act_str"; ?>">`. This means the OTP is visible in the page source to anyone — defeating the purpose of email verification.
- **Impact:** A user can inspect the page source, read the OTP, and verify without ever checking their email.

---

### BUG #16: `$_POST` Values Used Outside of POST Check
- **File:** [book_files/pay_now.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/pay_now.php#L85-L91)
- **Lines:** 85–91
- **Description:** Lines 85–91 access `$_POST['no_of_person']`, `$_POST['Price']`, `$_POST['destination']` outside of any `if(isset($_POST['submit']))` check. On initial page load (GET request), these are all `null`.
```php
$person = $_POST['no_of_person']; //3
$Package_Price = $_POST['Price']; // 2000 * 3 = 6000 * 2
$Total_Price = $person * $Package_Price * $duration;
```
- **Impact:** `$Total_Price` will be `0` on page load, and the price field will display `0` instead of the actual price. `error_reporting(0)` hides the undefined index warnings.

---

### BUG #17: Logout Does Not Actually Destroy Session Data Properly
- **File:** [other/homepage.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/homepage.php#L76)
- **Line:** 76
- **Description:** The "logout" link just navigates to `../index.php` — it does not call `config/logout.php` which has `session_destroy()`. This means the user's session persists after "logging out".
- **Impact:** Users remain logged in even after clicking logout. Session hijacking window stays open.

---

### BUG #18: Login Doesn't Set Session for Admin
- **File:** [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L228-L230)
- **Lines:** 228–230
- **Description:** When an admin logs in, the code redirects to `admin/adminhomepage.php` but never sets `$_SESSION["email"]`. For regular users, `$_SESSION["email"] = $email` is set on line 222, but for admins, this line is missing.
- **Impact:** Admin session has no identity — any page that depends on `$_SESSION["email"]` will fail for admins.

---

### BUG #19: `book_form.php` Uses `$_GET['Id']` Before Checking If It Exists
- **File:** [book_files/book_form.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_form.php#L5-L8)
- **Lines:** 5–8
- **Description:** Line 5 accesses `$_GET['Id']` directly, and line 6 stores it in `$_SESSION`. The `isset()` check is only on line 8 — AFTER the value has already been used.
```php
$TourPackage = $_GET['Id'];           // Used BEFORE check
$_SESSION["TourPackage_Id"] = $TourPackage;
if (isset($_GET['Id'])) {             // Check AFTER use
```
- **Impact:** Undefined index notice + wrong session value when `Id` is not provided.

---

### BUG #20: Status Check Inconsistency (String vs Integer)
- **Files:**
  - [other/login.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/login.php#L11-L17) — Checks `$row['status'] == 1` (integer)
  - [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L221-L229) — Checks `$row['status'] == 'active'` (string)
- **Description:** Two different login flows compare the `status` column differently: one uses integer `1`, the other uses string `'active'`. Only one can be correct based on the database schema.
- **Impact:** One of the two login paths will always fail to recognize verified users.

---

### BUG #21: Password Reset URL Points to Wrong Port
- **File:** [Authentication/password_reset.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/password_reset.php#L31)
- **Line:** 31
- **Description:** The reset link is hardcoded to `http://localhost:3000/Authentication/password_change.php?...` but `connection.php` uses MySQL port `3380`, suggesting the PHP server may run on a different port. The link doesn't use a dynamic base URL.
- **Impact:** Password reset links may be broken if the server runs on any port other than `3000`.

---

### BUG #22: `password_change.php` Doesn't Validate Reset Token
- **File:** [Authentication/password_change.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/password_change.php#L5-L23)
- **Lines:** 5–23
- **Description:** The form accepts `email` and `new_password` via POST, then updates the password with:
```php
$email = $_REQUEST['email'];
$reset_pwd = mysqli_query($conn, "UPDATE users SET password ='$pwd' WHERE email = '$email'");
```
There is no verification that the `verify_token` in the URL matches the `activation_code` in the database.
- **Impact:** Anyone can reset any user's password by simply POSTing to this page with any email.

---

### BUG #23: `connection.php` uses Non-standard MySQL Port
- **File:** [config/connection.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/config/connection.php#L7)
- **Line:** 7
- **Description:** `$servername = "localhost:3380"` — MySQL default is `3306`. If this is intentional for a custom setup, it will break on any standard MySQL/XAMPP installation.
- **Impact:** Connection failure on any machine that runs MySQL on the default port.

---

## 🟡 MEDIUM — Logic Errors & Broken Navigation

### BUG #24: `resend_otp.php` — Broken `alert.php` Include Path
- **File:** [Authentication/resend_otp.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/resend_otp.php#L122)
- **Line:** 122
- **Description:** `<?php include("config/alert.php"); ?>` — but the file is in the `Authentication/` directory, so the correct path should be `../config/alert.php`.
- **Impact:** Alert messages (error/status) won't display on the resend OTP page.

---

### BUG #25: `resend_otp.php` — Back Link Points to Non-existent File
- **File:** [Authentication/resend_otp.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/resend_otp.php#L104)
- **Line:** 104
- **Description:** `<a href="index.php">Back</a>` — there is no `index.php` inside the `Authentication/` folder. Should be `../index.php`.
- **Impact:** Clicking "Back" leads to a 404 error.

---

### BUG #26: `homepage.php` — Session Email Not Echoed
- **File:** [other/homepage.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/homepage.php#L59)
- **Line:** 59
- **Description:** `<?php $_SESSION["email"]; ?>` — this evaluates the expression but doesn't echo it. Should be `<?php echo $_SESSION["email"]; ?>`.
- **Impact:** The user's email doesn't appear in the navigation — appears blank.

---

### BUG #27: `homepage.php` — Unclosed PHP `while` Loop
- **File:** [other/homepage.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/homepage.php#L69-L84)
- **Lines:** 69, 81–84
- **Description:** A `while` loop starts on line 69 to iterate over users, but the closing `}` is inside a commented-out block (lines 81–84). The loop never properly closes.
```php
while ($row = mysqli_fetch_assoc($result)) {
?>
<!-- ... HTML ... -->
<!-- <?php } } ?> -->  ← This is commented out!
```
- **Impact:** Only the first user row is displayed. If there are multiple matches, behavior is undefined.

---

### BUG #28: `book_hotel.php` — References Wrong Column
- **File:** [book_files/book_hotel.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_hotel.php#L109)
- **Line:** 109
- **Description:** `<?php echo $data['Package_Details']; ?>` — but the query selects from `create_hotel` table, which doesn't have a `Package_Details` column. This should likely be `amenities` or a hotel description field.
- **Impact:** Displays nothing or causes a PHP notice.

---

### BUG #29: Timezone Set to "Asia/Karachi" (Pakistan) Instead of India
- **Files:**
  - [Authentication/otp_verify.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/otp_verify.php#L3) — Line 3
  - [Authentication/otp_2.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/otp_2.php#L3) — Line 3
- **Description:** `date_default_timezone_set("Asia/Karachi")` — for a "Travel India" website, this should be `"Asia/Kolkata"`. The 30-minute timezone offset means OTP expiry checks happen at the wrong time.
- **Impact:** OTPs may expire too early or too late (30 min difference).

---

### BUG #30: `otp_2.php` — Missing OTP Expiry Check
- **File:** [Authentication/otp_2.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/otp_2.php#L28-L42)
- **Lines:** 28–42
- **Description:** Unlike `otp_verify.php` (which checks if the OTP expired after 1 minute), `otp_2.php` skips the time check entirely — the time variables are created but the `if(date(...) >= $timeup)` block is removed.
- **Impact:** Resent OTPs never expire — they can be used days later.

---

### BUG #31: OTP Expiry Set to Only 1 Minute
- **File:** [Authentication/otp_verify.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/otp_verify.php#L22)
- **Line:** 22
- **Description:** `date_modify($row_signup_time, "+1 minutes")` — OTP expires in just 1 minute. Considering email delivery delays, most users won't be able to verify in time.
- **Impact:** Users frequently see "Your time is up" and can never verify their accounts.

---

### BUG #32: Email Newsletter Form Does Nothing
- **Files:**
  - [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L471-L474) — Lines 471–474
  - [other/homepage.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/homepage.php#L159-L162) — Lines 159–162
- **Description:** The "Stay in the know" email form has `action=""` and no server-side handler — submitting it just reloads the page.
- **Impact:** Emails entered are silently lost.

---

### BUG #33: `book_tour.php` / `book_hotel.php` — "Book now" Buttons Don't Link Anywhere
- **Files:**
  - [book_files/book_tour.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_tour.php#L211) — Line 211
  - [book_files/book_hotel.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_hotel.php#L210) — Line 210
- **Description:** The "Book now" buttons at the bottom of the page (`<button>Book now</button>`) have no `href` link, no form action, and no JavaScript handler.
- **Impact:** Clicking "Book now" does nothing.

---

## 🔵 LOW — Code Quality & HTML Issues

### BUG #34: Duplicate HTML `id` Attributes
- **Files:**
  - [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L252-L253) — Lines 252–253: Two buttons with `id="xyz"`
  - [book_files/pay_now.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/pay_now.php#L141-L162) — Multiple inputs with `id="name"`
  - [book_files/book_form.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_form.php#L71-L106) — Multiple inputs with `id="name"`
- **Description:** HTML `id` attributes must be unique per page. Duplicate IDs break `document.getElementById()` and accessibility.
- **Impact:** JavaScript selectors and accessibility tools will malfunction.

---

### BUG #35: Double `type` Attribute on Input
- **File:** [Authentication/password_change.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/password_change.php#L79)
- **Line:** 79
- **Description:** `<input type="password" type="password" name="cpassword" ...>` — the `type` attribute is specified twice.
- **Impact:** Invalid HTML; the second `type` is ignored by most browsers, but it's technically malformed.

---

### BUG #36: Double `class` Attribute on Button
- **Files:**
  - [book_files/pay_now.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/pay_now.php#L167) — Line 167
  - [book_files/book_form.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_form.php#L108) — Line 108
- **Description:** `<button class="button-part1" type="submit" class="submit-btn">` — two `class` attributes. Only the first is used; the second is silently ignored.
- **Impact:** Missing CSS styles from the second class.

---

### BUG #37: `error_reporting(0)` Hides All Errors
- **Files:**
  - [index.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/index.php#L3)
  - [admin/add_packages.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_packages.php#L3)
  - [admin/add_hotels.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_hotels.php#L4)
  - [admin/user_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/user_data.php#L3)
  - [admin/edit_user.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/edit_user.php#L3)
  - [admin/update_tour.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/update_tour.php#L3)
  - [admin/update_hotel.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/update_hotel.php#L3)
  - [book_files/pay_now.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/pay_now.php#L4)
  - [book_files/book_form.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/book_files/book_form.php#L3)
  - [Authentication/resend_otp.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Authentication/resend_otp.php#L3)
  - [other/Book_data.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/other/Book_data.php#L3)
- **Description:** 11 files suppress ALL PHP errors/warnings/notices. This masks real bugs and makes debugging impossible.
- **Impact:** You won't see SQL errors, undefined variables, or type mismatches — bugs go undetected in production.

---

### BUG #38: Razorpay Auto-Click Workaround
- **File:** [International_book/razorpay.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/International_book/razorpay.php#L32-L43)
- **Lines:** 32–43
- **Description:** The Razorpay payment button is hidden with CSS (`display: none`) and auto-clicked with jQuery:
```javascript
$('.razorpay-payment-button').click();
```
This is a fragile workaround. If jQuery loads slowly or the Razorpay script fails, the payment form becomes invisible and unusable.
- **Impact:** Users may see a blank page with no way to pay.

---

### BUG #39: "massage" Typo (should be "message")
- **Files:**
  - [config/feedback.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/config/feedback.php#L14-L16)
  - [Get_in_Touch/contact.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/Get_in_Touch/contact.php#L13-L15)
- **Description:** The POST field and database column are named `massage` instead of `message` throughout the feedback/contact forms and database schema.
- **Impact:** While consistent internally, this is a pervasive data model typo that will confuse future developers.

---

### BUG #40: `profile.php` — Current Password Input Has Wrong Type
- **File:** [profile/profile.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/profile/profile.php#L154)
- **Line:** 154
- **Description:** `<input type="number" id="current-password" name="current_password" ...>` — the current password field is `type="number"`, which means it won't accept text passwords and shows increment/decrement arrows.
- **Impact:** Users with non-numeric passwords cannot type their current password to change it.

---

### BUG #41: Spaces in PHP Filenames Cause URL Issues
- **Files:** `html/New Jersey.php`, `html/Orange County.php`, `html/Salt Lake City.php`, `before_Login/New Jersey.php`, etc.
- **Description:** Filenames with spaces require URL encoding (`%20`) which many servers and configurations don't handle consistently.
- **Impact:** Broken links on certain server configurations or when shared as URLs.

---

### BUG #42: `add_hotels.php` — Leading Space in Image Path
- **File:** [admin/add_hotels.php](file:///Users/chinu/Developer/VS%20CODE%20NOT%20IMP/travel_india-new/admin/add_hotels.php#L19)
- **Line:** 19
- **Description:** In the SQL: `'$amenities',' $folder'` — there's a leading space before `$folder`. This means the hotel image path stored in the database will be ` ../hotel_image/photo.jpg` (with a leading space), causing image `<img src>` lookups to fail.
- **Impact:** All hotel images will appear broken because the path has an extra space.

---

## Summary — Top 5 Fixes to Prioritize

| Priority | Bug # | What to Fix |
|----------|-------|-------------|
| 1 | #8, #9 | Add authentication guards to ALL pages + remove admin option from signup |
| 2 | #1–#3 | Use prepared statements (parameterized queries) everywhere |
| 3 | #4 | Hash passwords with `password_hash()` and verify with `password_verify()` |
| 4 | #5, #6 | Move credentials to environment variables (`.env` file) |
| 5 | #11 | Validate file uploads (whitelist extensions, rename files, check MIME type) |
