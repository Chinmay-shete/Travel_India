<?php
// Prevent direct access
if (count(get_included_files()) === 1) {
    http_response_code(403);
    exit("Direct access not allowed");
}

// XSS Escaping Helper
if (!function_exists('h')) {
    function h($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// CSRF Generation
if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

// CSRF Hidden Input Field HTML
if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
    }
}

// CSRF Verification
if (!function_exists('csrf_verify')) {
    function csrf_verify() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $token = $_POST['csrf_token'] ?? '';
            $session_token = $_SESSION['csrf_token'] ?? '';
            
            if (empty($session_token) || !hash_equals($session_token, $token)) {
                http_response_code(403);
                die("CSRF verification failed.");
            }
        }
    }
}

// Ensure rate_limits table exists and check/update rate limits
if (!function_exists('check_rate_limit')) {
    function check_rate_limit($conn, $endpoint, $max_attempts = 5, $lockout_time_seconds = 900) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        // Ensure table exists (self-healing migration)
        $conn->query("CREATE TABLE IF NOT EXISTS rate_limits (
            ip_address VARCHAR(45) NOT NULL,
            endpoint VARCHAR(100) NOT NULL,
            attempts INT NOT NULL DEFAULT 1,
            last_attempt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (ip_address, endpoint)
        ) ENGINE=InnoDB");

        // Clean up expired locks
        $stmt = $conn->prepare("DELETE FROM rate_limits WHERE last_attempt < NOW() - INTERVAL ? SECOND");
        $stmt->bind_param("i", $lockout_time_seconds);
        $stmt->execute();
        $stmt->close();

        // Get current attempts
        $stmt = $conn->prepare("SELECT attempts, last_attempt FROM rate_limits WHERE ip_address = ? AND endpoint = ?");
        $stmt->bind_param("ss", $ip, $endpoint);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if ($row) {
            $attempts = $row['attempts'];
            $last_attempt = strtotime($row['last_attempt']);
            
            if ($attempts >= $max_attempts) {
                $time_left = ($last_attempt + $lockout_time_seconds) - time();
                if ($time_left > 0) {
                    return [
                        'allowed' => false,
                        'time_left' => $time_left
                    ];
                } else {
                    // Lock expired, reset
                    $stmt = $conn->prepare("DELETE FROM rate_limits WHERE ip_address = ? AND endpoint = ?");
                    $stmt->bind_param("ss", $ip, $endpoint);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        return ['allowed' => true];
    }
}

if (!function_exists('increment_rate_limit')) {
    function increment_rate_limit($conn, $endpoint) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $conn->prepare("INSERT INTO rate_limits (ip_address, endpoint, attempts, last_attempt) 
                                VALUES (?, ?, 1, CURRENT_TIMESTAMP) 
                                ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = CURRENT_TIMESTAMP");
        $stmt->bind_param("ss", $ip, $endpoint);
        $stmt->execute();
        $stmt->close();
    }
}

if (!function_exists('reset_rate_limit')) {
    function reset_rate_limit($conn, $endpoint) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $conn->prepare("DELETE FROM rate_limits WHERE ip_address = ? AND endpoint = ?");
        $stmt->bind_param("ss", $ip, $endpoint);
        $stmt->execute();
        $stmt->close();
    }
}
