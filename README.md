# 🧳 Travel India — Tour Booking & Hotel Reservation System

> A full-stack PHP & MySQL web application for booking domestic & international tour packages, reserving hotels, processing secure payments, and managing admin approval workflows.

---

## 📖 Overview

**Travel India** is a comprehensive travel management and hotel reservation platform designed to streamline travel planning for tourists and provide full administrative control to site operators.

The platform enables users to browse curated domestic and international tour packages, reserve hotel rooms, verify accounts via OTP emails, and complete payments securely using Razorpay. On the administrative side, site operators can approve or cancel bookings, manage tour/hotel catalogs, track payments, and review customer feedback.

---

## ✨ Key Features

- 🗺️ **Domestic & International Tour Packages**: Explore and book destination packages tailored for individuals, families, or groups across India and global destinations.
- 🏨 **Hotel Reservation Engine**: View hotel listings with real-time address details, pricing per night, room counts, amenities (WiFi, Pool, Dining), and direct booking options.
- 🔒 **OTP-Based User Authentication**: Secure registration and password recovery using OTP email verification powered by PHPMailer and Brevo SMTP.
- 💳 **Razorpay Payment Integration**: Integrated payment checkout flow supporting digital transactions with pending-state transaction management.
- ⚙️ **Admin Management Portal**: Centralized administrative dashboard to approve/cancel user bookings, add/update packages and hotels, and audit user records.
- 💬 **Customer Feedback & Ratings**: Interactive feedback system allowing travelers to submit reviews, ratings, and support inquiries.

---

## 🛠️ Feature Overview

| Feature | Capabilities | Key Modules |
|---|---|---|
| **Tour Booking** | Destination search, price calculation based on person count, and date selection | `book_files/book_tour.php`, `book_files/book_form.php` |
| **Hotel Reservations** | Hotel catalog browsing, amenity tags, room selection, and direct booking | `book_files/book_hotel.php`, `admin/add_hotels.php` |
| **Authentication & OTP** | User signup, email verification, password reset, and session management | `Authentication/otp_verify.php`, `Authentication/password_reset.php` |
| **Payment Processing** | Razorpay checkout modal integration and payment status tracking | `book_files/payment/razorpay.php`, `book_files/payment/pending.php` |
| **Admin Control** | Approval/cancellation workflow for bookings, catalog management, user audit | `admin/booking_approvel/`, `admin/user_data.php` |
| **Feedback System** | User review submission, inquiry ticketing, and admin feedback reports | `config/feedback.php`, `admin/feedbackdata.php` |

---

## 🧠 Technical Architecture

### 🔑 1. Session Management & Role Security
* **User Authentication**: Stateful PHP session management tracking logged-in users (`user_auth_acces.php`).
* **Admin Guard Middleware**: Restricted administrative access (`config/admin_guard.php`) protecting sensitive dashboard endpoints from unauthorized requests.

### 🗄️ 2. Centralized Database Layer
* **Database Connection Pool**: Single, reusable MySQLi connection instance (`config/connection.php`) driven by `.env` environment configuration.
* **Relational Schema**: Structured MariaDB/MySQL database (`major_project.sql`) enforcing foreign keys and state tracking across 10 tables (`users`, `booking`, `hotel_booking`, `payment`, `tour_package`, `create_hotel`, etc.).

### 📧 3. Email Notification Pipeline
* **PHPMailer & Brevo SMTP Integration**: Centralized email configuration (`config/email_config.php`) handling transactional emails for registration OTPs and password resets.

### 💳 4. Razorpay Payment Gateway & Order Tracking
* **Razorpay Checkout**: Seamless integration for online payments with database recording.
* **Pending Transaction Recovery**: Status verification (`book_files/payment/pending.php`) ensuring transaction integrity during payment gateway callbacks.

---

## 📂 Project Structure

```text
travel_india-new/
├── admin/                      # Administrative portal
│   ├── booking_approvel/       # Booking approval & cancellation logic
│   │   ├── book.php            # Tour booking status handler
│   │   └── hotel_book.php      # Hotel booking status handler
│   ├── add_hotels.php          # Add hotel form
│   ├── add_packages.php        # Add domestic package form
│   ├── add_intern_package.php # Add international package form
│   ├── hotellist.php           # Hotel inventory management
│   ├── tourlist.php            # Tour package catalog management
│   ├── user_data.php           # Registered user management
│   └── feedbackdata.php        # Customer feedback & reviews
│
├── Authentication/             # Authentication & OTP flows
│   ├── otp_verify.php          # Email OTP verification
│   ├── resend_otp.php          # Resend OTP handler
│   ├── password_reset.php      # Password reset dispatch
│   └── password_change.php     # Password change form
│
├── book_files/                 # Booking & payment execution
│   ├── payment/                # Payment gateway scripts
│   │   ├── razorpay.php        # Razorpay integration
│   │   └── pending.php         # Pending status verification
│   ├── book_tour.php           # Tour package reservation logic
│   ├── book_hotel.php          # Hotel reservation logic
│   └── pay_now.php             # Payment submission screen
│
├── config/                     # System configuration & guards
│   ├── connection.php          # MySQLi connection helper
│   ├── env.php                 # Environment file (.env) parser
│   ├── email_config.php        # PHPMailer & SMTP setup
│   └── admin_guard.php         # Admin authentication middleware
│
├── database/                   # Database schemas
│   └── major_project.sql       # MySQL table structures & initial seed data
│
├── PHPMailer/                  # Transactional email library
├── profile/                    # User account dashboard & booking history
├── International_book/         # International travel package pages
├── Lakshadweep/                # Featured regional destination pages
├── Get_in_Touch/               # User inquiry & feedback forms
└── index.php                   # Public landing page
```

---

## 🚀 Quick Start (Local Setup)

### 📋 Prerequisites
- **PHP**: v8.1 or higher (PHP 8.2 recommended)
- **MySQL / MariaDB**: v10.4+ or MySQL 8.0+
- **Web Server**: Apache / Nginx or PHP built-in server

---

### ⚙️ 1. Environment Setup

1. Copy the example credentials configuration file:
   ```bash
   cp config/credentials.php.example config/credentials.php
   ```
2. Create a `.env` file in the project root with your database settings:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=your_password
   DB_NAME=major_project
   ```
3. Add your **Brevo / SMTP API credentials** in `config/credentials.php`:
   ```php
   define('SMTP_USER', 'your-smtp-username');
   define('SMTP_PASS', 'your-smtp-api-key');
   ```

---

### ⚙️ 2. Database Setup

Import `major_project.sql` into your local MySQL server:

```bash
# Log in to MySQL and create database
mysql -u root -p -e "CREATE DATABASE major_project;"

# Import schema and data
mysql -u root -p major_project < database/major_project.sql
```

---

### 💻 3. Run Locally

Start the built-in PHP development server from the root directory:

```bash
php -S localhost:8000
```

Access the application in your browser at:
```text
http://localhost:8000
```

---

## 🗄️ Database Tables Overview

| Table | Description |
|---|---|
| `users` | User accounts, credentials, contact info, and verification flags |
| `tour_package` | Domestic tour packages (pricing, duration, type, description) |
| `create_intern_package` | International tour packages and destination details |
| `create_hotel` | Hotel directory, room inventory, pricing, address, and amenities |
| `booking` | Tour package reservations with status (`Pending`, `Approved`, `Cancelled`) |
| `hotel_booking` | Hotel reservations linked to user accounts |
| `payment` | Payment transactions, Razorpay IDs, amounts, and tour package details |
| `hotel_payment` | Hotel payment records and transaction statuses |
| `feedback` | Customer ratings, reviews, and inquiry messages |

---

## 🛠️ Built With

- **Backend**: PHP 8.2
- **Database**: MariaDB / MySQL 8.0
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla / AJAX), Bootstrap
- **Email Engine**: PHPMailer + Brevo SMTP
- **Payment Gateway**: Razorpay API

---

<p align="center">Made with ❤️ in India &nbsp;•&nbsp; © 2026 Travel India</p>
