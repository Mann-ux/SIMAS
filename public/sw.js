/**
 * SIMAS — Service Worker
 * Strategi: Cache-first untuk aset statis, Network-first untuk halaman dinamis.
 */

const CACHE_NAME = 'simas-sma-v1';

// Aset yang di-cache saat install (App Shell)
const PRECACHE_ASSETS = [
    '/',
    '/manifest.json',
];

// ── Install Event ─────────────────────────────────────────────────────────────
// Membuka cache dan memasukkan aset App Shell.
self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
    // Langsung aktifkan SW baru tanpa menunggu tab lama ditutup
    self.skipWaiting();
});

// ── Activate Event ────────────────────────────────────────────────────────────
// Membersihkan cache lama yang tidak terpakai.
self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames
                    .filter(function (name) { return name !== CACHE_NAME; })
                    .map(function (name) { return caches.delete(name); })
            );
        })
    );
    // Ambil alih semua klien yang sedang aktif
    self.clients.claim();
});

// ── Fetch Event ───────────────────────────────────────────────────────────────
// Cache-first: balikan dari cache jika ada, jika tidak lakukan fetch jaringan.
self.addEventListener('fetch', function (event) {
    // Abaikan request yang bukan GET (POST, PATCH, dll — termasuk form submit)
    if (event.request.method !== 'GET') return;

    // Abaikan request ke domain lain (CDN, Google Fonts, dll)
    if (!event.request.url.startsWith(self.location.origin)) return;

    event.respondWith(
        caches.match(event.request).then(function (cachedResponse) {
            if (cachedResponse) {
                return cachedResponse;
            }
            // Tidak ada di cache → fetch dari jaringan
            return fetch(event.request);
        })
    );
});
