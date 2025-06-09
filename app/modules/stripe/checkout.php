<?php
require_once '../../config/config_inc.php';
require_once './secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$itemsObj = new Ecommerce();
$items = $itemsObj->getCart($_SESSION[SESSIONROOT]['user']);

$checkoutCart = array();

foreach ($items['courses'] as $item) {
    $checkoutCart[] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => $item['corso'],
            ],
            'unit_amount' => $item['importo'] * 100,
        ],
        'quantity' => 1,
    ];
}

foreach ($items['events'] as $item) {
    $checkoutCart[] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => $item['diretta'],
            ],
            'unit_amount' => $item['importo'] * 100,
        ],
        'quantity' => 1,
    ];
}

$checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => $checkoutCart,
    'mode' => 'payment',
    'success_url' => $_SERVER["HTTP_ORIGIN"] . ROOT . 'success/empty-and-subscribe?transaction=confirmed',
    'cancel_url' => $_SERVER["HTTP_ORIGIN"] . ROOT . 'checkout?cart=' . $_SESSION[SESSIONROOT]['user'],
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);