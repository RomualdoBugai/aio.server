<?php
return [
    'verify'    => [
        'token' => md5('william'),
        'app'   => 'logfiscal',
        'lang'  => 'en',
    ],
    'server' => (object) [
        'hostname'     => 'http://localhost/aio.master/public/index/api/logfiscal/',
        'port'         => 8800
    ]
];
