<?php
/**
 * Security Helper Functions
 * Fungsi-fungsi untuk validasi, sanitasi, dan proteksi keamanan
 */

// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate CSRF Token
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF Token
 */
function validateCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || !isset($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input string
 * Mencegah XSS attacks
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 * Mencegah email header injection
 */
function validateEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    // Check for header injection attempts
    $forbidden_chars = ["\r", "\n", "%0a", "%0d", "Content-Type:", "bcc:", "cc:", "to:"];
    foreach ($forbidden_chars as $char) {
        if (stripos($email, $char) !== false) {
            return false;
        }
    }
    
    return $email;
}

/**
 * Validate name field
 * Mencegah header injection dalam email headers
 */
function validateName($name) {
    $name = trim($name);
    
    // Check for header injection attempts
    $forbidden_chars = ["\r", "\n", "%0a", "%0d"];
    foreach ($forbidden_chars as $char) {
        if (stripos($name, $char) !== false) {
            return false;
        }
    }
    
    return sanitizeInput($name);
}
function validatePhone($phone) {
    $phone = trim($phone);
    if (preg_match('/^[0-9\s\+\-\(\)]+$/', $phone)) {
        return sanitizeInput($phone);
    }
    return '';
}

/**
 * Rate Limiting
 * Batasi jumlah request dari IP yang sama
 * Uses file locking to prevent race conditions
 */
function checkRateLimit($max_attempts = 5, $time_window = 3600) {
    // Get IP address, fallback to 'CLI' for command line execution
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CLI';
    $rate_limit_file = __DIR__ . '/rate_limit.json';
    
    // Open file with lock
    $fp = fopen($rate_limit_file, 'c+');
    if (!$fp) {
        logError('Failed to open rate limit file');
        return true; // Allow request if file can't be opened
    }
    
    // Acquire exclusive lock
    if (!flock($fp, LOCK_EX)) {
        fclose($fp);
        logError('Failed to acquire lock on rate limit file');
        return true; // Allow request if lock can't be acquired
    }
    
    // Read current data
    $contents = stream_get_contents($fp);
    $rate_data = !empty($contents) ? json_decode($contents, true) : [];
    if (!is_array($rate_data)) {
        $rate_data = [];
    }
    
    $current_time = time();
    
    // Clean old entries
    foreach ($rate_data as $stored_ip => $data) {
        if ($current_time - $data['first_attempt'] > $time_window) {
            unset($rate_data[$stored_ip]);
        }
    }
    
    // Check current IP
    $allowed = true;
    if (!isset($rate_data[$ip])) {
        $rate_data[$ip] = [
            'count' => 1,
            'first_attempt' => $current_time
        ];
    } else {
        $rate_data[$ip]['count']++;
        
        if ($rate_data[$ip]['count'] > $max_attempts) {
            $allowed = false;
        }
    }
    
    // Write updated data back to file
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($rate_data));
    
    // Release lock and close file
    flock($fp, LOCK_UN);
    fclose($fp);
    
    return $allowed;
}

/**
 * Validate redirect URL
 * Mencegah open redirect vulnerability
 */
function validateRedirectUrl($url) {
    if (empty($url)) {
        return '';
    }
    
    // Only allow relative URLs or same domain
    $parsed = parse_url($url);
    
    // If it's a relative URL (starts with /)
    if (isset($parsed['path']) && !isset($parsed['host'])) {
        return sanitizeInput($url);
    }
    
    // If it has a host, check if it's the same domain
    if (isset($parsed['host'])) {
        // Get current host safely with validation
        $current_host = '';
        if (isset($_SERVER['HTTP_HOST'])) {
            // Validate HTTP_HOST format
            $current_host = preg_replace('/[^a-zA-Z0-9\.\-:]/', '', $_SERVER['HTTP_HOST']);
        }
        
        // Also check against SERVER_NAME as fallback
        if (empty($current_host) && isset($_SERVER['SERVER_NAME'])) {
            $current_host = $_SERVER['SERVER_NAME'];
        }
        
        // Whitelist of allowed domains (add your domains here)
        $allowed_hosts = [
            $current_host,
            'mfarrasmajid.id',
            'www.mfarrasmajid.id',
            'localhost',
            '127.0.0.1'
        ];
        
        if (in_array($parsed['host'], $allowed_hosts, true)) {
            return sanitizeInput($url);
        }
    }
    
    // Default: don't redirect
    return '';
}

/**
 * Send JSON response
 */
function sendJsonResponse($alert, $message) {
    header('Content-Type: application/json');
    echo json_encode([
        'alert' => $alert,
        'message' => $message
    ]);
    exit();
}

/**
 * Log error securely
 */
function logError($message) {
    $log_file = __DIR__ . '/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CLI';
    $log_message = "[{$timestamp}] IP: {$ip} - {$message}\n";
    error_log($log_message, 3, $log_file);
}
