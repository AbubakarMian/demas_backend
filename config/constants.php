<?php

return [

	'status' => [
		'OK' => 200
	],

	'app-type' => [
		'android' => "demas-app-mobile",
	],
	'settings' => [
		'discount' => "discount",
	],
    'driver'=>[
        'commission_types'=>[
            'monthly'=>'Monthly',
            'per_trip'=>'Per Trip',
        ],
        'commission_types_keys'=>[
            'monthly'=>'monthly',
            'per_trip'=>'per_trip',
        ],
        'categories'=>[
            'own'=>'Own',
            'out_source'=>'Out Source',
        ],
        'categories_keys'=>[
            'own'=>'own',
            'out_source'=>'out_source',
        ]
    ],
    'sales_agent'=>[
        'commission_types'=>[
            'fix_amount'=>'Fix Amount',
            'profit_percent'=>'Profit Percent',
            'sales_percent'=>'Sales Percent',
        ]
    ],
    'travel_agent'=>[
        'commission_types'=>[
            'per_trip'=>'per_trip',
        ],
        'commission_types_keys'=>[
            'monthly'=>'monthly',
            'per_trip'=>'per_trip',
        ]
    ],
    'order_status'=>[
        'pending'=>'pending',
        'paid'=>'paid',
        'in_progress'=>'in_progress',
        'completed'=>'completed',
        'cancelled'=>'cancelled',
    ],
	'social_login' => [
		'facebook'=>'facebook',
		'twitter'=>'twitter',
		'gmail'=>'gmail',
	],
    'sender' =>[
        'user'=>'user',
        'sholar'=>'scholar'
    ],
    'payment_type'=>[
        'cod'=>'cod',
        'card'=>'card',
        'advance_collection'=>'advance_collection',
    ],
    'payment_status'=>[
        'pending'=>'pending',
        'paid'=>'paid',
        'refunded'=>'refunded',
    ],

    'ajax_action'=>[
        'create'=>'create',
        'update'=>'update',
        'delete'=>'delete',
        'error'=>'error',
        'success'=>'success',
    ],
];
