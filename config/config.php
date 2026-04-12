<?php
ob_start();

$_host   = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = str_contains($_host, 'localhost');
$isLive  = str_contains($_host, 'digitallyfitawards.com') || str_contains($_host, 'dfa.tuqiohub.africa');
// default = also live (any other host routes to production)

// ─── Site ──────────────────────────────────────────────────────────────────
define("SITE_URL", $isLocal ? "http://localhost/digitalyfit" : "https://digitallyfitawards.com");
define("SITE_NAME",   "Digitally Fit Awards");
define("ADMIN_EMAIL",       "info@digitallyfitawards.com");
define("SITE_PHONE", "+254709000838"); // Safaricom (also WhatsApp)

// ─── Social ────────────────────────────────────────────────────────────────
define("SOCIAL_FACEBOOK",  "#"); // TODO: add DFA Facebook URL
define("SOCIAL_INSTAGRAM", "#"); // TODO: add DFA Instagram URL
define("SOCIAL_TWITTER",   "#"); // TODO: add DFA Twitter/X URL
define("SOCIAL_LINKEDIN",  "#"); // TODO: add DFA LinkedIn URL
define("SOCIAL_TIKTOK",    "#"); // TODO: add DFA TikTok URL

// ─── OG image ──────────────────────────────────────────────────────────────
define("OG_IMAGE", $isLocal
    ? "http://localhost/digitalyfit/assets/images/og/dfa-og.webp"
    : "https://digitallyfitawards.com/assets/images/og/dfa-og.webp");

// ─── API ────────────────────────────────────────────────────────────────────
define("API_BASE", $isLocal ? "http://localhost:8000" : "https://platform.digitallyfitawards.com");
define("API_STORAGE", API_BASE . "/storage/");

// ─── Admin (organizer platform) ────────────────────────────────────────────
define("ADMIN_URL", API_BASE . "/login");

// ─── DFA branding ──────────────────────────────────────────────────────────
define("BRAND_PRIMARY", "#000000"); // DFA black
define("BRAND_ACCENT",  "#be9b3f"); // DFA gold

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
