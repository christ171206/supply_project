<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les catégories enrichies (18 catégories)
        $categories = [
            // Informatique
            ['nom' => 'Ordinateurs Portables', 'slug' => 'ordinateurs-portables', 'description' => 'Laptops performants pour le travail et loisirs', 'image' => 'Ordinateur portable.jpg'],
            ['nom' => 'Ordinateurs de Bureau', 'slug' => 'ordinateurs-bureau', 'description' => 'Unités centrales et tours gaming', 'image' => 'Ordinateur portable.jpg'],
            ['nom' => 'Écrans & Moniteurs', 'slug' => 'ecrans-moniteurs', 'description' => 'Moniteurs haute résolution et gaming', 'image' => 'Ecran.jpg'],

            // Périphériques
            ['nom' => 'Claviers', 'slug' => 'claviers', 'description' => 'Claviers mécaniques et sans fil', 'image' => 'Clavier.jpg'],
            ['nom' => 'Souris & Trackpads', 'slug' => 'souris-trackpads', 'description' => 'Souris gamer et ergonomiques', 'image' => 'Souris.jpg'],
            ['nom' => 'Casques Audio', 'slug' => 'casques-audio', 'description' => 'Casques pour gaming, musique et appels', 'image' => 'Casque audio.jpg'],
            ['nom' => 'Webcams & Microphones', 'slug' => 'webcams-microphones', 'description' => 'Caméras, microphones pour visioconférence et streaming', 'image' => 'Webcams.jpg'],

            // Stockage & Mémoire
            ['nom' => 'SSD & HDD', 'slug' => 'ssd-hdd', 'description' => 'Disques durs et SSD haute vitesse', 'image' => 'storage.jpg'],
            ['nom' => 'Clés USB & Cartes Mémoire', 'slug' => 'cles-usb-cartes', 'description' => 'Clés USB rapides et cartes microSD', 'image' => 'usb.jpg'],

            // Accessoires
            ['nom' => 'Tapis & Supports', 'slug' => 'tapis-supports', 'description' => 'Tapis souris, supports écran et laptop', 'image' => 'mousepad.jpg'],
            ['nom' => 'Câbles & Connecteurs', 'slug' => 'cables-connecteurs', 'description' => 'Câbles USB, HDMI, de données', 'image' => 'cables.jpg'],
            ['nom' => 'Hubs & Docking', 'slug' => 'hubs-docking', 'description' => 'Hubs USB et stations d\'accueil', 'image' => 'hub.jpg'],

            // Réseau & Connectivité
            ['nom' => 'Routeurs & Modems', 'slug' => 'routeurs-modems', 'description' => 'Équipements réseau haute vitesse', 'image' => 'router.jpg'],
            ['nom' => 'Adaptateurs Réseau', 'slug' => 'adaptateurs-reseau', 'description' => 'Cartes réseau et adaptateurs Wifi', 'image' => 'adapter.jpg'],

            // Composants
            ['nom' => 'Processeurs', 'slug' => 'processeurs', 'description' => 'CPUs Intel et AMD dernière génération', 'image' => 'cpu.jpg'],
            ['nom' => 'Cartes Graphiques', 'slug' => 'cartes-graphiques', 'description' => 'GPUs NVIDIA RTX et AMD radeon', 'image' => 'gpu.jpg'],
            ['nom' => 'Mémoire RAM', 'slug' => 'memoire-ram', 'description' => 'RAM DDR4 et DDR5', 'image' => 'ram.jpg'],

            // Alimentation & Refroidissement
            ['nom' => 'Alimentations & Refroidissement', 'slug' => 'alimentations-refroidissement', 'description' => 'Blocs d\'alimentation, refroidisseurs, ventilateurs', 'image' => 'psu.jpg'],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // Produits massif avec prix en FCFA - 100+ produits
        $produits = [
            // ORDINATEURS PORTABLES (1)
            ['categorie_id' => 1, 'nom' => 'Dell XPS 13 Plus M4', 'slug' => 'dell-xps-13-plus', 'description' => 'Ultrabook 13.4" FHD, Intel Core i7, SSD 512GB', 'prix' => 850000, 'stock' => 15, 'stock_minimum' => 5, 'image' => 'Dell XPS 13.jpg'],
            ['categorie_id' => 1, 'nom' => 'MacBook Pro 14 M3', 'slug' => 'macbook-pro-14-m3', 'description' => 'Processeur M3 Pro, 16GB RAM, 512GB SSD', 'prix' => 1310000, 'stock' => 8, 'stock_minimum' => 3, 'image' => 'MacBook Pro 14.jpg'],
            ['categorie_id' => 1, 'nom' => 'ASUS TUF Gaming A17', 'slug' => 'asus-tuf-a17', 'description' => 'Laptop gaming 17.3", RTX 4060, Ryzen 7', 'prix' => 1050000, 'stock' => 12, 'stock_minimum' => 5, 'image' => 'ASUS TUF Gaming F15.jpg'],
            ['categorie_id' => 1, 'nom' => 'HP Pavilion 15', 'slug' => 'hp-pavilion-15', 'description' => 'Portable 15.6" IPS, Intel i5, SSD 256GB', 'prix' => 550000, 'stock' => 20, 'stock_minimum' => 8, 'image' => 'hp-pavilion.jpg'],
            ['categorie_id' => 1, 'nom' => 'Lenovo ThinkPad X1', 'slug' => 'lenovo-thinkpad-x1', 'description' => 'Business laptop 14", Intel Core i7, sécurité avancée', 'prix' => 920000, 'stock' => 10, 'stock_minimum' => 4, 'image' => 'lenovo-x1.jpg'],
            ['categorie_id' => 1, 'nom' => 'MSI GF65 Thin Gaming', 'slug' => 'msi-gf65-gaming', 'description' => 'Gaming 15.6" 144Hz, RTX 4050, Intel i7', 'prix' => 800000, 'stock' => 14, 'stock_minimum' => 5, 'image' => 'msi-gf65.jpg'],

            // ORDINATEURS DE BUREAU (2)
            ['categorie_id' => 2, 'nom' => 'PC Gamer I9-13900K RTX 4090', 'slug' => 'pc-gamer-i9-rtx4090', 'description' => 'Tour gaming haute-end, i9, RTX 4090, 32GB RAM', 'prix' => 3500000, 'stock' => 3, 'stock_minimum' => 1, 'image' => 'pc-gamer-ultra.jpg'],
            ['categorie_id' => 2, 'nom' => 'PC Bureau i5-13600 GTX 1650', 'slug' => 'pc-bureau-i5', 'description' => 'Unité centrale bureau, i5, GTX 1650, 16GB RAM', 'prix' => 900000, 'stock' => 18, 'stock_minimum' => 7, 'image' => 'pc-bureau.jpg'],
            ['categorie_id' => 2, 'nom' => 'Workstation Ryzen 7 Pro RTX 5000', 'slug' => 'workstation-ryzen7', 'description' => 'Poste travail pro, Ryzen 7 Pro, RTX 5000, 32GB RAM', 'prix' => 2400000, 'stock' => 5, 'stock_minimum' => 2, 'image' => 'workstation.jpg'],
            ['categorie_id' => 2, 'nom' => 'Mini PC Intel NUC i7', 'slug' => 'mini-pc-nuc', 'description' => 'Petit format ultra-compact, i7, SSD 512GB', 'prix' => 450000, 'stock' => 25, 'stock_minimum' => 10, 'image' => 'mini-pc.jpg'],

            // ÉCRANS & MONITEURS (3)
            ['categorie_id' => 3, 'nom' => 'LG UltraWide 34" 3440x1440', 'slug' => 'lg-ultrawide-34', 'description' => 'Écran ultra-large courbe 34", 100Hz, USB-C', 'prix' => 595000, 'stock' => 10, 'stock_minimum' => 3, 'image' => 'LG UltraWide 34.jpg'],
            ['categorie_id' => 3, 'nom' => 'Dell S2721DGF 27" 165Hz', 'slug' => 'dell-s2721dgf', 'description' => 'Gaming 27" QHD 165Hz, 1ms, G-Sync', 'prix' => 385000, 'stock' => 18, 'stock_minimum' => 8, 'image' => 'Dell S2721DGF.jpg'],
            ['categorie_id' => 3, 'nom' => 'ASUS ProArt PA278CV 27"', 'slug' => 'asus-proart-pa278', 'description' => 'Moniteur créatif 27" IPS, 98.5% DCI-P3', 'prix' => 430000, 'stock' => 7, 'stock_minimum' => 2, 'image' => 'LG UltraWide 34.jpg'],
            ['categorie_id' => 3, 'nom' => 'BenQ PD2500Q 25" Pro', 'slug' => 'benq-pd2500q', 'description' => 'Écran professionnel 25" QHD, calibré', 'prix' => 460000, 'stock' => 7, 'stock_minimum' => 2, 'image' => 'BenQ PD2500Q.jpg'],
            ['categorie_id' => 3, 'nom' => 'MSI MEG 321URF 32" Gaming', 'slug' => 'msi-meg-321urf', 'description' => 'Gaming 32" 4K 144Hz, Mini LED, HDR1400', 'prix' => 920000, 'stock' => 6, 'stock_minimum' => 2, 'image' => 'Dell S2721DGF.jpg'],

            // CLAVIERS (4)
            ['categorie_id' => 4, 'nom' => 'Corsair K95 RGB Platinum', 'slug' => 'corsair-k95-rgb', 'description' => 'Clavier gaming mécanique Cherry MX, 8 touches macro', 'prix' => 185000, 'stock' => 25, 'stock_minimum' => 10, 'image' => 'Corsair K95 RGB Platinum.jfif'],
            ['categorie_id' => 4, 'nom' => 'Logitech MX Keys Advanced', 'slug' => 'logitech-mx-keys', 'description' => 'Clavier sans fil multi-appareils, rétroéclairage', 'prix' => 95000, 'stock' => 30, 'stock_minimum' => 12, 'image' => 'Logitech MX Keys.jpg'],
            ['categorie_id' => 4, 'nom' => 'SteelSeries Apex Pro', 'slug' => 'steelseries-apex-pro', 'description' => 'Clavier gaming touches OmniPoint ajustables', 'prix' => 150000, 'stock' => 20, 'stock_minimum' => 8, 'image' => 'SteelSeries Apex Pro.jpeg'],
            ['categorie_id' => 4, 'nom' => 'Keychron K8 Pro', 'slug' => 'keychron-k8-pro', 'description' => 'Clavier mécanique sans fil compact rétroéclairé', 'prix' => 85000, 'stock' => 35, 'stock_minimum' => 15, 'image' => 'Logitech MX Keys.jpg'],
            ['categorie_id' => 4, 'nom' => 'Ducky One 3', 'slug' => 'ducky-one-3', 'description' => 'Clavier mécanique gaming Cherry MX RGB', 'prix' => 120000, 'stock' => 28, 'stock_minimum' => 12, 'image' => 'Corsair K95 RGB Platinum.jfif'],

            // SOURIS & TRACKPADS (5)
            ['categorie_id' => 5, 'nom' => 'Logitech MX Master 3S', 'slug' => 'logitech-mx-master-3s', 'description' => 'Souris sans fil ergonomique multi-appareils', 'prix' => 75000, 'stock' => 35, 'stock_minimum' => 15, 'image' => 'Logitech MX Master 3S.jpg'],
            ['categorie_id' => 5, 'nom' => 'Razer DeathAdder V3', 'slug' => 'razer-deathadder-v3', 'description' => 'Souris gaming légère optique sans fil', 'prix' => 55000, 'stock' => 40, 'stock_minimum' => 18, 'image' => 'Razer DeathAdder V3.jfif'],
            ['categorie_id' => 5, 'nom' => 'SteelSeries Rival 5', 'slug' => 'steelseries-rival-5', 'description' => 'Souris gaming capteur TrueMove Air 18000 DPI', 'prix' => 40000, 'stock' => 45, 'stock_minimum' => 20, 'image' => 'SteelSeries Rival 5.jpg'],
            ['categorie_id' => 5, 'nom' => 'Apple Magic Trackpad 2', 'slug' => 'apple-magic-trackpad-2', 'description' => 'Trackpad sans fil rechargeable pour Mac', 'prix' => 110000, 'stock' => 15, 'stock_minimum' => 6, 'image' => 'Logitech MX Master 3S.jpg'],
            ['categorie_id' => 5, 'nom' => 'Microsoft Pro Intellimouse', 'slug' => 'microsoft-pro-intellimouse', 'description' => 'Souris ergonomique professionnelle sans fil', 'prix' => 50000, 'stock' => 32, 'stock_minimum' => 14, 'image' => 'SteelSeries Rival 5.jpg'],

            // CASQUES AUDIO (6)
            ['categorie_id' => 6, 'nom' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5', 'description' => 'Casque ANC premium réduction bruit active', 'prix' => 300000, 'stock' => 12, 'stock_minimum' => 5, 'image' => 'Sony WH-1000XM5.jpg'],
            ['categorie_id' => 6, 'nom' => 'SteelSeries Arctis Nova Pro', 'slug' => 'steelseries-arctis-nova', 'description' => 'Casque gaming sans fil USB 2.4GHz', 'prix' => 320000, 'stock' => 16, 'stock_minimum' => 6, 'image' => 'SteelSeries Arctis 9.jfif'],
            ['categorie_id' => 6, 'nom' => 'JBL Quantum 800', 'slug' => 'jbl-quantum-800', 'description' => 'Casque gaming surround 7.1 sans fil', 'prix' => 190000, 'stock' => 20, 'stock_minimum' => 8, 'image' => 'JBL Quantum 800.jpg'],
            ['categorie_id' => 6, 'nom' => 'Sennheiser Momentum 4', 'slug' => 'sennheiser-momentum-4', 'description' => 'Casque audio premium audiophile sans fil', 'prix' => 220000, 'stock' => 14, 'stock_minimum' => 5, 'image' => 'Sony WH-1000XM5.jpg'],
            ['categorie_id' => 6, 'nom' => 'Bose QuietComfort 45', 'slug' => 'bose-qc45', 'description' => 'Casque élite ANC réduction bruit Bose', 'prix' => 280000, 'stock' => 11, 'stock_minimum' => 4, 'image' => 'Sony WH-1000XM5.jpg'],

            // WEBCAMS & MICROPHONES (7)
            ['categorie_id' => 7, 'nom' => 'Logitech C920 HD Pro', 'slug' => 'logitech-c920-hd', 'description' => 'Webcam USB Full HD 1080p autofocus', 'prix' => 60000, 'stock' => 50, 'stock_minimum' => 20, 'image' => 'logitech-c920.jpg'],
            ['categorie_id' => 7, 'nom' => 'Razer Kiyo Pro', 'slug' => 'razer-kiyo-pro', 'description' => 'Webcam gaming 1080p 60fps capteur Sony', 'prix' => 150000, 'stock' => 18, 'stock_minimum' => 7, 'image' => 'razer-kiyo.jpg'],
            ['categorie_id' => 7, 'nom' => 'Elgato Facecam', 'slug' => 'elgato-facecam', 'description' => 'Webcam 1080p pour streamers autofocus', 'prix' => 130000, 'stock' => 22, 'stock_minimum' => 9, 'image' => 'elgato-facecam.jpg'],
            ['categorie_id' => 7, 'nom' => 'Logitech BRIO 4K', 'slug' => 'logitech-brio-4k', 'description' => 'Webcam 4K 30fps HDR autofocus', 'prix' => 220000, 'stock' => 9, 'stock_minimum' => 3, 'image' => 'logitech-brio.jpg'],
            ['categorie_id' => 7, 'nom' => 'Audio-Technica AT2020', 'slug' => 'at2020-condenser', 'description' => 'Microphone condensateur cardioïde studio professionnel', 'prix' => 150000, 'stock' => 26, 'stock_minimum' => 10, 'image' => 'at2020.jpg'],
            ['categorie_id' => 7, 'nom' => 'Rode Procaster Broadcasting', 'slug' => 'rode-procaster', 'description' => 'Micro dynamique broadcast radio podcast', 'prix' => 190000, 'stock' => 15, 'stock_minimum' => 6, 'image' => 'rode-procaster.jpg'],
            ['categorie_id' => 7, 'nom' => 'Blue Yeti USB Streaming', 'slug' => 'blue-yeti-usb', 'description' => 'Micro USB condenser gaming streaming coloré', 'prix' => 95000, 'stock' => 32, 'stock_minimum' => 12, 'image' => 'blue-yeti.jpg'],
            ['categorie_id' => 7, 'nom' => 'Shure SM7B Studio', 'slug' => 'shure-sm7b', 'description' => 'Micro dynamique réduction bruit professionnel', 'prix' => 425000, 'stock' => 5, 'stock_minimum' => 2, 'image' => 'shure-sm7b.jpg'],

            // SSD & HDD (8)
            ['categorie_id' => 8, 'nom' => 'Samsung 990 Pro NVMe 2TB', 'slug' => 'samsung-990-pro-2tb', 'description' => 'SSD NVMe PCIe 4.0 ultra rapide 2TB', 'prix' => 220000, 'stock' => 45, 'stock_minimum' => 18, 'image' => 'samsung-990.jpg'],
            ['categorie_id' => 8, 'nom' => 'Western Digital Blue SSD 1TB', 'slug' => 'wd-blue-ssd-1tb', 'description' => 'SSD SATA 2.5" fiable 1TB', 'prix' => 110000, 'stock' => 55, 'stock_minimum' => 20, 'image' => 'wd-blue.jpg'],
            ['categorie_id' => 8, 'nom' => 'Seagate IronWolf HDD 4TB', 'slug' => 'seagate-ironwolf-4tb', 'description' => 'Disque dur interne 4TB 5400 RPM NAS', 'prix' => 95000, 'stock' => 30, 'stock_minimum' => 12, 'image' => 'seagate-ironwolf.jpg'],
            ['categorie_id' => 8, 'nom' => 'Crucial MX500 SSD 500GB', 'slug' => 'crucial-mx500-500gb', 'description' => 'SSD SATA compact 500GB haute vitesse', 'prix' => 55000, 'stock' => 40, 'stock_minimum' => 15, 'image' => 'crucial-mx500.jpg'],

            // CLÉS USB & CARTES MÉMOIRE (9)
            ['categorie_id' => 9, 'nom' => 'SanDisk Extreme USB 3.1 128GB', 'slug' => 'sandisk-extreme-128gb', 'description' => 'Clé USB 3.1 ultra rapide 128GB', 'prix' => 45000, 'stock' => 60, 'stock_minimum' => 25, 'image' => 'sandisk-extreme.jpg'],
            ['categorie_id' => 9, 'nom' => 'Kingston DataTraveler 512GB', 'slug' => 'kingston-datatraveler-512gb', 'description' => 'Clé USB 3.2 haute capacité 512GB', 'prix' => 120000, 'stock' => 35, 'stock_minimum' => 14, 'image' => 'kingston-dt.jpg'],
            ['categorie_id' => 9, 'nom' => 'Samsung Pro Plus microSD 512GB', 'slug' => 'samsung-pro-plus-512gb', 'description' => 'Carte micr SD 512GB haute vitesse A2', 'prix' => 85000, 'stock' => 50, 'stock_minimum' => 20, 'image' => 'samsung-microsd.jpg'],
            ['categorie_id' => 9, 'nom' => 'SanDisk Extreme Pro SD 256GB', 'slug' => 'sandisk-extreme-sd-256gb', 'description' => 'Carte SD UDMA 256GB caméra professionnel', 'prix' => 95000, 'stock' => 28, 'stock_minimum' => 10, 'image' => 'sandisk-sd.jpg'],

            // TAPIS & SUPPORTS (10)
            ['categorie_id' => 10, 'nom' => 'SteelSeries QcK Premium Mousepad', 'slug' => 'steelseries-qck-premium', 'description' => 'Tapis souris 450x400mm surface de précision', 'prix' => 35000, 'stock' => 70, 'stock_minimum' => 30, 'image' => 'steelseries-qck.jpg'],
            ['categorie_id' => 10, 'nom' => 'Razer Goliathus Extended RGB', 'slug' => 'razer-goliathus-rgb', 'description' => 'Tapis souris XXL RGB gaming 920x294mm', 'prix' => 65000, 'stock' => 40, 'stock_minimum' => 15, 'image' => 'razer-goliathus.jpg'],
            ['categorie_id' => 10, 'nom' => 'AmazonBasics Laptop Stand', 'slug' => 'amazonbasics-laptop-stand', 'description' => 'Support laptop métal ergonomique ajustable', 'prix' => 25000, 'stock' => 80, 'stock_minimum' => 35, 'image' => 'amazonbasics-stand.jpg'],
            ['categorie_id' => 10, 'nom' => 'Ergotron Monitor Arm Dual', 'slug' => 'ergotron-monitor-arm', 'description' => 'Bras support double écrans VESA articul', 'prix' => 180000, 'stock' => 18, 'stock_minimum' => 7, 'image' => 'ergotron-arm.jpg'],

            // CÂBLES & CONNECTEURS (11)
            ['categorie_id' => 11, 'nom' => 'Anker USB-C Cable 3Pack', 'slug' => 'anker-usb-c-3pack', 'description' => '3 câbles USB-C nylon tressé 2m chacun', 'prix' => 35000, 'stock' => 100, 'stock_minimum' => 40, 'image' => 'anker-usbc.jpg'],
            ['categorie_id' => 11, 'nom' => 'Belkin HDMI 2.1 Cable 8K', 'slug' => 'belkin-hdmi-2.1-8k', 'description' => 'Câble HDMI 2.1 8K 60Hz haute vitesse', 'prix' => 45000, 'stock' => 55, 'stock_minimum' => 20, 'image' => 'belkin-hdmi.jpg'],
            ['categorie_id' => 11, 'nom' => 'AmazonBasics Ethernet CAT7', 'slug' => 'amazonbasics-ethernet-cat7', 'description' => 'Câble Ethernet CAT7 15m RJ45', 'prix' => 30000, 'stock' => 65, 'stock_minimum' => 25, 'image' => 'amazonbasics-ethernet.jpg'],
            ['categorie_id' => 11, 'nom' => 'Startech DP to HDMI Adapter', 'slug' => 'startech-dp-hdmi', 'description' => 'Adaptateur DisplayPort vers HDMI 4K', 'prix' => 50000, 'stock' => 40, 'stock_minimum' => 15, 'image' => 'startech-adapter.jpg'],

            // HUBS & DOCKING (12)
            ['categorie_id' => 12, 'nom' => 'Anker USB Hub 7 Ports', 'slug' => 'anker-hub-7ports', 'description' => 'Hub USB 3.0 7 ports haute vitesse 30W', 'prix' => 70000, 'stock' => 45, 'stock_minimum' => 18, 'image' => 'anker-hub-7.jpg'],
            ['categorie_id' => 12, 'nom' => 'Caldigit TS4 Thunderbolt 4', 'slug' => 'caldigit-ts4-tb4', 'description' => 'Dock Thunderbolt 4 compact 98W', 'prix' => 480000, 'stock' => 8, 'stock_minimum' => 3, 'image' => 'caldigit-ts4.jpg'],
            ['categorie_id' => 12, 'nom' => 'Belkin USB-C Multiport Hub', 'slug' => 'belkin-usbc-multiport', 'description' => 'Hub USB-C 5 ports HDMI USB-A SD', 'prix' => 95000, 'stock' => 35, 'stock_minimum' => 14, 'image' => 'belkin-hub.jpg'],

            // ROUTEURS & MODEMS (13)
            ['categorie_id' => 13, 'nom' => 'ASUS AX6000 Router Pro', 'slug' => 'asus-ax6000-router', 'description' => 'Routeur WiFi 6 AX6000 ultra rapide Gaming', 'prix' => 380000, 'stock' => 20, 'stock_minimum' => 8, 'image' => 'asus-ax6000.jpg'],
            ['categorie_id' => 13, 'nom' => 'TP-Link Deco X90 Mesh WiFi', 'slug' => 'tplink-deco-x90', 'description' => 'Système maillage WiFi 6 3-pack couverture', 'prix' => 420000, 'stock' => 15, 'stock_minimum' => 6, 'image' => 'tplink-deco.jpg'],
            ['categorie_id' => 13, 'nom' => 'Netgear Nighthawk Pro Gaming', 'slug' => 'netgear-nighthawk-pro', 'description' => 'Routeur gaming WiFi 6E très haut débit', 'prix' => 530000, 'stock' => 10, 'stock_minimum' => 4, 'image' => 'netgear-nighthawk.jpg'],

            // ADAPTATEURS RÉSEAU (14)
            ['categorie_id' => 14, 'nom' => 'TP-Link WiFi USB Adapter', 'slug' => 'tplink-wifi-usb', 'description' => 'Adaptateur Wifi USB Double bande 600Mbps', 'prix' => 35000, 'stock' => 70, 'stock_minimum' => 30, 'image' => 'tplink-wifi-usb.jpg'],
            ['categorie_id' => 14, 'nom' => 'Intel Wifi 6E AX210', 'slug' => 'intel-wifi6e-ax210', 'description' => 'Carte réseau WiFi 6E PCIe ultra rapide', 'prix' => 120000, 'stock' => 40, 'stock_minimum' => 16, 'image' => 'intel-ax210.jpg'],

            // PROCESSEURS (15)
            ['categorie_id' => 15, 'nom' => 'Intel Core i9-13900KS', 'slug' => 'intel-i9-13900ks', 'description' => 'CPU flagship Intel 24 cœurs ultra haute fréq', 'prix' => 750000, 'stock' => 12, 'stock_minimum' => 5, 'image' => 'intel-i9-13900ks.jpg'],
            ['categorie_id' => 15, 'nom' => 'AMD Ryzen 9 7950X3D', 'slug' => 'amd-ryzen9-7950x3d', 'description' => 'CPU gaming 16 cœurs 3D V-Cache ultra rapide', 'prix' => 680000, 'stock' => 14, 'stock_minimum' => 6, 'image' => 'amd-7950x3d.jpg'],
            ['categorie_id' => 15, 'nom' => 'Intel Core i7-13700K', 'slug' => 'intel-i7-13700k', 'description' => 'CPU puissant 16 cœurs gaming travail', 'prix' => 480000, 'stock' => 20, 'stock_minimum' => 8, 'image' => 'intel-i7-13700k.jpg'],
            ['categorie_id' => 15, 'nom' => 'AMD Ryzen 7 7700X', 'slug' => 'amd-ryzen7-7700x', 'description' => 'CPU productivity 8 cœurs Zen 4 X670', 'prix' => 350000, 'stock' => 25, 'stock_minimum' => 10, 'image' => 'amd-7700x.jpg'],

            // CARTES GRAPHIQUES (16)
            ['categorie_id' => 16, 'nom' => 'NVIDIA RTX 4090 24GB', 'slug' => 'nvidia-rtx4090', 'description' => 'GPU flagship gaming 24GB ultra performance', 'prix' => 2800000, 'stock' => 3, 'stock_minimum' => 1, 'image' => 'rtx4090.jpg'],
            ['categorie_id' => 16, 'nom' => 'NVIDIA RTX 4080 16GB', 'slug' => 'nvidia-rtx4080', 'description' => 'GPU haute-end gaming 16GB haute perf', 'prix' => 1850000, 'stock' => 6, 'stock_minimum' => 2, 'image' => 'rtx4080.jpg'],
            ['categorie_id' => 16, 'nom' => 'NVIDIA RTX 4070 12GB', 'slug' => 'nvidia-rtx4070', 'description' => 'GPU gaming mid-range 12GB bon rapport', 'prix' => 950000, 'stock' => 15, 'stock_minimum' => 6, 'image' => 'rtx4070.jpg'],
            ['categorie_id' => 16, 'nom' => 'AMD Radeon RX 7900XT 20GB', 'slug' => 'amd-rx7900xt', 'description' => 'GPU gaming AMD 20GB compétitif RTX', 'prix' => 1480000, 'stock' => 8, 'stock_minimum' => 3, 'image' => 'rx7900xt.jpg'],

            // MÉMOIRE RAM (17)
            ['categorie_id' => 17, 'nom' => 'Corsair Vengeance DDR5 64GB', 'slug' => 'corsair-vengeance-ddr5-64gb', 'description' => 'RAM DDR5 64GB (2x32) 5600MHz CAS36', 'prix' => 450000, 'stock' => 30, 'stock_minimum' => 12, 'image' => 'corsair-vengeance-ddr5.jpg'],
            ['categorie_id' => 17, 'nom' => 'G.Skill Trident Z5 32GB DDR5', 'slug' => 'gskill-tridentz5-32gb', 'description' => 'RAM DDR5 32GB (2x16) 6000Mhz gaming', 'prix' => 220000, 'stock' => 40, 'stock_minimum' => 16, 'image' => 'gskill-z5.jpg'],
            ['categorie_id' => 17, 'nom' => 'Kingston Kingston FURY DDR4 32GB', 'slug' => 'kingston-fury-ddr4-32gb', 'description' => 'RAM DDR4 32GB (2x16) 3600MHz haute perf', 'prix' => 140000, 'stock' => 50, 'stock_minimum' => 20, 'image' => 'kingston-fury.jpg'],
            ['categorie_id' => 17, 'nom' => 'Crucial Ballistix DDR4 16GB', 'slug' => 'crucial-ballistix-16gb', 'description' => 'RAM DDR4 16GB (2x8) 3200MHz gaming', 'prix' => 75000, 'stock' => 60, 'stock_minimum' => 25, 'image' => 'crucial-ballistix.jpg'],

            // ALIMENTATIONS & REFROIDISSEMENT (18)
            ['categorie_id' => 18, 'nom' => 'Corsair RM1000e 1000W Gold', 'slug' => 'corsair-rm1000e', 'description' => 'PSU 1000W 80+ Gold modulaire certifié', 'prix' => 280000, 'stock' => 22, 'stock_minimum' => 9, 'image' => 'corsair-rm1000e.jpg'],
            ['categorie_id' => 18, 'nom' => 'EVGA SuperNOVA 850 G6', 'slug' => 'evga-supernova-850', 'description' => 'Alimentation 850W 80+ Gold modulaire', 'prix' => 200000, 'stock' => 28, 'stock_minimum' => 11, 'image' => 'evga-850g6.jpg'],
            ['categorie_id' => 18, 'nom' => 'BeQuiet Pure Power 650W', 'slug' => 'bequiet-pure-power-650w', 'description' => 'PSU 650W silencieuse 80+ Bronze quiet', 'prix' => 120000, 'stock' => 35, 'stock_minimum' => 14, 'image' => 'bequiet-650.jpg'],
            ['categorie_id' => 18, 'nom' => 'APC Back-UPS 1500VA UPS', 'slug' => 'apc-backups-1500va', 'description' => 'Onduleur 1500VA batterie de secours', 'prix' => 320000, 'stock' => 16, 'stock_minimum' => 6, 'image' => 'apc-backups.jpg'],
            ['categorie_id' => 18, 'nom' => 'Noctua NH-U14S CPU Cooler', 'slug' => 'noctua-nhu14s', 'description' => 'Refroidisseur CPU air silencieux haute perf', 'prix' => 140000, 'stock' => 30, 'stock_minimum' => 12, 'image' => 'noctua-u14s.jpg'],
            ['categorie_id' => 18, 'nom' => 'Corsair Liquid H150i Elite Capellix', 'slug' => 'corsair-h150i-elite', 'description' => 'Refroidisseur liquide AIO 360mm RGB LED', 'prix' => 220000, 'stock' => 20, 'stock_minimum' => 8, 'image' => 'corsair-h150i.jpg'],
            ['categorie_id' => 18, 'nom' => 'Arctic Noctua NF-A12x25', 'slug' => 'arctic-fan-a12', 'description' => 'Ventilateur silencieux 120mm premium', 'prix' => 35000, 'stock' => 80, 'stock_minimum' => 30, 'image' => 'arctic-a12.jpg'],
            ['categorie_id' => 18, 'nom' => 'Thermaltake ToughFan 15', 'slug' => 'thermaltake-toughfan-15', 'description' => 'Set 3 ventilateurs RGB 120mm gaming', 'prix' => 95000, 'stock' => 40, 'stock_minimum' => 16, 'image' => 'thermaltake-fan.jpg'],
        ];

        foreach ($produits as $produit) {
            Produit::create($produit);
        }
    }
}

