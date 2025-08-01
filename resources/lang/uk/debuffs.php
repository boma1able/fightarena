<?php

return [
    'bleeding' => [
        'name' => 'Кровотеча',
        'description' => 'Змушує ціль втрачати здоров’я протягом 3 ходів.',
    ],
    'accuracy_reduction' => [
        'name' => 'Зменшення точності',
        'description' => 'Зменшує точність ворога на 3 ходи.',
    ],
    'stun' => [
        'name' => 'Оглушення',
        'description' => 'Перешкоджає ворогу діяти протягом 1 ходу.',
        'duration' => 1,
        'delay' => 1,
        'icon' => '/images/debuffs/stun.png',
    ],
    'damage_reduction' => [
        'name' => 'Зменшення урону',
        'description' => 'Знижує урон ворога на 3 ходи.',
    ],
];
