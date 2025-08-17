<?php

return [
    'bleeding' => [
        'name' => 'Bleeding',
        'description' => 'Causes the target to lose health over 3 turns.',
    ],
    'deep_cut' => [
        'name' => 'Deep Cut',
        'duration' => 3,
        'max_hp_reduction_percent' => 10,
        'description' => 'Deep cut reduces maximum HP for 3 turns.'
    ],
    'stun' => [
        'name' => 'Stun',
        'description' => 'Prevents the enemy from acting for 1 turn.',
        'duration' => 1,
        'delay' => 1,
        'icon' => '/images/debuffs/stun.png',
    ],
    'damage_reduction' => [
        'name' => 'Damage Reduction',
        'description' => 'Lowers the enemy\'s damage output for 3 turns.',
    ],
];
