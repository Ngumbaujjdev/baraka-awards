Ready for review
Select text to add comments on the plan
Plan: MEMA Whitelabel Subsite — Local Setup
Context
MEMA (Myogenic Excellence Music Awards) needs its own branded public website and admin platform, whitelabelled from the existing Tuqio infrastructure. Two new local folders will be created:

mema-frontend/ — copy of tuqio-frontend/, rebranded for MEMA, pointing to mema-platform API
mema-platform/ — copy of v1-events-backend/, rebranded for MEMA, same tuqio_hub database
Local URLs:

Frontend: http://localhost/mema-frontend
Platform API: http://localhost:8002 (php artisan serve --port=8002)
Production targets (future):

mema.tuqiohub.africa → mema-frontend
memaplatform.tuqiohub.africa → mema-platform
Colors and logo to be provided by user — plan includes all the exact files and lines to update.

Step 1 — Copy the two repos
cp -r /Applications/MAMP/htdocs/tuqio-frontend /Applications/MAMP/htdocs/mema-frontend
cp -r /Applications/MAMP/htdocs/v1-events-backend /Applications/MAMP/htdocs/mema-platform
Remove old git history and init fresh repos:

rm -rf /Applications/MAMP/htdocs/mema-frontend/.git
rm -rf /Applications/MAMP/htdocs/mema-platform/.git
cd /Applications/MAMP/htdocs/mema-frontend && git init && git add . && git commit -m "init: MEMA frontend — whitelabelled from tuqio-frontend"
cd /Applications/MAMP/htdocs/mema-platform && git init && git add . && git commit -m "init: MEMA platform — whitelabelled from v1-events-backend"
Step 2 — mema-frontend: update config/config.php
File: /Applications/MAMP/htdocs/mema-frontend/config/config.php

Changes:

Field	Old value	New value
$isNew detection	tuqiohub.africa	mema.tuqiohub.africa
SITE_URL local	http://localhost/tuqio-frontend	http://localhost/mema-frontend
SITE_URL production	https://tuqiohub.africa	https://mema.tuqiohub.africa
API_BASE local	http://localhost:8000	http://localhost:8002
API_BASE production	https://platform.tuqiohub.africa	https://memaplatform.tuqiohub.africa
SITE_NAME	"Tuqio Hub"	"MEMA Awards"
ADMIN_EMAIL	info@tuqiohub.africa	info@memaawards.co.ke (placeholder)
TUQIO_NAVY	#1e1548	#MEMA_PRIMARY ← user to provide
TUQIO_RED	#ed1c24	#MEMA_ACCENT ← user to provide
Social links	Tuqio social links	MEMA social links (placeholder)
OG_IMAGE	Tuqio OG image	MEMA OG image (placeholder)
Step 3 — mema-frontend: update CSS color variables
Grep for all occurrences of Tuqio colors across the frontend:

grep -rn "#1e1548\|#2d1f6b\|#15102e\|#ed1c24\|#c41820" /Applications/MAMP/htdocs/mema-frontend/assets/css/
Replace:

Old	New
#1e1548 (navy)	MEMA primary color
#2d1f6b (navy light)	MEMA primary light (derive from primary)
#15102e (navy dark)	MEMA primary dark (derive from primary)
#ed1c24 (red)	MEMA accent color
#c41820 (red dark)	MEMA accent dark
Same grep + replace in any inline styles across .php files.

Step 4 — mema-frontend: replace logos
Logo files to replace (swap in MEMA logo files with same filenames):

/Applications/MAMP/htdocs/mema-frontend/assets/images/logo/
User adds MEMA logo files here after copy. No code change needed — filenames stay the same.

Also update:

favicon.ico / favicon.png in root
Any hardcoded "Tuqio Hub" text in includes/ header, footer, nav files
Step 5 — mema-frontend: update "Tuqio Hub" text references
Grep for text mentions:

grep -rn "Tuqio Hub\|tuqiohub\|Tuqio" /Applications/MAMP/htdocs/mema-frontend/includes/
grep -rn "Tuqio Hub\|tuqiohub\|Tuqio" /Applications/MAMP/htdocs/mema-frontend/index.php
Replace all "Tuqio Hub" → "MEMA Awards", "tuqiohub" domain references → "memaawards" where relevant.

Step 6 — mema-platform: update .env
File: /Applications/MAMP/htdocs/mema-platform/.env

Changes:

Key	Old	New
APP_NAME	"Tuqio Hub"	"MEMA Awards Platform"
APP_URL	http://localhost:8000	http://localhost:8002
FRONTEND_URL	http://localhost/tuqio-frontend	http://localhost/mema-frontend
APP_KEY	existing key	regenerate with php artisan key:generate
DB settings	unchanged	same — tuqio_hub, root/root
Step 7 — mema-platform: admin panel branding
Logo
File: resources/views/layouts/app.blade.php (and any partials that reference the logo)

Grep:

grep -n "logo\|tuqio\|Tuqio" /Applications/MAMP/htdocs/mema-platform/resources/views/layouts/app.blade.php
Replace logo src path with MEMA logo path. User drops MEMA logo into public/assets/images/logo/.

Admin CSS colors
Grep for Tuqio navy/red in the admin template CSS:

grep -rn "#1e1548\|#ed1c24\|#2d1f6b" /Applications/MAMP/htdocs/mema-platform/public/assets/
grep -rn "#1e1548\|#ed1c24\|#2d1f6b" /Applications/MAMP/htdocs/mema-platform/resources/
Replace with MEMA colors (same substitution as Step 3).

App name in blade
grep -rn "Tuqio Hub\|tuqiohub" /Applications/MAMP/htdocs/mema-platform/resources/views/
Replace "Tuqio Hub" → "MEMA Awards Platform" in page titles, meta tags, layout.

Step 8 — mema-platform: bootstrap
cd /Applications/MAMP/htdocs/mema-platform
php artisan key:generate
php artisan storage:link
php artisan config:clear
php artisan cache:clear
Run on port 8002:

php artisan serve --port=8002
Step 9 — mema-platform: remove inventory-v1 submodule (not needed for MEMA)
cd /Applications/MAMP/htdocs/mema-platform
git rm --cached inventory-v1
rm -rf inventory-v1
Files Changed Summary
mema-frontend
File	Change
config/config.php	URLs, name, colors, socials
assets/css/*.css	Color variable replacements
assets/images/logo/*	User drops in MEMA logo files
includes/header*.php	Site name text
includes/footer*.php	Site name, social links
index.php	Any hardcoded Tuqio text
mema-platform
File	Change
.env	APP_NAME, APP_URL, FRONTEND_URL, APP_KEY
resources/views/layouts/app.blade.php	Logo, site name
Admin CSS files	Color replacements
inventory-v1/	Removed
Verification
http://localhost/mema-frontend loads with MEMA branding
http://localhost:8002 (API) returns JSON from same tuqio_hub DB
http://localhost:8002/login loads MEMA-branded admin login
Event data (MEMA Gala 2026) shows on the frontend homepage
Logo and colors are correct throughout
Plan: MEMA 2026 Event Seeder — Full Setup (Revised)
Context
Myogenic Excellence Music Awards (MEMA) is a new client with no existing account on the platform. Single gala event on September 16, 2026 in Kisumu. Seeder covers everything end-to-end: client, admin user, the gala event (voting + ticketing), 9 nomination categories (8 public + 1 admin-only), nomination questions per category, ticket types with early bird tiers, vote bundles at KES 100/vote, 5 speakers, full gala evening schedule, and event FAQs. Seeder is idempotent (safe to re-run). User fills in exact venue name, banner image, and description via admin UI.

New File: database/seeders/MEMASeeder.php
Imports (top of file)
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
Part 1 — MEMA Client
$client = DB::table('clients')->where('slug', 'mema')->first();
if (!$client) {
    $clientId = DB::table('clients')->insertGetId([
        'name'                       => 'Myogenic Excellence Music Awards',
        'slug'                       => 'mema',
        'email'                      => 'ngumbaujoshua4@gmail.com',
        'phone'                      => '+254757140682',
        'type'                       => 'organization',
        'description'                => 'Myogenic Excellence Music Awards — Honouring Africa\'s Voices and Sounds.',
        'timezone'                   => 'Africa/Nairobi',
        'currency'                   => 'KES',
        'is_active'                  => true,
        'voting_commission_rate'     => 20.00,  // platform keeps 20%, MEMA gets 80%
        'ticketing_commission_rate'  => 5.00,   // platform keeps 5%, MEMA gets 95%
        'paystack_subaccount_code'   => null,   // fill via Admin > Settings > Payment after seeding
        'paystack_subaccount_active' => false,
        'created_at' => now(), 'updated_at' => now(),
    ]);
} else {
    $clientId = $client->id;
}
Part 2 — Client Admin User
$user = User::firstOrCreate(
    ['email' => 'ngumbaujoshua4@gmail.com'],
    [
        'name'      => 'MEMA Admin',
        'client_id' => $clientId,
        'password'  => Hash::make('Admin@2026!'),
        'is_active' => true,
    ]
);
if ($user->client_id !== $clientId) {
    $user->update(['client_id' => $clientId]);
}
$user->syncRoles(['client_admin']);
Part 3 — MEMA Gala 2026 (Single Event — Voting + Ticketing)
// Idempotent: cascade-delete related records then re-insert
$existing = DB::table('events')->where('slug', 'mema-gala-2026')->first();
if ($existing) {
    DB::table('event_schedules')->where('event_id', $existing->id)->delete();
    DB::table('event_faqs')->where('event_id', $existing->id)->delete();
    DB::table('event_speakers')->where('event_id', $existing->id)->delete();
    DB::table('ticket_types')->where('event_id', $existing->id)->delete();
    DB::table('vote_bundles')->where('event_id', $existing->id)->delete();
    DB::table('nominee_categories')->where('event_id', $existing->id)->delete();
    DB::table('events')->where('id', $existing->id)->delete();
}

$eventId = DB::table('events')->insertGetId([
    'uuid'                      => Str::uuid(),
    'client_id'                 => $clientId,
    'name'                      => 'MEMA 2026 — Myogenic Excellence Music Awards Gala',
    'slug'                      => 'mema-gala-2026',
    'tagline'                   => 'Honouring Africa\'s Voices and Sounds.',
    'short_description'         => 'The inaugural Myogenic Excellence Music Awards — celebrating outstanding talent across gospel music, production, videography, and photography in Africa.',
    'description'               => 'TBD — update via admin UI',
    'type'                      => 'awards',
    'event_format'              => 'in-person',
    'start_date'                => '2026-09-16',
    'end_date'                  => '2026-09-16',
    'start_time'                => '16:30:00',
    'end_time'                  => '23:59:00',
    'timezone'                  => 'Africa/Nairobi',
    'venue_name'                => 'TBD — update via admin UI',  // update to actual venue
    'venue_city'                => 'Kisumu',
    'venue_country'             => 'Kenya',
    'status'                    => 'published',
    'visibility'                => 'public',
    'is_featured'               => true,
    'has_voting'                => true,
    'has_ticketing'             => true,
    'has_registration'          => true,
    'voting_opens_at'           => '2026-08-01 00:00:00',
    'voting_closes_at'          => '2026-09-14 23:59:59',
    'voting_commission_rate'    => 20.00,
    'ticketing_commission_rate' => 5.00,
    'currency'                  => 'KES',
    'published_at'              => now(),
    'created_at'                => now(),
    'updated_at'                => now(),
]);
Part 4 — 9 Nomination Categories (8 public + 1 admin-only)
Nomination window (public categories): April 16 → July 15, 2026 Voting window (all categories): August 1 → September 14, 2026

Nomination questions stored as JSON in nomination_questions column:

$publicQuestions = [
    ['key' => 'nominee_name',       'label' => 'Nominee\'s Full Name',                  'type' => 'text',     'required' => true],
    ['key' => 'nominee_phone',      'label' => 'Nominee\'s Phone Number',               'type' => 'tel',      'required' => false],
    ['key' => 'nominee_email',      'label' => 'Nominee\'s Email Address',              'type' => 'email',    'required' => false],
    ['key' => 'nominee_county',     'label' => 'Nominee\'s County / Location',          'type' => 'text',     'required' => true],
    ['key' => 'why_nominate',       'label' => 'Why are you nominating this person?',   'type' => 'textarea', 'required' => true,  'min_length' => 80],
    ['key' => 'nominee_impact',     'label' => 'How has this person impacted gospel music in Kenya?', 'type' => 'textarea', 'required' => true, 'min_length' => 80],
    ['key' => 'nominee_social',     'label' => 'Nominee\'s Social Media Handle(s)',     'type' => 'text',     'required' => false, 'placeholder' => '@handle on Instagram / TikTok / YouTube'],
    ['key' => 'nominee_work_link',  'label' => 'Link to Nominee\'s Best Work',          'type' => 'url',      'required' => false, 'placeholder' => 'YouTube / SoundCloud / Spotify link'],
];
Category table (name, nomination_type, sort_order):

#	Category	nomination_type
1	Female Artist of the Year	public_nomination
2	Male Artist of the Year	public_nomination
3	Emerging Gospel Artist of the Year	public_nomination
4	Gospel Song of the Year	public_nomination
5	Gospel Video of the Year	public_nomination
6	Gospel Collaboration of the Year	admin_only (only one)
7	Audio Producer of the Year	public_nomination
8	Video Director of the Year	public_nomination
9	Photography of the Year	public_nomination
// Insert each category:
DB::table('nominee_categories')->insert([
    'uuid'                 => Str::uuid(),
    'event_id'             => $eventId,
    'name'                 => $name,
    'slug'                 => Str::slug($name),
    'description'          => "Recognising the best in {$name}.",
    'sort_order'           => $sortOrder,
    'is_active'            => true,
    'voting_enabled'       => true,
    'max_winners'          => 1,
    'display_type'         => 'grid',
    'nominees_per_row'     => 3,
    'nomination_type'      => $nominationType,
    'nomination_status'    => 'collecting',
    'nomination_starts_at' => $isPublic ? '2026-04-16 00:00:00' : null,
    'nomination_ends_at'   => $isPublic ? '2026-07-15 23:59:59' : null,
    'nomination_questions' => $isPublic ? json_encode($publicQuestions) : null,
    'selection_method'     => 'manual',
    'top_nominees_count'   => 5,
    'created_at'           => now(), 'updated_at' => now(),
]);
Part 5 — Ticket Types (Early Bird + Regular + Tables)
Early bird window: April 16 – June 30, 2026 Regular window: July 1 – September 15, 2026 Prices are placeholder — user updates via admin UI after seeding.

// [name, price, original_price, qty, sale_starts_at, sale_ends_at, sort_order, description, benefits[]]
$ticketTypes = [
    // ─── Early Bird ───────────────────────────────────────────────────────────
    [
        'name'           => 'Early Bird Regular Seat',
        'price'          => 1000,
        'original_price' => 1500,
        'qty'            => 100,
        'sale_starts_at' => '2026-04-16 00:00:00',
        'sale_ends_at'   => '2026-06-30 23:59:59',
        'sort_order'     => 1,
        'description'    => 'Limited early bird offer — regular seat with dinner. Grab it before June 30!',
        'benefits'       => ['Gala Dinner', 'Event Programme', 'Early Bird Discount'],
    ],
    [
        'name'           => 'Early Bird VIP Seat',
        'price'          => 2500,
        'original_price' => 3500,
        'qty'            => 50,
        'sale_starts_at' => '2026-04-16 00:00:00',
        'sale_ends_at'   => '2026-06-30 23:59:59',
        'sort_order'     => 2,
        'description'    => 'Limited early bird VIP seat with dinner and priority seating.',
        'benefits'       => ['Gala Dinner', 'Priority Seating', 'Event Programme', 'Early Bird Discount'],
    ],
    // ─── Regular ──────────────────────────────────────────────────────────────
    [
        'name'           => 'Regular Single Seat',
        'price'          => 1500,
        'original_price' => 1500,
        'qty'            => 200,
        'sale_starts_at' => '2026-07-01 00:00:00',
        'sale_ends_at'   => '2026-09-15 23:59:59',
        'sort_order'     => 3,
        'description'    => 'Single regular seat with gala dinner included.',
        'benefits'       => ['Gala Dinner', 'Event Programme'],
    ],
    [
        'name'           => 'VIP Single Seat',
        'price'          => 3500,
        'original_price' => 3500,
        'qty'            => 100,
        'sale_starts_at' => '2026-07-01 00:00:00',
        'sale_ends_at'   => '2026-09-15 23:59:59',
        'sort_order'     => 4,
        'description'    => 'Single VIP seat with dinner and priority seating.',
        'benefits'       => ['Gala Dinner', 'Priority Seating', 'Event Programme'],
    ],
    [
        'name'           => 'VVIP Single Seat',
        'price'          => 6000,
        'original_price' => 6000,
        'qty'            => 50,
        'sale_starts_at' => '2026-04-16 00:00:00',
        'sale_ends_at'   => '2026-09-15 23:59:59',
        'sort_order'     => 5,
        'description'    => 'Single VVIP seat with premium dinner, front-row seating, and VIP lounge access.',
        'benefits'       => ['Premium Dinner', 'Front-Row Seating', 'VIP Lounge Access', 'Event Programme', 'Meet & Greet'],
    ],
    // ─── Tables ───────────────────────────────────────────────────────────────
    [
        'name'           => 'VIP Table (10 Seats)',
        'price'          => 30000,
        'original_price' => 30000,
        'qty'            => 20,
        'sale_starts_at' => '2026-04-16 00:00:00',
        'sale_ends_at'   => '2026-09-15 23:59:59',
        'sort_order'     => 6,
        'description'    => 'Private VIP table for 10 guests with dinner and priority seating.',
        'benefits'       => ['Gala Dinner for 10', 'Priority Seating', 'Table Name Card', 'Event Programmes x10'],
    ],
    [
        'name'           => 'VVIP Table (10 Seats)',
        'price'          => 50000,
        'original_price' => 50000,
        'qty'            => 10,
        'sale_starts_at' => '2026-04-16 00:00:00',
        'sale_ends_at'   => '2026-09-15 23:59:59',
        'sort_order'     => 7,
        'description'    => 'Exclusive VVIP table for 10 with premium dinner, front-row placement, and VIP lounge access.',
        'benefits'       => ['Premium Dinner for 10', 'Front-Row Table', 'VIP Lounge Access x10', 'Table Name Card', 'Meet & Greet x2'],
    ],
];
// Insert into ticket_types: uuid, client_id, event_id, is_active=true, currency='KES',
// min_per_order=1, max_per_order=10, allows_refund=false
Part 6 — Vote Bundles (KES 100 per vote base rate)
Valid: August 1 → September 14, 2026

$bundles = [
    // [name, vote_count, price, original_price, discount_pct, is_featured, sort_order, description]
    ['Single Vote',     1,    100,    100,   0,  false, 1, 'Cast 1 vote for your favourite nominee.'],
    ['Starter Pack',   10,    900,   1000,  10,  false, 2, '10 votes — great for a quick show of support.'],
    ['Fan Pack',       50,   4000,   5000,  20,  true,  3, '50 votes — show real love for your favourite. Best value!'],
    ['Super Fan',     100,   7000,  10000,  30,  false, 4, '100 votes — for the ultimate supporter.'],
    ['Champion',      500,  30000,  50000,  40,  false, 5, '500 votes — dominate the leaderboard and crown your champion.'],
];
// Insert into vote_bundles: client_id, event_id, is_active=true,
// valid_from='2026-08-01 00:00:00', valid_until='2026-09-14 23:59:59'
Part 7 — 5 Speakers
$speakers = [
    [
        'name'         => 'Dr. Ezekiel Mutua',
        'title'        => 'CEO, Kenya Film Classification Board',
        'company'      => 'Kenya Film Classification Board',
        'bio'          => 'Dr. Ezekiel Mutua (PhD) is a media strategist, motivational speaker, and CEO of the Kenya Film Classification Board. A passionate advocate for African values in media and entertainment.',
        'speaker_type' => 'keynote',
        'is_featured'  => true,
        'sort_order'   => 1,
    ],
    [
        'name'         => 'Eunice Njeri',
        'title'        => 'Award-Winning Gospel Artist & Worship Leader',
        'company'      => 'Independent',
        'bio'          => 'Eunice Njeri is one of Kenya\'s most celebrated gospel artists, known for chart-topping worship songs and her passionate ministry across Africa.',
        'speaker_type' => 'speaker',
        'is_featured'  => true,
        'sort_order'   => 2,
    ],
    [
        'name'         => 'Aaron Rimbui',
        'title'        => 'Music Producer & Composer',
        'company'      => 'Rimbui Studios',
        'bio'          => 'Aaron Rimbui is a multi-award-winning music producer and composer who has shaped the sound of Kenyan gospel and contemporary music for over two decades.',
        'speaker_type' => 'speaker',
        'is_featured'  => false,
        'sort_order'   => 3,
    ],
    [
        'name'         => 'Rev. Dr. Josephine Naphtali',
        'title'        => 'Minister of Music & Founder, Music Ministry Africa',
        'company'      => 'Music Ministry Africa',
        'bio'          => 'Rev. Dr. Josephine Naphtali is a minister, gospel musician, and founder of Music Ministry Africa — dedicated to equipping African gospel artists for impact.',
        'speaker_type' => 'panelist',
        'is_featured'  => false,
        'sort_order'   => 4,
    ],
    [
        'name'         => 'Jimmy Gait',
        'title'        => 'Gospel Artist & MC',
        'company'      => 'Independent',
        'bio'          => 'Jimmy Gait is a beloved Kenyan gospel artist and entertainer known for his energetic performances and ability to unite audiences through music and laughter.',
        'speaker_type' => 'moderator',
        'is_featured'  => false,
        'sort_order'   => 5,
    ],
];
// Insert into event_speakers: event_id, is_active=true, social_links=[], topics=[]
Part 8 — Gala Evening Schedule (September 16, 2026)
$schedule = [
    // [title, description, type, start_time, end_time, is_highlighted, sort_order, speaker, speaker_title]
    ['Red Carpet & Guest Arrivals',    'Guests arrive and register. Red carpet photography and pre-show networking.',               'ceremony',    '16:30', '17:30', false, 1,  null, null],
    ['Networking Cocktails',           'Pre-show cocktail reception with light refreshments and entertainment.',                    'networking',  '17:30', '18:30', false, 2,  null, null],
    ['Doors Open — Main Gala Hall',    'Guests are ushered into the main hall. Seats assigned.',                                   'session',     '18:30', '19:00', false, 3,  null, null],
    ['Opening Prayer & Welcome',       'Official opening prayer followed by welcome address from the MEMA Chairperson.',           'ceremony',    '19:00', '19:20', true,  4,  null, null],
    ['Keynote Address',                'An inspiring address on the power of gospel music and African creative excellence.',       'keynote',     '19:20', '19:50', true,  5,  'Dr. Ezekiel Mutua', 'CEO, Kenya Film Classification Board'],
    ['Awards Block 1 — Categories 1–3','Presentation of awards: Female Artist, Male Artist, Emerging Gospel Artist of the Year.', 'ceremony',    '19:50', '20:40', true,  6,  null, null],
    ['Musical Performance',            'Live gospel performance by award-nominated artists.',                                      'performance', '20:40', '21:10', false, 7,  null, null],
    ['Awards Block 2 — Categories 4–6','Presentation of awards: Gospel Song, Gospel Video, Gospel Collaboration of the Year.',    'ceremony',    '21:10', '22:00', true,  8,  null, null],
    ['Gala Dinner & Entertainment',    'Sit-down gala dinner accompanied by live music and cultural performances.',               'break',       '22:00', '22:30', false, 9,  null, null],
    ['Awards Block 3 — Categories 7–9','Presentation of awards: Audio Producer, Video Director, Photography of the Year.',       'ceremony',    '22:30', '23:10', true,  10, null, null],
    ['Special Recognition Award',      'Lifetime achievement and special recognition award presentations.',                       'ceremony',    '23:10', '23:25', true,  11, null, null],
    ['Closing Remarks & Vote of Thanks','Official closing of MEMA 2026 and vote of thanks to sponsors and guests.',              'ceremony',    '23:25', '23:45', false, 12, null, null],
    ['After-Gala Networking & Photos', 'Informal networking, group photography, and celebrations with award winners.',           'networking',  '23:45', '00:00', false, 13, null, null],
];
// Insert into event_schedules: event_id, date='2026-09-16', location='Main Gala Hall — Kisumu',
// track=null, meta=[]
Part 9 — Event FAQs (12 FAQs)
$faqs = [
    // [question, answer, sort_order]
    [
        'How do I vote for my favourite nominee?',
        'Visit the MEMA Gala 2026 event page on tuqiohub.africa, choose your nominee, select a vote bundle, and complete payment via M-Pesa or card. Your votes are credited instantly.',
        1
    ],
    [
        'How much does one vote cost?',
        'One vote costs KES 100. You can buy vote bundles for discounts — e.g. 50 votes for KES 4,000 (save 20%) or 100 votes for KES 7,000 (save 30%).',
        2
    ],
    [
        'Can I buy votes for different nominees?',
        'Yes! You can purchase vote bundles and distribute them across different nominees and categories as you wish.',
        3
    ],
    [
        'When does voting open and close?',
        'Public voting opens on August 1, 2026 and closes on September 14, 2026 at 11:59 PM (EAT). No votes are accepted after the deadline.',
        4
    ],
    [
        'How do I nominate someone?',
        'Visit tuqiohub.africa/nominate, select the MEMA Gala 2026 event, choose a category, and fill in the nomination form with the required details. Nominations are open from April 16 to July 15, 2026.',
        5
    ],
    [
        'Can I nominate myself?',
        'Yes, self-nominations are accepted! Fill out the nomination form with your own details and explain why you deserve the award.',
        6
    ],
    [
        'When does the nomination window open and close?',
        'Public nominations open on April 16, 2026 and close on July 15, 2026 at 11:59 PM. After this, the MEMA team reviews all nominations and promotes shortlisted nominees to the voting phase.',
        7
    ],
    [
        'Which categories are open for public nomination?',
        'Eight out of nine categories are open for public nomination: Female Artist, Male Artist, Emerging Gospel Artist, Gospel Song, Gospel Video, Audio Producer, Video Director, and Photography of the Year. Gospel Collaboration of the Year is nominated by the MEMA team.',
        8
    ],
    [
        'How do I buy event tickets?',
        'Visit the MEMA Gala 2026 event page, click "Get Tickets", choose your ticket type (Early Bird, Regular, VIP, or VVIP), and complete payment. You will receive your e-ticket via email and SMS.',
        9
    ],
    [
        'Are there early bird tickets?',
        'Yes! Early Bird Regular Seats (KES 1,000) and Early Bird VIP Seats (KES 2,500) are available from April 16 to June 30, 2026 — at a significant discount. Limited quantities — book early!',
        10
    ],
    [
        'Where is the event held?',
        'MEMA Gala 2026 will be held in Kisumu, Kenya on September 16, 2026. The exact venue will be announced soon. Stay tuned on our social media channels.',
        11
    ],
    [
        'Can I get a refund on my ticket?',
        'Tickets are non-refundable once purchased. However, ticket transfers are allowed — contact us at info@tuqiohub.africa with your booking reference to arrange a transfer.',
        12
    ],
];
// Insert into event_faqs: event_id, is_active=true
Modified File: database/seeders/DatabaseSeeder.php
Add MEMASeeder::class to the $this->call([...]) array.

Revenue Split Summary
Transaction	Platform cut	MEMA gets
Ticket sales	5%	95%
Votes (KES 100/vote)	20%	80%
Single vote revenue	KES 20 → platform	KES 80 → MEMA
Execution
Local seed
cd /Applications/MAMP/htdocs/v1-events-backend
php artisan db:seed --class=MEMASeeder
Push & production seed
git add database/seeders/MEMASeeder.php database/seeders/DatabaseSeeder.php
git commit -m "feat: add MEMA 2026 seeder — client, event, 9 categories, early bird tickets, 5 speakers, schedule, FAQs"
git push origin main

# On cPanel:
cd ~/platform.tuqiohub.africa
git pull origin main
php artisan db:seed --class=MEMASeeder
What User Does After Seeding
Login → select MEMA client in admin
Event → upload MEMA banner image, update venue name (exact hall in Kisumu), add full description
Ticket Types → update prices to actual confirmed amounts
Nominees → for Gospel Collaboration of the Year (admin_only) — add nominees directly via admin
Paystack → Admin > Settings > Payment — add MEMA Paystack subaccount code
April 16 → nomination window opens automatically; public nominates at /nominate?event=mema-gala-2026
After July 15 → review nominations inbox, approve/reject nominees to promote to voting
August 1 → voting opens automatically; public votes at /event-detail?slug=mema-gala-2026
Verification
After running php artisan db:seed --class=MEMASeeder, verify:

DB::table('clients')->where('slug','mema')->count() → 1
DB::table('events')->where('slug','mema-gala-2026')->count() → 1
DB::table('nominee_categories')->where('event_id', $eventId)->count() → 9
DB::table('ticket_types')->where('event_id', $eventId)->count() → 7
DB::table('vote_bundles')->where('event_id', $eventId)->count() → 5
DB::table('event_speakers')->where('event_id', $eventId)->count() → 5
DB::table('event_schedules')->where('event_id', $eventId)->count() → 13
DB::table('event_faqs')->where('event_id', $eventId)->count() → 12
Admin UI: login as ngumbaujoshua4@gmail.com / Admin@2026!, switch to MEMA client, navigate to Events → MEMA Gala 2026 to confirm all sections loaded
Add Comment