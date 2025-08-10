<?php

return [
    'bleeding' => [
        'name' => 'Bleeding',
        'description' => 'Causes the target to lose health over 3 turns.',
    ],
    'armor_reduction' => [
        'name' => 'Armor Reduction',
        'description' => 'Reduces the enemy\'s armor for 3 turns.',
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
