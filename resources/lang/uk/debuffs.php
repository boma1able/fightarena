<?php

return [
    'bleeding' => [
        'name' => 'Кровотеча',
        'description' => 'Змушує ціль втрачати здоров’я протягом 3 ходів.',
        'icon' => '/images/debuffs/bleeding.png',
    ],
    'deep_cut' => [
        'name' => 'Глибокий поріз',
        'duration' => 3,
        'max_hp_reduction_percent' => 10,
        'description' => 'Глибокий поріз зменшує максимальне HP протягом 3 ходів.',
        'icon' => '/images/debuffs/deep_cut.png',
    ],
    'stun' => [
        'name' => 'Оглушення',
        'description' => 'Перешкоджає ворогу діяти протягом 1 ходу.',
        'duration' => 1,
        'delay' => 1,
        'icon' => '/images/debuffs/stun.png',
    ],
    'sunder' => [
        'name' => 'Sunder',
        'description' => 'Зменшує броню цілі, змушуючи її отримувати більше урона',
        'duration' => 3,
        'armor_reduction_percent' => 100,
        'icon' => '/images/debuffs/sunder.png',
    ],
];
