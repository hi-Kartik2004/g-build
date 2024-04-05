<?php

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Example client-side PushSubscription JSON
$clientSidePushSubscriptionJSON = '{
    "endpoint": "https://example.com/push/endpoint",
    "keys": {
        "p256dh": "BNNvPdKtc3y1GzE-mSNi4r6x6EYqD7UySvxk-ivEGfN0Pw8d9xXZAK8wqsgu5AdtMlQ5Y74tulBFca4_KW-YTgg",
        "auth": "w4Ezjwrhgc_m2gEaS5hsig"
    }
}';

// Store the client-side `PushSubscription` object (calling `.toJSON` on it) as-is and then create a WebPush\Subscription from it
$subscription = Subscription::create(json_decode($clientSidePushSubscriptionJSON, true));

// Array of notifications
$notifications = [
    [
        'subscription' => $subscription,
        'payload' => '{"message":"Hello World!"}',
    ], [
        // Example current PushSubscription format (browsers might change this in the future)
        'subscription' => Subscription::create([
            "endpoint" => "https://example.com/other/endpoint/of/another/vendor/abcdef...",
            "keys" => [
                'p256dh' => '(stringOf88Chars)',
                'auth' => '(stringOf24Chars)'
            ],
        ]),
        'payload' => '{"message":"Hello World!"}',
    ], [
        // Example old Firefox PushSubscription format
        'subscription' => Subscription::create([
            'endpoint' => 'https://updates.push.services.mozilla.com/push/abc...', // Firefox 43+,
            'publicKey' => 'BPcMbnWQL5GOYX/5LKZXT6sLmHiMsJSiEvIFvfcDvX7IZ9qqtq68onpTPEYmyxSQNiH7UD/98AUcQ12kBoxz/0s=', // base 64 encoded, should be 88 chars
            'authToken' => 'CxVX6QsVToEGEcjfYPqXQw==', // base 64 encoded, should be 24 chars
        ]),
        'payload' => 'hello !',
    ], [
        // Example old Chrome PushSubscription format
        'subscription' => Subscription::create([
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/abcdef...',
        ]),
        'payload' => null,
    ], [
        // Example old PushSubscription format
        'subscription' => Subscription::create([
            'endpoint' => 'https://example.com/other/endpoint/of/another/vendor/abcdef...',
            'publicKey' => '(stringOf88Chars)',
            'authToken' => '(stringOf24Chars)',
            'contentEncoding' => 'aesgcm', // one of PushManager.supportedContentEncodings
        ]),
        'payload' => '{"message":"test"}',
    ]
];

$webPush = new WebPush();

// Send multiple notifications with payload
foreach ($notifications as $notification) {
    $webPush->queueNotification(
        $notification['subscription'],
        $notification['payload'] // optional (defaults null)
    );
}

// Check sent results
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();

    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.<br>";
    } else {
        echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}<br>";
    }
}

// Send one notification and flush directly
$report = $webPush->sendOneNotification(
    $notifications[0]['subscription'],
    $notifications[0]['payload'] // optional (defaults null)
);
