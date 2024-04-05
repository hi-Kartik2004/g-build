<?php

use Minishlink\WebPush\WebPush;

$endpoint = 'https://fcm.googleapis.com/fcm/send/abcdef...'; // Chrome

$auth = [
    'VAPID' => [
        'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
    ],
];

$webPush = new WebPush($auth);
$webPush->queueNotification(...);
