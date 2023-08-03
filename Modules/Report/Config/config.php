<?php

return [
    'name' => 'Report',

    'chart_types' => ['bar', 'card', 'line', 'pie', 'table'],

    'models' => [
        'Detection' => [
            'reports' => [
                'total', 'actions', 'types', 'status'
            ]
        ],
        'Drone' => [
            'reports' => []
        ]

    ],

    'builder' => [
        'models' => [
            [
                'key'   => 'Detection',
                'value' => 'Detection',
            ], [
                'key'   => 'Drone',
                'value' => 'drones',
            ]
        ],
        'report_types' => [
            [
                'key'   => 'total',
                'value' => 'Total'
            ],
            [
                'key'   => 'actions',
                'value' => 'Actions'
            ],
            [
                'key'   => 'types',
                'value' => 'Detection Types'
            ],
            [
                'key'   => 'status',
                'value' => 'Detection Status'
            ],
        ],
        'show_types' => [
            [
                'key'   => 'specific',
                'value' => 'Specific',
            ], [
                'key'   => 'comparison',
                'value' => 'Comparison'
            ]
        ],
        'time_types' => [
            [
                'key'   => 'specific',
                'value' => 'Specific'
            ], [
                'key'   => 'dynamic',
                'value' => 'Dynamic',
                'dates' => [
                    [
                        'key'   => 'today',
                        'value' => 'Today'
                    ], [
                        'key'   => '14',
                        'value' => 'This Week'
                    ], [
                        'key'   => '13',
                        'value' => 'Last Week'
                    ], [
                        'key'   => '16',
                        'value' => 'This Month'
                    ], [
                        'key'   => '15',
                        'value' => 'Last Month'
                    ]
                ]
            ]
        ]
    ],

    'report' => [
        'total' => [
            'data' => ['count'],
            'unit' => 'number',
            'className' => 'TotalReport'
        ],
        'status' => [
            'data' => ['pending', 'error', 'confirmed'],
            'unit' => 'number',
            'className' => 'DetectionStatusReport'
        ],
        'actions' => [
            'data' => ['notice', 'not_notice'],
            'unit' => 'number',
            'className' => 'ActionReport'
        ],
        'types' => [
            'data' => ['people', 'vehicles', 'leakage', 'fire', 'smoke', 'change'],
            'unit' => 'number',
            'className' => 'DetectionTypeReport'
        ],
    ],
];
