<?php

return [
    'helmet' => [
        ['level_min' => 1, 'level_max' => 5, 'zones' => ['head' => [1, 3]]],
        ['level_min' => 6, 'level_max' => 10, 'zones' => ['head' => [2, 6]]],
        ['level_min' => 11, 'level_max' => 15, 'zones' => ['head' => [4, 10]]],
        ['level_min' => 16, 'level_max' => 20, 'zones' => ['head' => [7, 13]]],
    ],
    'armor' => [
        ['level_min' => 1, 'level_max' => 5, 'zones' => ['chest' => [1, 3], 'belly' => [1, 2]]],
        ['level_min' => 6, 'level_max' => 10, 'zones' => ['chest' => [2, 6], 'belly' => [1, 3]]],
        ['level_min' => 11, 'level_max' => 15, 'zones' => ['chest' => [4, 10], 'belly' => [2, 5]]],
        ['level_min' => 16, 'level_max' => 20, 'zones' => ['chest' => [6, 12], 'belly' => [4, 8]]],
    ],
    'arms' => [
        ['level_min' => 1, 'level_max' => 5, 'zones' => ['belly' => [1, 2], 'belt' => [1, 2]]],
        ['level_min' => 6, 'level_max' => 10, 'zones' => ['belly' => [1, 3], 'belt' => [1, 3]]],
        ['level_min' => 11, 'level_max' => 15, 'zones' => ['belly' => [2, 5], 'belt' => [2, 5]]],
        ['level_min' => 16, 'level_max' => 20, 'zones' => ['belly' => [3, 8], 'belt' => [4, 6]]],
    ],
    'legs' => [
        ['level_min' => 1, 'level_max' => 5, 'zones' => ['legs' => [1, 2], 'belt' => [1, 2]]],
        ['level_min' => 6, 'level_max' => 10, 'zones' => ['legs' => [1, 3], 'belt' => [1, 3]]],
        ['level_min' => 11, 'level_max' => 15, 'zones' => ['legs' => [2, 5], 'belt' => [2, 5]]],
        ['level_min' => 16, 'level_max' => 20, 'zones' => ['legs' => [3, 6], 'belt' => [3, 6]]],
    ],
    'boots' => [
        ['level_min' => 1, 'level_max' => 5, 'zones' => ['legs' => [1, 3]]],
        ['level_min' => 6, 'level_max' => 10, 'zones' => ['legs' => [1, 3]]],
        ['level_min' => 11, 'level_max' => 15, 'zones' => ['legs' => [2, 5]]],
        ['level_min' => 16, 'level_max' => 20, 'zones' => ['legs' => [3, 7]]],
    ],
];
