## Travel India – PHP/MySQL Application

A complete PHP/MySQL web application for browsing Indian and international travel packages, hotel listings, and booking trips with email OTP authentication and Razorpay payments. Includes an admin panel for managing users, packages, hotels, and bookings.

### Key Features
- Browsing of domestic and international tour packages and hotels
- Booking flows for tours and hotels with payment (Razorpay integration)
- Email-based OTP verification, password reset, and account security
- Admin dashboard to add/update packages and hotels, view users, and manage bookings
- Contact form and feedback handling

### Tech Stack
- PHP 8+ (procedural style)
- MySQL/MariaDB
- HTML/CSS/JS (vanilla)
- PHPMailer (bundled)
- Razorpay Checkout (client + server integration)

---

## Project Structure
High-level directories you’ll interact with most:
- `index.php` – Application entry point (landing/home)
- `admin/` – Admin panel pages (add/update/list packages/hotels, booking approvals)
- `Authentication/` – OTP, password reset, email verification flows
- `book_files/` – Domestic booking flows and Razorpay payment handlers
- `International_book/` – International booking/payment flows
- `Lakshadweep/` – Example destination module with its own booking/payment pages
- `config/` – Database connection, auth access guard, feedback and reusable alerts
- `database/major_project.sql` – Database schema and seed data
- `PHPMailer/` – PHPMailer library (self-contained)
- `css/`, `js/`, `image/`, `hotel_image/` – Static assets

---

## Prerequisites
- PHP 8.1+ (CLI and web SAPI)
- MySQL 8+ (or MariaDB equivalent)
- A local web server (any of the below):
  - Apache/Nginx with PHP configured (e.g., XAMPP, MAMP)
  - or the built-in PHP server for local development

Optional (for production-like flows):
- Razorpay account & API keys
- SMTP credentials for sending emails (or a local mailcatcher)

---

## Setup
1) Clone or download the repository into your web root.

2) Create a MySQL database (example: `travel_india`).

3) Import the schema and sample data:
   - Open your MySQL client (phpMyAdmin, TablePlus, CLI)
   - Import file: `database/major_project.sql`

4) Configure database connection:
   - Open `config/connection.php`
   - Set your DB host, database name, username, and password

5) Configure email (PHPMailer):
   - PHPMailer is bundled under `PHPMailer/`.
   - If you use SMTP, ensure the SMTP host, port, username, password, and from-address are set wherever PHPMailer is initialized in your auth flows (e.g., `Authentication/otp_2.php`, `Authentication/verify_email.php`, or shared config if present in your setup).

6) Configure Razorpay (payments):
   - Update your Razorpay Key ID/Secret in the payment handlers:
     - `book_files/payment/razorpay.php`
     - `International_book/razorpay.php`
     - `Lakshadweep/razorpay.php`
   - Ensure callbacks/success/failure routes in these files match your base URL.

7) Run locally:
   - Using Apache/Nginx: Point your VirtualHost/Server Block document root to this project directory. Visit `http://localhost/` (or your configured host).
   - Using PHP built-in server (development only):
     - In the project root, run:
       ```bash
       php -S localhost:8000 -t .
       ```
    - Open `http://localhost:8000/` in your browser.

---

## How to Run the App (Step-by-step)

### Option A: Quick Start with PHP Built-in Server
1) Ensure you completed the Setup steps (DB import + `config/connection.php`).
2) Start the server from the project root:
   ```bash
   php -S localhost:8000 -t .
   ```
3) Open these URLs to verify:
- Home: `http://localhost:8000/index.php`
- Admin: `http://localhost:8000/admin/adminhomepage.php`
- Example booking: `http://localhost:8000/book_files/book_form.php`

Notes:
- Built-in server is for development only; do not use in production.
- If you see DB errors, re-check credentials in `config/connection.php` and confirm the SQL import.

### Option B: XAMPP/MAMP/Apache or Nginx
1) Move or symlink the project directory into your web root:
- XAMPP (macOS): `/Applications/XAMPP/xamppfiles/htdocs`
- XAMPP (Windows): `C:\\xampp\\htdocs`
- MAMP: `/Applications/MAMP/htdocs`
- Apache/Nginx (custom): your configured document root

2) Access the site:
- If the folder name is `travel_india-new`, visit:
  - `http://localhost/travel_india-new/index.php`
  - Admin: `http://localhost/travel_india-new/admin/adminhomepage.php`

3) Optional friendly host:
- Add an entry to `/etc/hosts` (macOS/Linux) or `C:\\Windows\\System32\\drivers\\etc\\hosts`:
  ```
  127.0.0.1   travel.local
  ```
- Point your VirtualHost/Server Block to this project and then open `http://travel.local/`.

4) Ensure required PHP extensions are enabled:
- `mysqli`, `openssl`, `mbstring`, `json`, `curl`

### Verifying Core Flows
- OTP/email: trigger via pages under `Authentication/` (e.g., `otp_2.php`, `verify_email.php`) and check your SMTP or mailcatcher logs.
- Payments: use Razorpay Test Mode and try a test booking from `book_files/book_form.php` or `International_book/book_form.php`.
- Admin CRUD: add/update a sample package via `admin/add_packages.php` and confirm it appears under listings.

---

## Admin Panel
- Admin pages are under `admin/` (e.g., `admin/adminhomepage.php`).
- From there you can:
  - Add/update tour packages (`add_packages.php`, `update_tour.php`)
  - Add/update international packages (`add_intern_package.php`, `update_intern.php`)
  - Add/update hotels (`add_hotels.php`, `update_hotel.php`)
  - Review bookings (`booking_approvel/`)
  - View users and feedback (`user_data.php`, `feedbackdata.php`)

Initial credentials are not hardcoded in this README. Create an admin user directly in the database if not already present, or adapt the login form to accept your first admin.

---

## Authentication & Security
- OTP verification and password reset flows live under `Authentication/`.
- Ensure your email delivery is configured properly to send OTPs and verification links.
- Review `config/user_auth_acces.php` for session checks/guards applied to protected pages.

---

## Payments
- Razorpay integration is implemented for bookings.
- Update your API keys and verify the success/callback endpoints.
- Use Razorpay Test Mode keys for development.

---

## Common Paths
- Domestic booking forms: `book_files/`
- International bookings: `International_book/`
- Destination-specific module example: `Lakshadweep/`
- Public pages (pre-login): `before_Login/` and `html/`

---

## Environment Configuration Tips
- Keep production credentials (DB, SMTP, Razorpay) out of source control.
- Consider extracting sensitive values into environment variables and reading them in `config/connection.php` and mail/payment initializers.
- Enforce HTTPS in production and configure proper CORS/headers if hosting behind a proxy/CDN.

---

## Troubleshooting
- White screen / errors:
  - Enable errors temporarily in PHP (`display_errors=On`) or add:
    ```php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ```
  - Confirm `config/connection.php` uses valid DB credentials and that the database was imported.
  - Verify file/directory permissions for uploads (if used).
- Emails not sending:
  - Check SMTP credentials/ports and that your host allows outbound SMTP.
  - Try a mailcatcher (MailHog, Papercut) for local development.
- Payments not completing:
  - Ensure Razorpay keys are correct and endpoints match your local/prod URLs.
  - Use browser dev tools/network tab to inspect any JS errors in checkout.

---

## Deployment Notes
- Use Apache or Nginx with PHP-FPM for production deployments.
- Configure a dedicated MySQL instance and update credentials.
- Set appropriate file permissions and disable `display_errors` in production.
- Add caching (OPcache) and a CDN for static assets if needed.

---

## License
No license specified. Add a license if you intend to distribute or open source this project.

---

## Acknowledgements
- PHPMailer (`PHPMailer/` is bundled)
- Razorpay Checkout


