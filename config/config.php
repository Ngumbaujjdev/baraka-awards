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
define("SOCIAL_FACEBOOK",  "https://www.facebook.com/barakaawards");
define("SOCIAL_INSTAGRAM", "https://www.instagram.com/barakaawardske");
define("SOCIAL_TWITTER",   "https://twitter.com/barakaawards");
define("SOCIAL_LINKEDIN",  "");
define("SOCIAL_TIKTOK",    "https://www.tiktok.com/@barakaawardske");

// ─── OG image ──────────────────────────────────────────────────────────────
define("OG_IMAGE", $isLocal
    ? "http://localhost/baraka-awards/assets/images/og/baraka-og.webp"
    : "https://barakaawards.tuqiohub.africa/assets/images/og/baraka-og.webp");

// ─── API ────────────────────────────────────────────────────────────────────
define("API_BASE",          $isLocal ? "http://localhost:8000" : "https://tuqio.hekimaconsult.co.ke");
define("API_BASE_FALLBACK", $isLocal ? "http://localhost:8000" : "https://platform.tuqiohub.africa");
define("API_STORAGE", API_BASE . "/storage/");

// ─── Admin (organizer platform) ────────────────────────────────────────────
define("ADMIN_URL", API_BASE . "/login");

// ─── Baraka Awards branding ────────────────────────────────────────────────
define("BRAND_PRIMARY", "#1a0a2e"); // deep purple
define("BRAND_ACCENT",  "#d4af37"); // gold

// ─── Client slug (used in API calls) ───────────────────────────────────────
define("CLIENT_SLUG", "baraka-awards");

// ─── API response cache (file-based, shared across all PHP processes) ─────
define("API_CACHE_DIR", __DIR__ . '/../cache/api');

function tuqio_api_cache_get(string $key): ?array {
    $file = API_CACHE_DIR . '/' . $key . '.json';
    if (!file_exists($file)) return null;
    $meta = json_decode(file_get_contents($file), true);
    if (!$meta || time() > $meta['expires']) return null;
    return $meta['data'];
}

function tuqio_api_cache_set(string $key, array $data, int $ttl): void {
    if (!is_dir(API_CACHE_DIR)) mkdir(API_CACHE_DIR, 0755, true);
    file_put_contents(
        API_CACHE_DIR . '/' . $key . '.json',
        json_encode(['expires' => time() + $ttl, 'data' => $data]),
        LOCK_EX
    );
    // Prune expired files on ~1% of writes so the directory doesn't grow forever
    if (random_int(1, 100) === 1) {
        foreach (glob(API_CACHE_DIR . '/*.json') as $f) {
            $meta = json_decode(@file_get_contents($f), true);
            if (!$meta || time() > $meta['expires']) @unlink($f);
        }
    }
}

// ─── API helper (GET) ──────────────────────────────────────────────────────
// $ttl: cache lifetime in seconds. 0 = no cache (use for POST-driven data).
// Default TTLs: nominees 120s, vote-bundles 600s, everything else 60s.
function tuqio_api(string $path, int $ttl = 60): array {
    // Derive TTL from endpoint if not overridden
    if ($ttl === 60) {
        if (str_contains($path, '/nominees'))    $ttl = 120;
        if (str_contains($path, '/vote-bundles'))$ttl = 600;
        if (str_contains($path, '/vote-counts')) $ttl = 0;  // always fresh (JS handles this)
    }

    $cacheKey = md5($path);

    if ($ttl > 0) {
        $cached = tuqio_api_cache_get($cacheKey);
        if ($cached !== null) return $cached;
    }

    $data = [];
    foreach ([API_BASE, API_BASE_FALLBACK] as $base) {
        $ch = curl_init($base . $path);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 6,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $body   = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body && $status >= 200 && $status < 500) {
            $data = json_decode($body, true) ?? [];
            break;
        }
    }

    if ($ttl > 0 && !empty($data)) {
        tuqio_api_cache_set($cacheKey, $data, $ttl);
    }

    return $data;
}

// ─── API helper (POST) ─────────────────────────────────────────────────────
function tuqio_api_post(string $path, array $data): array {
    foreach ([API_BASE, API_BASE_FALLBACK] as $base) {
        $ch = curl_init($base . $path);
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
        if ($body && $status >= 200 && $status < 500) {
            $result = json_decode($body, true) ?? [];
            $result['_http_status'] = $status;
            return $result;
        }
    }
    return ['_http_status' => 0];
}
