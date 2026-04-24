<?php
require_once __DIR__ . '/config/config.php';

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <!-- Static pages -->
  <url><loc>https://barakaawards.tuqiohub.africa/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/events</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/nominees</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/vote</loc><changefreq>daily</changefreq><priority>0.8</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/nominate</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/ticket</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/categories</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/blog</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/polls</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/pricing</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/sponsors</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/gallery</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/about</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/contact</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/faq</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
  <url><loc>https://barakaawards.tuqiohub.africa/tickets</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>

<?php
// Dynamic: Events
$events   = tuqio_api('/api/public/events?client=' . CLIENT_SLUG);
$eventList = $events['data'] ?? (isset($events[0]) ? $events : []);
foreach ($eventList as $event) {
    $slug = htmlspecialchars($event['slug'] ?? '', ENT_XML1);
    if (!$slug) continue;
    $lastmod = isset($event['updated_at']) ? date('Y-m-d', strtotime($event['updated_at'])) : date('Y-m-d');
    echo "  <url><loc>https://barakaawards.tuqiohub.africa/event-detail?slug={$slug}</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>\n";
}

// Dynamic: Blog posts
$blog     = tuqio_api('/api/public/blog?client=' . CLIENT_SLUG);
$postList = $blog['data'] ?? (isset($blog[0]) ? $blog : []);
foreach ($postList as $post) {
    $slug = htmlspecialchars($post['slug'] ?? '', ENT_XML1);
    if (!$slug) continue;
    $lastmod = isset($post['published_at']) ? date('Y-m-d', strtotime($post['published_at'])) : date('Y-m-d');
    echo "  <url><loc>https://barakaawards.tuqiohub.africa/blog-single?slug={$slug}</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>\n";
}
?>
</urlset>
