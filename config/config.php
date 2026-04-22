<?php
ob_start();

$_host   = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = str_contains($_host, 'localhost');
$isLive  = str_contains($_host, 'barakaawards.tuqiohub.africa');
// default = also live (any other host routes to production)

// ─── Site ──────────────────────────────────────────────────────────────────
define("SITE_URL", $isLocal ? "http://localhost/baraka-awards" : "https://barakaawards.tuqiohub.africa");
define("SITE_NAME",   "Baraka Awards Kenya");
define("ADMIN_EMAIL",       "info@barakaawards.tuqiohub.africa");
define("SITE_PHONE", "+254710388288"); // WhatsApp & calls

// ─── Social ────────────────────────────────────────────────────────────────
define("SOCIAL_FACEBOOK",  "#");
define("SOCIAL_INSTAGRAM", "#");
define("SOCIAL_TWITTER",   "#");
define("SOCIAL_LINKEDIN",  "#");
define("SOCIAL_TIKTOK",    "#");

// ─── OG image ──────────────────────────────────────────────────────────────
define("OG_IMAGE", $isLocal
    ? "http://localhost/baraka-awards/assets/images/og/baraka-og.webp"
    : "https://barakaawards.tuqiohub.africa/assets/images/og/baraka-og.webp");

// ─── API ────────────────────────────────────────────────────────────────────
define("API_BASE", $isLocal ? "http://localhost:8000" : "https://platform.tuqiohub.africa");
define("API_STORAGE", API_BASE . "/storage/");

// ─── Admin (organizer platform) ────────────────────────────────────────────
define("ADMIN_URL", API_BASE . "/login");

// ─── Baraka Awards branding ────────────────────────────────────────────────
define("BRAND_PRIMARY", "#1a0a2e"); // deep purple
define("BRAND_ACCENT",  "#d4af37"); // gold

// ─── Client slug (used in API calls) ───────────────────────────────────────
define("CLIENT_SLUG", "baraka-awards");

// ─── API helper (GET) ──────────────────────────────────────────────────────
function tuqio_api(string $path): array {
    $url = API_BASE . $path;
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 6,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
    return json_decode($body ?: '[]', true) ?? [];
}

// ─── API helper (POST) ─────────────────────────────────────────────────────
function tuqio_api_post(string $path, array $data): array {
    $ch = curl_init(API_BASE . $path);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: application/json'],
    ]);
    $body   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $result = json_decode($body ?: '{}', true) ?? [];
    $result['_http_status'] = $status;
    return $result;
}
