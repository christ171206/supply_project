<?php

use Illuminate\Support\Str;

return [
    'PWA Installation' => [
        'Chrome/Edge Desktop' => [
            'steps' => [
                '1. Visiter http://localhost:8000',
                '2. Cliquer l\'icône d\'installation (top-right)',
                '3. Cliquer "Installer"',
                '4. Supply se lance en mode standalone',
                '5. Accessible depuis le menu Démarrer / Applications'
            ],
            'url' => 'http://localhost:8000'
        ],
        'Android Chrome' => [
            'steps' => [
                '1. Ouvrir le site dans Chrome',
                '2. Menu (⋮) → "Installer l\'app"',
                '3. Confirmer l\'installation',
                '4. Supply s\'ajoute à l\'écran d\'accueil',
                '5. Fonctionne offline avec cache'
            ]
        ],
        'iOS Safari' => [
            'steps' => [
                '1. Ouvrir Safari',
                '2. Aller à supply.app',
                '3. Partage (↑) → "Sur l\'écran d\'accueil"',
                '4. Nommer et ajouter',
                '5. Accessible comme app native'
            ]
        ],
        'Windows Desktop' => [
            'steps' => [
                '1. Chrome/Edge sur le site',
                '2. Menu → App → "Installer Supply"',
                '3. Supply apparaît dans lancer d\'apps',
                '4. Créé raccourci sur le bureau',
                '5. Runs en mode standalone'
            ]
        ]
    ]
];
