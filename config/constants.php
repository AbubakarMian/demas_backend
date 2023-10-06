<?php

return [

	'status' => [
		'OK' => 200
	],

	'app-type' => [
		'android' => "demas-app-mobile",
	],
    'driver'=>[
        'commission_types'=>[
            'monthly'=>'Monthly',
            'per_trip'=>'Per Trip',
        ]
    ],
    'sales_agent'=>[
        'commission_types'=>[
            'fix_amount'=>'Fix Amount',
            'profit_percent'=>'Profit Percent',
            'sales_percent'=>'Sales Percent',
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

    'payment_status'=>[
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
