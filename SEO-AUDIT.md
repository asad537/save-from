# Save-Froms SEO Audit

Audit date: 2026-10-06

## Completed

- Unique page titles and meta descriptions are rendered for the homepage, directory, FAQ, platform pages, SEO landing pages and blog posts.
- Every audited public page has one H1, a self-referencing canonical URL and index/follow robots metadata.
- Temporary download/error pages use `noindex,follow`.
- XML sitemap includes active platform pages, landing pages and published blog posts; dynamic records include accurate `lastmod` values.
- robots.txt allows public content, blocks the administrator area and declares the sitemap.
- Homepage and article pages have crawlable internal links using descriptive anchors.
- FAQ and landing pages include valid JSON-LD where applicable.
- Blog articles include valid `BlogPosting` and breadcrumb JSON-LD.
- Global Organization and WebSite JSON-LD is present.
- Open Graph and Twitter large-image metadata is present.
- Eight unique 1280×720 featured images are attached to the platform articles.
- Blog image dimensions, descriptive alt text, lazy loading on listings and eager loading on article covers are set.
- Every current rendered image has both descriptive `alt` and `title` attributes. A global image-attribute guard also covers images inserted later through TinyMCE or dynamic results.
- The homepage displays the three latest published articles as responsive, crawlable cards with descriptive image metadata.
- All eight published articles have distinct normalized-content hashes. The highest pairwise text similarity in the current set is 33.29%, so no exact or near-duplicate article was detected.
- Responsive pages were validated at 320px and 390px widths without horizontal overflow.

## Downloader verification

- Instagram Reel: parse returned two resources and the prepare service returned a valid final media URL.
- TikTok video: parse returned two resources and the prepare service returned a valid final media URL.
- Facebook Reel: parse returned seven resources and the prepare service returned a valid final media URL.
- Blank provider quality labels now use a safe `Original quality` or `Audio` fallback, preventing valid Instagram results from being filtered out.

## Deployment checklist

- Set production `APP_URL=https://save-froms.net` and clear Laravel config/view caches.
- Force one HTTPS host version (www or non-www) with a permanent redirect.
- Submit `https://save-froms.net/sitemap.xml` in Google Search Console and Bing Webmaster Tools.
- Verify the domain and inspect priority URLs after launch.
- Connect real analytics/consent settings appropriate to the target countries.
- Monitor Core Web Vitals, indexing, crawl errors and provider uptime after real traffic begins.

Ranking is not guaranteed; relevance, competition, backlinks, real user satisfaction, site reliability and search-engine systems remain external factors.

## Audit follow-up (2026-10-08)

Applied from the page-by-page audit; content lives in migration `2026_10_08_000003_apply_seo_audit_content_fixes`.

- Contact, About, Privacy Policy and Terms of Service are real pages (`resources/views/{contact,about,privacy,terms}.blade.php`). `/privacy`, `/terms`, `/about-us`, `/contact-us` redirect (301) to the canonical URLs. Addresses come from `SUPPORT_EMAIL` and `LEGAL_EMAIL` in `.env`.
- Header "Download Extension" CTA replaced with a real "Download Video" link to the homepage downloader.
- Homepage: H1 "Free Online Video Downloader", honest platform claim (no "100+ websites"), demo visual labelled "Example result", fake "Premium" badge removed, title/meta target "online video downloader".
- Platform pages: keyword H1 per platform (`supported_sites.heading`, editable in admin), audit title strategy, breadcrumb + BreadcrumbList schema, platform-specific content (URL pattern tables, platform troubleshooting tables, platform-only FAQs), and an automatic "tools, guides and help" section linking child tools, matching blog posts and the help hubs.
- Specialist landing pages link up to their parent platform and across to sibling tools (`LandingPage::parentPlatform()` / `siblingPages()`).
- Supported Sites is a hub: capability table per platform, link types, grouped tools, help hubs.
- Global FAQ rewritten to site-level questions only; platform questions stay on platform pages.
- Blog: each article's CTA targets its most specific action page; overlapping articles re-angled (MP4 vs MP3 generic guide, TikTok MP4 vs MP3, mobile guide, YouTube URL types guide); author box links to editorial principles.
- Feature coverage in `tests/Feature/SeoPagesTest.php`.

Still manual: verify indexing in Google Search Console, Core Web Vitals, and that the production mailboxes for the contact addresses exist.
