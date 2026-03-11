/**
 * Pusher Realtime Notifications
 * Manage realtime notifications with Pusher channels
 */

class RealtimeNotifications {
    constructor() {
        this.pusher = null;
        this.channels = [];
        this.isInitialized = false;
        this.notificationQueue = [];
        this.soundEnabled = true;
    }

    /**
     * Initialize Pusher connection and channels
     */
    async init() {
        try {
            // Fetch Pusher configuration from server
            const response = await fetch('/api/notifications/init');
            if (!response.ok) {
                console.error('Failed to initialize notifications');
                return false;
            }

            const data = await response.json();
            const { pusher: pusherConfig, channels, user_id, user_name } = data;

            // Load Pusher library if not already loaded
            if (!window.Pusher) {
                await this.loadPusherLibrary();
            }

            // Initialize Pusher with config
            this.pusher = new Pusher(pusherConfig.key, {
                cluster: pusherConfig.cluster,
                useTLS: pusherConfig.useTLS,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            });

            // Subscribe to all channels
            channels.forEach(channelName => {
                const channel = this.pusher.subscribe(channelName);
                
                // Listen to order created event
                channel.bind('order.created', (data) => {
                    this.handleOrderCreated(data);
                });

                // Listen to order status change event
                channel.bind('order.status-changed', (data) => {
                    this.handleOrderStatusChanged(data);
                });

                // Listen to new message event
                channel.bind('message.received', (data) => {
                    this.handleNewMessage(data);
                });

                // Listen to vendor approval event
                channel.bind('vendor.approval-status-changed', (data) => {
                    this.handleVendorApproval(data);
                });

                this.channels.push(channel);
            });

            this.isInitialized = true;
            console.log('✅ Realtime notifications initialized');
            return true;

        } catch (error) {
            console.error('❌ Notification initialization error:', error);
            return false;
        }
    }

    /**
     * Load Pusher library dynamically
     */
    loadPusherLibrary() {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://js.pusher.com/8.0.1/pusher.min.js';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    /**
     * Handle order created notification
     */
    handleOrderCreated(data) {
        const notification = {
            type: 'order.created',
            id: `order-${data.id}`,
            title: '📦 Nouvelle Commande',
            message: `${data.client} a passé une commande de ${data.montant} ${data.devise}`,
            data: data,
            timestamp: new Date()
        };

        this.showNotification(notification);
        this.playSound();
    }

    /**
     * Handle order status change notification
     */
    handleOrderStatusChanged(data) {
        const statusColors = {
            'pending': 'bg-yellow-50 border-yellow-200',
            'processing': 'bg-blue-50 border-blue-200',
            'shipped': 'bg-purple-50 border-purple-200',
            'delivered': 'bg-green-50 border-green-200',
            'cancelled': 'bg-red-50 border-red-200',
        };

        const notification = {
            type: 'order.status-changed',
            id: `status-${data.id}-${Date.now()}`,
            title: '🔄 Mise à Jour de Commande',
            message: data.message,
            data: data,
            timestamp: new Date(),
            color: statusColors[data.new_status] || 'bg-gray-50 border-gray-200'
        };

        this.showNotification(notification);
        this.playSound();
    }

    /**
     * Handle new message notification
     */
    handleNewMessage(data) {
        const notification = {
            type: 'message.received',
            id: `msg-${data.id}`,
            title: '💬 Nouveau Message',
            message: `${data.from_name}: ${data.content.substring(0, 50)}...`,
            data: data,
            timestamp: new Date(),
            actionUrl: '/messages/' + data.from_user_id
        };

        this.showNotification(notification);
        this.playSound();

        // Update unread message count in navbar
        this.updateMessageBadge();
    }

    /**
     * Handle vendor approval notification
     */
    handleVendorApproval(data) {
        const isApproved = data.status === 'approved';
        const notification = {
            type: 'vendor.approval',
            id: `vendor-${data.vendor_id}`,
            title: isApproved ? '✅ Compte Approuvé' : '❌ Inscription Rejetée',
            message: data.message + (data.reason ? `: ${data.reason}` : ''),
            data: data,
            timestamp: new Date(),
            color: isApproved ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'
        };

        this.showNotification(notification);
        this.playSound();
    }

    /**
     * Display notification toast
     */
    showNotification(notification) {
        const toastContainer = document.getElementById('notification-container');
        
        if (!toastContainer) {
            this.createNotificationContainer();
        }

        const toast = document.createElement('div');
        toast.id = notification.id;
        toast.className = `
            notification-toast
            border rounded-lg
            p-4 mb-4
            shadow-lg
            bg-white
            border-gray-200
            animate-slideIn
            cursor-pointer
            transition-all
            max-w-sm
        `;

        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="text-lg">${this.getIcon(notification.type)}</div>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900 text-sm">${notification.title}</p>
                    <p class="text-gray-600 text-xs mt-1">${notification.message}</p>
                    <p class="text-gray-400 text-xs mt-2">${this.formatTime(notification.timestamp)}</p>
                </div>
                <button class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('${notification.id}').remove()">
                    ✕
                </button>
            </div>
        `;

        // Click to navigate if action URL available
        if (notification.actionUrl) {
            toast.addEventListener('click', () => {
                window.location.href = notification.actionUrl;
            });
        }

        const container = document.getElementById('notification-container');
        container.insertBefore(toast, container.firstChild);

        // Auto remove after 6 seconds
        setTimeout(() => {
            if (document.getElementById(notification.id)) {
                document.getElementById(notification.id).remove();
            }
        }, 6000);

        // Store in queue for fallback
        this.notificationQueue.push(notification);
    }

    /**
     * Create notification container if not exists
     */
    createNotificationContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 max-w-md';
        document.body.appendChild(container);

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            .animate-slideIn {
                animation: slideIn 0.3s ease-out;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Play notification sound
     */
    playSound() {
        if (!this.soundEnabled) return;

        // Create simple beep sound using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        // Play a pleasant beep
        oscillator.frequency.value = 800; // Hz
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    /**
     * Update message badge count
     */
    updateMessageBadge() {
        const badge = document.querySelector('[data-unread-messages]');
        if (badge) {
            const current = parseInt(badge.textContent) || 0;
            badge.textContent = current + 1;
            badge.classList.remove('hidden');
        }
    }

    /**
     * Get icon for notification type
     */
    getIcon(type) {
        const icons = {
            'order.created': '📦',
            'order.status-changed': '🔄',
            'message.received': '💬',
            'vendor.approval': '✅'
        };
        return icons[type] || '🔔';
    }

    /**
     * Format timestamp
     */
    formatTime(date) {
        const now = new Date();
        const diff = now - date;
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);

        if (seconds < 60) return 'à l\'instant';
        if (minutes < 60) return `il y a ${minutes}m`;
        if (hours < 24) return `il y a ${hours}h`;
        
        return date.toLocaleDateString('fr-FR');
    }

    /**
     * Disconnect Pusher
     */
    disconnect() {
        if (this.pusher) {
            this.pusher.disconnect();
            this.isInitialized = false;
        }
    }

    /**
     * Toggle sound
     */
    toggleSound(enabled) {
        this.soundEnabled = enabled;
    }
}

// Initialize notifications when DOM is ready
document.addEventListener('DOMContentLoaded', async () => {
    const notifications = new RealtimeNotifications();
    await notifications.init();
    
    // Make available globally for debugging
    window.RealtimeNotifications = notifications;
});
