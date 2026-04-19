<?php

declare(strict_types=1);

return [
    'form' => [
        'contracted_beds_hint' => 'Anzahl der Betten die mindestens bezahlt werden müssen, auch wenn die tatsächliche Belegung darunter liegt.',
        'capacity_all' => 'Die Anzahl der Betten ist für die Jugenherberge begrenzt auf :total Betten. Bitte bedenke, dass die Betreuer auch Betten benötigen.',
        'capacity_gendered' => 'Die Anzahl der Betten ist für die Jugenherberge begrenzt auf :total Betten. Für die männlichen Teilnehmer sind :male Betten geplant und für die weiblichen Teilnehmer :female Betten. Die Summe darf nicht :total übersteigen. Bitte bedenke, dass die Betreuer auch Betten benötigen.',
    ],
    'checklist' => [
        'price_set' => [
            'label' => 'Teilnehmerpreis festgelegt',
            'description' => 'Der Preis pro Teilnehmer ist am Lager hinterlegt.',
        ],
        'registration_dates_set' => [
            'label' => 'Anmeldezeitraum festgelegt',
            'description' => 'Öffnungs- und Schlussdatum der Anmeldung sind konfiguriert.',
        ],
        'contract_signed' => [
            'label' => 'Vertrag abgeschlossen',
            'description' => 'Ein Herbergsvertrag wurde für dieses Lager erstellt.',
        ],
        'catering_arranged' => [
            'label' => 'Verpflegung organisiert',
            'description' => 'Essen und Getränke für das Lager sind organisiert.',
        ],
        'transportation_arranged' => [
            'label' => 'Transport organisiert',
            'description' => 'An- und Abreise zum Lager sind geregelt.',
        ],
        'materials_prepared' => [
            'label' => 'Material vorbereitet',
            'description' => 'Alle benötigten Materialien und Hilfsmittel sind bereit.',
        ],
        'staff_briefed' => [
            'label' => 'Betreuer eingewiesen',
            'description' => 'Alle Betreuer wurden über ihre Aufgaben informiert.',
        ],
        'emergency_contacts_collected' => [
            'label' => 'Notfallkontakte erfasst',
            'description' => 'Notfallkontakte wurden von allen Teilnehmern eingeholt.',
        ],
    ],
];
