<?php

declare(strict_types=1);

return [
    'invite_link' => [
        'label' => 'Mitglieder Einladen',
        'description' => 'Teile diesen Link, damit sich Mitglieder registrieren können. Der Link ist 30 Tage gültig.',
    ],
    'settings' => [
        'label' => 'Einstellungen',
        'description' => 'Weitere Einstellungen für euren Verein',
        'default_role_id' => [
            'label' => 'Standard-Rolle für neue Benutzer',
            'description' => 'Bitte wähle die Standard-Rolle aus, die neuen Benutzern zugewiesen werden soll.',
            'placeholder' => 'Standard Rolle auswählen',
        ],
    ],
];
