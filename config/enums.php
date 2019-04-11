<?php

return [

    'jobs' => [
        'statuses' => [
            'OPEN'        => 'open',
            'PRIVATE'     => 'private',            
            'CLOSED'      => 'closed',
            'DRAFT'       => 'draft',
            'COMPLETE'    => 'complete',
            'DECLINING'   => 'declining',
            'DECLINED'    => 'declined',
            'IN_PROGRESS' => 'in progress',
            'IN_REVIEW'   => 'in review',
        ]
    ],
    'applications' => [
        'statuses'        => [
            'OPEN'        => 'open',
            'PRIVATE'     => 'private',
            'CLOSED'      => 'closed',
            'DRAFT'       => 'draft',
            'COMPLETE'    => 'complete',
            'IN_PROGRESS' => 'in progress',
        ]
    ],
    'account' => [
        'specialities' => [
            'designer',
            'programmer',
            'courier',
        ]
    ],
    'days' => [
        'MONDAY' => '1',
        'TUESDAY' => '2',
        'WEDNESDAY' => '3',
        'THURSDAY' => '4',
        'FRIDAY' => '5',
        'SATURDAY' => '6',
        'SUNDAY' => '0',
    ]
];
