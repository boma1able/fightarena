<?php

return [
    'bleeding' => [
        'name' => 'Bleeding',
        'description' => 'Causes the target to lose health over 3 turns.',
        'icon' => '/images/debuffs/bleeding.png',
    ],
    'deep_cut' => [
        'name' => 'Deep Cut',
        'duration' => 3,
        'max_hp_reduction_percent' => 10,
        'description' => 'Deep cut reduces maximum HP for 3 turns.',
        'icon' => '/images/debuffs/deep_cut.png',
    ],
    'stun' => [
        'name' => 'Stun',
        'description' => 'Prevents the enemy from acting for 1 turn.',
        'duration' => 1,
        'delay' => 1,
        'icon' => '/images/debuffs/stun.png',
    ],
    'sunder' => [
        'name' => 'Sunder',
        'description' => 'Reduces the target’s armor, making them take more damage.',
        'duration' => 3,
        'armor_reduction_percent' => 100,
        'icon' => '/images/debuffs/sunder.png',
    ],
];
