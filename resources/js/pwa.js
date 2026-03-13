/**
 * PWA Service Worker Registration
 * Handles service worker lifecycle and updates
 */

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/service-worker.js')
      .then((registration) => {
        console.log('[PWA] Service Worker registered:', registration);

        // Check for updates periodically
        setInterval(() => {
          registration.update();
        }, 60000); // Check every minute

        // Listen for updates
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;

          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              // New service worker available
              console.log('[PWA] New version available');
              notifyAppUpdate();
            }
          });
        });
      })
      .catch((error) => {
        console.error('[PWA] Service Worker registration failed:', error);
      });

    // Handle controller changes
    let refreshing = false;
    navigator.serviceWorker.addEventListener('controllerchange', () => {
      if (refreshing) return;
      refreshing = true;
      window.location.reload();
    });
  });
}

/**
 * Notify user about app updates
 */
function notifyAppUpdate() {
  if ('Notification' in window && Notification.permission === 'granted') {
    new Notification('Supply a été mise à jour', {
      body: 'Une nouvelle version est disponible. Rechargez pour appliquer les changements.',
      icon: '/icons/icon-192x192.png',
      badge: '/icons/badge-72x72.png',
      tag: 'pwa-update'
    });
  }
}

/**
 * Request notification permission if not granted
 */
function requestNotificationPermission() {
  if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
  }
}

/**
 * Trigger background sync for offline actions
 */
function syncData() {
  if ('serviceWorker' in navigator && 'SyncManager' in window) {
    navigator.serviceWorker.ready.then((registration) => {
      registration.sync.register('sync-orders')
        .then(() => console.log('[PWA] Sync registered'))
        .catch((error) => console.error('[PWA] Sync failed:', error));
    });
  }
}

/**
 * Send message to service worker
 */
function sendMessageToServiceWorker(message) {
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.ready.then((registration) => {
      if (registration.active) {
        registration.active.postMessage(message);
      }
    });
  }
}

export {
  requestNotificationPermission,
  syncData,
  sendMessageToServiceWorker
};
