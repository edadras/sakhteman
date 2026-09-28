/* Service Worker — نسخه قابل نصب (PWA) سایت
 * صفحات: ابتدا شبکه، در صورت قطعی از کش یا صفحه آفلاین
 * فایل‌های ثابت و تصاویر: از کش با به‌روزرسانی در پس‌زمینه
 */
const VERSION = 'v2';
const STATIC_CACHE = 'th-static-' + VERSION;
const PAGE_CACHE = 'th-pages-' + VERSION;
const IMG_CACHE = 'th-img-' + VERSION;
const OFFLINE_URL = '/offline.html';

const PRECACHE = [
    OFFLINE_URL,
    '/assets/fonts/vazirmatn/vazirmatn.css',
    '/assets/fonts/vazirmatn/Vazirmatn-Variable.woff2',
    '/assets/vendor/remixicon/remixicon.css',
    '/assets/vendor/remixicon/remixicon.woff2?t=1718271040674',
    '/assets/vendor/gsap/gsap.min.js',
    '/assets/vendor/gsap/ScrollTrigger.min.js',
    '/assets/vendor/swiper/swiper-bundle.min.js',
    '/assets/vendor/swiper/swiper-bundle.min.css',
    '/assets/vendor/lenis/lenis.min.js',
    '/assets/img/logo-mark.svg',
    '/assets/pwa/icon-192.png',
];

// صفحاتی که هرگز کش نمی‌شوند
const NO_CACHE = [/^\/admin/, /^\/cart/, /^\/account/, /^\/login/, /^\/register/, /^\/logout/, /^\/manifest\.webmanifest/, /^\/sw\.js/];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(STATIC_CACHE).then((c) => c.addAll(PRECACHE)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k.startsWith('th-') && !k.endsWith(VERSION)).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

const trim = async (name, max) => {
    const cache = await caches.open(name);
    const keys = await cache.keys();
    if (keys.length > max) await Promise.all(keys.slice(0, keys.length - max).map((k) => cache.delete(k)));
};

self.addEventListener('fetch', (event) => {
    const req = event.request;
    if (req.method !== 'GET') return;
    const url = new URL(req.url);
    if (url.origin !== self.location.origin) return;
    if (NO_CACHE.some((r) => r.test(url.pathname))) return;

    // صفحات HTML
    if (req.mode === 'navigate' || (req.headers.get('accept') || '').includes('text/html')) {
        event.respondWith(
            fetch(req)
                .then((res) => {
                    if (res.ok && !res.redirected) {
                        const copy = res.clone();
                        caches.open(PAGE_CACHE).then((c) => c.put(req, copy)).then(() => trim(PAGE_CACHE, 40));
                    }
                    return res;
                })
                .catch(async () => (await caches.match(req)) || caches.match(OFFLINE_URL))
        );
        return;
    }

    // تصاویر و فایل‌های ثابت
    if (/^\/(assets|storage)\//.test(url.pathname) || /\.(css|js|woff2?|png|jpe?g|webp|svg|gif|avif)$/.test(url.pathname)) {
        const cacheName = /\.(png|jpe?g|webp|svg|gif|avif)$/.test(url.pathname) ? IMG_CACHE : STATIC_CACHE;
        event.respondWith(
            caches.open(cacheName).then(async (cache) => {
                const cached = (await cache.match(req)) || (await caches.match(req));
                const network = fetch(req)
                    .then((res) => {
                        if (res.ok) cache.put(req, res.clone()).then(() => cacheName === IMG_CACHE && trim(IMG_CACHE, 150));
                        return res;
                    })
                    .catch(() => cached || Response.error());
                return cached || network;
            })
        );
    }
});
