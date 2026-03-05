<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Espace Vendeur - Supply')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50">
            <!-- Header Vendeur -->
            <header class="sticky top-0 z-50 bg-white shadow-lg border-b-2 border-primary-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                        <!-- Logo -->
                        <a href="{{ route('vendeur.dashboard') }}" class="text-2xl font-bold text-primary-600">
                            <x-heroicon-o-cube class="w-6 h-6" /> Supply
                        </a>

                        <!-- Centre: Breadcrumb -->
                        <div class="hidden md:flex items-center gap-2 text-sm">
                            <x-heroicon-o-home class="w-6 h-6 text-gray-600" />
                            <span class="font-bold text-gray-900">{{ auth()->user()->shop_name ?? auth()->user()->name }}</span>
                        </div>

                        <!-- Droite: Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-600 hover:text-primary-600 transition" onclick="toggleNotifications()">
                                <x-heroicon-o-bell class="w-6 h-6" />
                                <span id="notifications-badge" class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">0</span>
                            </button>

                            <!-- Profil Dropdown -->
                            <div class="relative group">
                                <button class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition">
                                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:block text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                                </button>

                                <!-- Menu Dropdown -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <a href="{{ route('vendeur.profil') }}" class="block px-4 py-3 text-gray-900 hover:bg-gray-100 rounded-t-lg font-medium flex items-center gap-2"><x-heroicon-o-user class="w-5 h-5" /> Mon Profil</a>
                                    <a href="{{ route('accueil') }}" class="block px-4 py-3 text-gray-900 hover:bg-gray-100 font-medium flex items-center gap-2"><x-heroicon-o-shopping-bag class="w-5 h-5" /> Mode Client</a>
                                    <form action="{{ route('logout') }}" method="POST" style="display: inline;" class="w-full"
                                          data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?"
                                          data-confirm-title="Déconnexion"
                                          data-confirm-type="warning"
                                          data-confirm-button="Déconnexion">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-b-lg font-medium flex items-center gap-2"><x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" /> Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <div class="flex">
                <!-- Sidebar -->
                <aside class="w-64 bg-white shadow-lg min-h-[calc(100vh-80px)] border-r-2 border-gray-100 sticky top-20">
                    <nav class="p-6 space-y-2">
                        <!-- Section Princ -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Principal</p>

                        <a href="{{ route('vendeur.dashboard') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.dashboard') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-chart-bar class="w-5 h-5" /> Tableau de Bord
                        </a>
                        <a href="{{ route('vendeur.apercu') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.apercu') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-eye class="w-5 h-5" /> Aperçu Boutique
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Section Gestion -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Gestion</p>

                        <a href="{{ route('vendeur.produits.index') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.produits.*') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-cube class="w-5 h-5" /> Mes Produits
                        </a>
                        <a href="{{ route('vendeur.stock') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.stock') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-document-text class="w-5 h-5" /> Gestion Stock
                        </a>
                        <a href="{{ route('vendeur.commandes') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.commandes*') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-shopping-cart class="w-5 h-5" /> Commandes
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Section Client -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Client</p>

                        <a href="{{ route('vendeur.avis') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.avis') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-star class="w-5 h-5" /> Avis Clients
                        </a>
                        <a href="{{ route('vendeur.messages') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.messages') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-chat-bubble-left class="w-5 h-5" /> Messages
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Section Compte -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Compte</p>

                        <a href="{{ route('vendeur.statistiques') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.statistiques') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-arrow-trending-up class="w-5 h-5" /> Statistiques
                        </a>
                        <a href="{{ route('vendeur.parametres') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.parametres') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }} flex items-center gap-2">
                            <x-heroicon-o-cog-6-tooth class="w-5 h-5" /> Paramètres
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Retour Boutique -->
                        <a href="{{ route('accueil') }}" class="block px-4 py-3 rounded-lg font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 flex items-center gap-2">
                            <x-heroicon-o-arrow-left class="w-5 h-5" /> Retour Boutique
                        </a>
                    </nav>
                </aside>

                <!-- Content Area -->
                <main class="flex-1">
                    @yield('content')
                </main>
            </div>

            <!-- Footer -->
            <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-gray-300 mt-16 border-t-4 border-primary-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <div>
                            <p class="font-bold text-white mb-3">Supply</p>
                            <p class="text-sm">Plateforme de vente en ligne pour revendeurs informatiques</p>
                        </div>
                        <div>
                            <p class="font-bold text-white mb-3">Contact</p>
                            <p class="text-sm flex items-center gap-2"><x-heroicon-o-envelope class="w-4 h-4" /> support@supply.fr</p>
                            <p class="text-sm flex items-center gap-2"><x-heroicon-o-phone class="w-4 h-4" /> 01 23 45 67 89</p>
                        </div>
                        <div>
                            <p class="font-bold text-white mb-3">Liens</p>
                            <p class="text-sm"><a href="#" class="hover:text-white transition">Centre d'aide</a></p>
                            <p class="text-sm"><a href="#" class="hover:text-white transition">Politique de confidentialité</a></p>
                        </div>
                    </div>
                    <div class="text-center border-t border-gray-700 pt-8">
                        <p class="text-sm">&copy; 2026 Supply - Espace Vendeur. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>

        <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
        <script>
            // Configuration WebSocket pour les notifications en temps réel
            const socketUrl = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' 
                ? 'http://127.0.0.1:3000'
                : `http://${window.location.hostname}:3000`;

            let socket = null;
            let socketConnected = false;

            // Initialiser la connexion WebSocket avec gestion d'erreur
            function initializeSocket() {
                try {
                    socket = io(socketUrl, {
                        reconnection: true,
                        reconnectionDelay: 1000,
                        reconnectionDelayMax: 5000,
                        reconnectionAttempts: 5,
                        transports: ['websocket', 'polling']
                    });

                    // Connexion de l'utilisateur au WebSocket
                    socket.on('connect', function() {
                        console.log('✅ Connecté au serveur WebSocket');
                        socketConnected = true;
                        socket.emit('user-connect', {
                            userId: currentUserId,
                            name: currentUserName
                        });
                    });

                    // Écouter les notifications de messages en temps réel
                    socket.on('message-notification', function(data) {
                        console.log('🔔 Notification de message reçue:', data);
                        updateNotificationsBadge();
                        
                        // Afficher une notification visuelle
                        showNotificationToast(data);
                        
                        // Rafraîchir le contenu du panel si ouvert
                        if (notificationsPanel && notificationsPanel.style.display === 'block') {
                            toggleNotifications(); // Fermer
                            // Attendre un peu avant de réouvrir pour rafraîchir les données
                            setTimeout(() => {
                                toggleNotifications(); // Rouvrir avec données fraîches
                            }, 300);
                        }
                    });

                    // Écouter la déconnexion
                    socket.on('disconnect', function() {
                        console.log('❌ Déconnecté du serveur WebSocket');
                        socketConnected = false;
                    });

                    // Gérer les erreurs WebSocket
                    socket.on('error', function(error) {
                        console.error('Erreur WebSocket:', error);
                        socketConnected = false;
                    });
                } catch (error) {
                    console.error('Erreur lors de l\'initialisation Socket.io:', error);
                    socketConnected = false;
                }
            }

            let notificationsPanel = null;
            const currentUserId = {{ auth()->user()->id }};
            const currentUserName = "{{ auth()->user()->name }}";

            function toggleNotifications() {
                const button = event.target.closest('button');
                
                // Créer ou récupérer le panneau de notifications
                if (!notificationsPanel) {
                    notificationsPanel = document.createElement('div');
                    notificationsPanel.id = 'notifications-panel';
                    document.body.appendChild(notificationsPanel);
                }

                // Toggle d'affichage
                if (notificationsPanel.style.display === 'block') {
                    notificationsPanel.style.display = 'none';
                    return;
                }

                // Charger les notifications
                const url = "{{ route('vendeur.notifications') }}";
                
                notificationsPanel.innerHTML = '<div class="bg-white rounded-lg shadow-lg p-4"><div class="flex justify-center"><span class="text-gray-500">Chargement...</span></div></div>';
                notificationsPanel.style.display = 'block';
                
                // Positionner le panneau
                const rect = button.getBoundingClientRect();
                notificationsPanel.style.position = 'fixed';
                notificationsPanel.style.top = (rect.bottom + 10) + 'px';
                notificationsPanel.style.right = '20px';
                notificationsPanel.style.width = '400px';
                notificationsPanel.style.maxHeight = '500px';
                notificationsPanel.style.overflowY = 'auto';
                notificationsPanel.style.zIndex = '1000';

                // Charger les données

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        renderNotifications(data);
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        notificationsPanel.innerHTML = '<div class="bg-white rounded-lg shadow-lg p-4"><div class="text-center text-red-500">Erreur lors du chargement</div></div>';
                    });
            }

            // Mapper les icones Heroicons
            function getIconSVG(iconName) {
                const icons = {
                    'chat-bubble-left': '<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z\" /></svg>',
                    'shopping-cart': '<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z\" /></svg>',
                    'star': '<svg class=\"w-6 h-6\" fill=\"currentColor\" viewBox=\"0 0 24 24\"><path d=\"M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z\" /></svg>',
                    'cube': '<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M20.325 3.507a2.001 2.001 0 00-2.38.72l-.896 1.345-.748 1.545m7.624 7.138a2.001 2.001 0 001.414-3.675l-1.08-.54m-5.906 7.045c.435-.435.536-1.112.211-1.664m2.26 5.015a2 2 0 10-3.464 2m6.514-1.285a15.075 15.075 0 002.29-4.571m-12.66 4.926A7.456 7.456 0 004.458 9m.007-.175a7.456 7.456 0 00 13.528 5.039M4 20h16\" /></svg>',
                    'bell': '<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\" /></svg>'
                };
                return icons[iconName] || icons['cube'];
            }

            function renderNotifications(data) {
                if (!data.notifications || data.notifications.length === 0) {
                    notificationsPanel.innerHTML = `
                        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                            <svg class="w-16 h-16 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-gray-500 font-medium">Aucune notification</p>
                            <p class="text-sm text-gray-400">Vous êtes à jour!</p>
                        </div>
                    `;
                    return;
                }

                let html = '<div class="bg-white rounded-lg shadow-lg overflow-hidden">';
                html += '<div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white p-4">';
                    html += '<h3 class="font-bold text-lg flex items-center gap-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg> Notifications</h3>';
                html += '<p class="text-sm text-primary-100">Vous avez ' + data.total + ' notification' + (data.total > 1 ? 's' : '') + '</p>';
                html += '</div>';

                html += '<div class="divide-y">';

                data.notifications.forEach(notification => {
                    html += '<div class="p-4 hover:bg-gray-50 cursor-pointer transition">';
                    html += '<div class="flex items-start justify-between mb-2">';
                    html += '<div class="flex items-center gap-2">';
                    html += '<div class=\"text-primary-600\">' + getIconSVG(notification.icon) + '</div>';
                    html += '<div>';
                    html += '<p class="font-bold text-gray-900">' + notification.title + '</p>';
                    html += '<span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full ';
                    
                    // Couleur basée sur le type
                    switch(notification.color) {
                        case 'blue':
                            html += 'bg-blue-100 text-blue-800';
                            break;
                        case 'orange':
                            html += 'bg-orange-100 text-orange-800';
                            break;
                        case 'yellow':
                            html += 'bg-yellow-100 text-yellow-800';
                            break;
                        case 'red':
                            html += 'bg-red-100 text-red-800';
                            break;
                        default:
                            html += 'bg-gray-100 text-gray-800';
                    }
                    
                    html += '">' + notification.type.toUpperCase() + '</span>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    // Afficher les détails
                    if (notification.data && notification.data.length > 0) {
                        html += '<div class="text-sm text-gray-600 space-y-1">';
                        notification.data.slice(0, 3).forEach(item => {
                            let itemName = item.nom || item.name || 'Élément';
                            html += '<div class="flex items-center gap-2 text-xs">';
                            html += '<span>•</span>';
                            html += '<span class="truncate">' + itemName + '</span>';
                            html += '</div>';
                        });
                        if (notification.data.length > 3) {
                            html += '<div class="text-xs text-gray-400 mt-1">... et ' + (notification.data.length - 3) + ' autre' + (notification.data.length - 3 > 1 ? 's' : '') + '</div>';
                        }
                        html += '</div>';
                    }

                    html += '<div class="mt-3">';
                    html += '<a href="' + notification.link + '" class="inline-block text-xs font-semibold text-primary-600 hover:text-primary-700 hover:underline">Voir détails →</a>';
                    html += '</div>';
                    html += '</div>';
                });

                html += '</div>';
                html += '</div>';

                notificationsPanel.innerHTML = html;
            }

            // Fermer le panneau quand on clique ailleurs
            document.addEventListener('click', function(event) {
                const button = document.querySelector('[onclick="toggleNotifications()"]');
                if (notificationsPanel && event.target !== button && !notificationsPanel.contains(event.target)) {
                    notificationsPanel.style.display = 'none';
                }
            });

            // Charger les notifications au démarrage de la page
            document.addEventListener('DOMContentLoaded', function() {
                // Initialiser WebSocket
                initializeSocket();
                
                updateNotificationsBadge();
                
                // Mettre à jour les notifications toutes les 30 secondes
                setInterval(updateNotificationsBadge, 30000);
            });

            function updateNotificationsBadge() {
                const url = "{{ route('vendeur.notifications') }}";
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notifications-badge');
                        if (badge) {
                            badge.textContent = data.total;
                            if (data.total === 0) {
                                badge.style.display = 'none';
                            } else {
                                badge.style.display = 'flex';
                            }
                        }
                    })
                    .catch(error => {
                        console.log('Erreur mise à jour notifications:', error);
                        // Afficher un nombre par défaut en cas d'erreur
                        const badge = document.getElementById('notifications-badge');
                        if (badge) {
                            badge.textContent = '?';
                        }
                    });
            }

            // Afficher une notification toast (petit message en haut à droite)
            function showNotificationToast(data) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-20 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm border-l-4 border-primary-500 animate-pulse z-50';
                
                let message = 'Nouveau message reçu';
                if (data.from_user_id) {
                    message = 'Vous avez un nouveau message';
                }
                if (data.preview) {
                    message += '<br><span class="text-xs text-gray-600 mt-1 block">"' + data.preview + '..."</span>';
                }
                
                toast.innerHTML = `
                    <div class="flex items-start gap-3">
                        <span class="text-2xl"><x-heroicon-o-chat-bubble-left class="w-5 h-5" /></span>
                        <div>
                            <p class="font-bold text-gray-900">Nouveau message!</p>
                            <p class="text-sm text-gray-600 mt-1">${message}</p>
                            <a href="{{ route('vendeur.messages') }}" class="inline-block mt-2 text-xs font-semibold text-primary-600 hover:text-primary-700">Voir →</a>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Supprimer après 5 secondes
                setTimeout(() => {
                    toast.style.animation = 'fade-out 0.3s ease-out forwards';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
        </script>

        <!-- Animation CSS pour les toasts -->
        <style>
            @keyframes fade-out {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(100px);
                }
            }
        </style>

        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @yield('scripts')
    </body>
</html>
