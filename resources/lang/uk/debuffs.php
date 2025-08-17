<?php

return [
    'bleeding' => [
        'name' => 'Кровотеча',
        'description' => 'Змушує ціль втрачати здоров’я протягом 3 ходів.',
    ],
    'deep_cut' => [
        'name' => 'Глибокий поріз',
        'duration' => 3,
        'max_hp_reduction_percent' => 10,
        'description' => 'Глибокий поріз зменшує максимальне HP протягом 3 ходів.'
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
