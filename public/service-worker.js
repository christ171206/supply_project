/**
 * Supply PWA Service Worker
 * Handles offline support, caching, and push notifications
 */

const CACHE_NAME = 'supply-v1';
const RUNTIME_CACHE = 'supply-runtime-v1';
const API_CACHE = 'supply-api-v1';

// Assets to cache on install
const STATIC_ASSETS = [
  '/',
  '/index.php',
  '/offline.html',
  '/css/app.css',
  '/js/app.js',
  '/manifest.json',
  'https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500&family=Geist+Mono:wght@400;500&display=swap'
];

// Cache strategy patterns
const CACHE_STRATEGIES = {
  // Network first, fall back to cache
  networkFirst: [
    /\/api\//,
    /\/catalogue/,
    /\/products/,
    /\.json$/
  ],
  // Cache first, fall back to network
  cacheFirst: [
    /\.css$/,
    /\.js$/,
    /\.woff2?$/,
    /\.png|jpg|jpeg|gif|svg$/,
    /\/fonts\//,
    /\/images\//
  ],
  // Stale while revalidate
  staleWhileRevalidate: [
    /\/api\/reports/,
    /\/api\/stats/
  ]
};

/**
 * Install Event - Cache static assets
 */
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Installing...');

  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[ServiceWorker] Caching static assets');
      return cache.addAll(STATIC_ASSETS).catch((error) => {
        console.warn('[ServiceWorker] Some assets failed to cache:', error);
        return Promise.resolve();
      });
    }).then(() => self.skipWaiting())
  );
});

/**
 * Activate Event - Clean up old caches
 */
self.addEventListener('activate', (event) => {
  console.log('[ServiceWorker] Activating...');

  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME &&
              cacheName !== RUNTIME_CACHE &&
              cacheName !== API_CACHE) {
            console.log('[ServiceWorker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

/**
 * Fetch Event - Implement caching strategies
 */
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests and external domains
  if (request.method !== 'GET') {
    return;
  }

  // Determine which strategy to use
  if (shouldUseNetworkFirst(url)) {
    event.respondWith(networkFirstStrategy(request));
  } else if (shouldUseCacheFirst(url)) {
    event.respondWith(cacheFirstStrategy(request));
  } else if (shouldUseStaleWhileRevalidate(url)) {
    event.respondWith(staleWhileRevalidateStrategy(request));
  }
});

/**
 * Network First Strategy
 * Try network first, fallback to cache
 */
function networkFirstStrategy(request) {
  return fetch(request)
    .then((response) => {
      if (!response || response.status !== 200 || response.type === 'error') {
        return response;
      }

      // Cache successful responses
      const responseClone = response.clone();
      caches.open(API_CACHE).then((cache) => {
        cache.put(request, responseClone);
      });

      return response;
    })
    .catch(() => {
      // Return cached version on network error
      return caches.match(request)
        .then((response) => response || createOfflineResponse());
    });
}

/**
 * Cache First Strategy
 * Try cache first, fallback to network
 */
function cacheFirstStrategy(request) {
  return caches.match(request)
    .then((response) => {
      if (response) {
        return response;
      }

      return fetch(request)
        .then((response) => {
          if (!response || response.status !== 200 || response.type === 'error') {
            return response;
          }

          const responseClone = response.clone();
          caches.open(RUNTIME_CACHE).then((cache) => {
            cache.put(request, responseClone);
          });

          return response;
        })
        .catch(() => createOfflineResponse());
    });
}

/**
 * Stale While Revalidate Strategy
 * Return cache immediately, update in background
 */
function staleWhileRevalidateStrategy(request) {
  return caches.match(request)
    .then((cachedResponse) => {
      const fetchPromise = fetch(request)
        .then((response) => {
          if (!response || response.status !== 200 || response.type === 'error') {
            return response;
          }

          const responseClone = response.clone();
          caches.open(API_CACHE).then((cache) => {
            cache.put(request, responseClone);
          });

          return response;
        });

      return cachedResponse || fetchPromise;
    })
    .catch(() => createOfflineResponse());
}

/**
 * Helper functions
 */
function shouldUseNetworkFirst(url) {
  return CACHE_STRATEGIES.networkFirst.some((pattern) => pattern.test(url.pathname));
}

function shouldUseCacheFirst(url) {
  return CACHE_STRATEGIES.cacheFirst.some((pattern) => pattern.test(url.pathname));
}

function shouldUseStaleWhileRevalidate(url) {
  return CACHE_STRATEGIES.staleWhileRevalidate.some((pattern) => pattern.test(url.pathname));
}

/**
 * Create offline response
 */
function createOfflineResponse() {
  return caches.match('/offline.html')
    .catch(() => new Response(
      '<h1>Hors ligne</h1><p>Vous êtes actuellement hors ligne. Certaines fonctionnalités ne sont pas disponibles.</p>',
      {
        status: 503,
        statusText: 'Service Unavailable',
        headers: new Headers({
          'Content-Type': 'text/html; charset=utf-8'
        })
      }
    ));
}

/**
 * Push Notification Event
 */
self.addEventListener('push', (event) => {
  if (!event.data) {
    return;
  }

  const data = event.data.json();
  const options = {
    body: data.body || 'Supply - Nouvelle notification',
    icon: '/icons/icon-192x192.png',
    badge: '/icons/badge-72x72.png',
    tag: data.tag || 'supply-notification',
    requireInteraction: data.requireInteraction || false,
    data: {
      url: data.url || '/',
      ...data.custom
    }
  };

  event.waitUntil(
    self.registration.showNotification(
      data.title || 'Supply',
      options
    )
  );
});

/**
 * Notification Click Event
 */
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  const urlToOpen = event.notification.data.url || '/';

  event.waitUntil(
    clients.matchAll({
      type: 'window',
      includeUncontrolled: true
    }).then((clientList) => {
      // Check if app is already open
      for (let i = 0; i < clientList.length; i++) {
        const client = clientList[i];
        if (client.url === urlToOpen && 'focus' in client) {
          return client.focus();
        }
      }
      // Otherwise open new window
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});

/**
 * Background Sync Event
 * For syncing data when connection is restored
 */
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-orders') {
    event.waitUntil(syncOrders());
  }
});

function syncOrders() {
  return caches.open(API_CACHE)
    .then((cache) => {
      return cache.keys()
        .then((requests) => {
          return Promise.all(
            requests
              .filter((request) => request.url.includes('/api/orders'))
              .map((request) => {
                return fetch(request.clone())
                  .then(() => cache.delete(request))
                  .catch(() => {});
              })
          );
        });
    })
    .catch(() => console.log('[ServiceWorker] Sync failed'));
}

console.log('[ServiceWorker] Loaded successfully');
