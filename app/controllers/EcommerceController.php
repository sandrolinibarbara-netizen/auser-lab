<?php

if(isset($_GET['show']) && $_GET['show'] == 'ecommerce'){
    require_once __DIR__.'/../../vendor/autoload.php';
    $cart = new Ecommerce();
    $cart->removeTemp();
    loadView('ecommerce');
}

if(isset($_GET['show']) && $_GET['show'] == 'ondemand'){
    require_once __DIR__.'/../../vendor/autoload.php';
    $cart = new Ecommerce();
    $cart->removeTemp();
    loadView('ecommerce', [], '/corsi-ondemand.php');
}

if(isset($_GET['shop']) && $_GET['shop'] == 'course'){
    require_once __DIR__.'/../../vendor/autoload.php';
    session_start();
    if (isset($_SESSION[SESSIONROOT]['timer']) && $_SESSION[SESSIONROOT]['timer'] < time()) {
        $cart = new Ecommerce();
        foreach($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']] as $item) {
            $cart->removeFromCart($item);
        }
        unset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]);
        unset($_SESSION[SESSIONROOT]['timer']);
    }
    $course = new Course($_GET['id']);
    $data = $course->getEcommVersion();
    loadView('show', ['data' => $data], '/single-course.php');
}

if(isset($_GET['transaction']) && $_GET['transaction'] == 'confirmed'){
    require_once '../config/config_inc.php';
    $user = new User($_SESSION[SESSIONROOT]['user']);
    $user->emptyAndSubscribe(1);
    header("Location: " . ROOT . "success");
}

if(isset($_GET['bank-transfer']) && $_GET['bank-transfer'] == 'confirmed'){
    require_once '../config/config_inc.php';
    $user = new User($_SESSION[SESSIONROOT]['user']);
    $user->emptyAndSubscribe(2);
    header("Location: " . ROOT . "success");
}

if(isset($_GET['cart'])){
    require_once '../config/config_inc.php';
    session_start();
    if (isset($_SESSION[SESSIONROOT]['timer']) && $_SESSION[SESSIONROOT]['timer'] < time()) {
        $cart = new Ecommerce();
        foreach($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']] as $item) {
            $cart->removeFromCart($item);
        }
        unset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]);
        unset($_SESSION[SESSIONROOT]['timer']);
    }
    $course = new Ecommerce();
    $data = $course->getCart($_GET['cart']);
    loadView('stripe', ['data' => $data], '/cart-checkout.php');
}

if(isset($_GET['shop']) && $_GET['shop'] === 'event') {
    require_once __DIR__.'/../../vendor/autoload.php';
    session_start();
    if (isset($_SESSION[SESSIONROOT]['timer']) && $_SESSION[SESSIONROOT]['timer'] < time()) {
        $cart = new Ecommerce();
        foreach($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']] as $item) {
            $cart->removeFromCart($item);
        }
        unset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]);
        unset($_SESSION[SESSIONROOT]['timer']);
    }
    $event = new Lesson($_GET['id']);
    $data = $event->getEcommVersion();
    loadView('show', ['data' => $data], '/single-event.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'getSearch') {
    require_once __DIR__.'/../../vendor/autoload.php';
    session_start();
    if (isset($_SESSION[SESSIONROOT]['timer']) && $_SESSION[SESSIONROOT]['timer'] < time()) {
        $cart = new Ecommerce();
        foreach($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']] as $item) {
            $cart->removeFromCart($item);
        }
        unset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]);
        unset($_SESSION[SESSIONROOT]['timer']);
    }
    $products = new Ecommerce();
    $param = $_POST['param'] ?? "";
    $result = $products->getProducts($param);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getOnDemand') {
    require_once __DIR__.'/../../vendor/autoload.php';
    session_start();
    if (isset($_SESSION[SESSIONROOT]['timer']) && $_SESSION[SESSIONROOT]['timer'] < time()) {
        $cart = new Ecommerce();
        foreach($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']] as $item) {
            $cart->removeFromCart($item);
        }
        unset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]);
        unset($_SESSION[SESSIONROOT]['timer']);
    }
    $products = new Ecommerce();
    $param = $_POST['param'] ?? "";
    $result = $products->getOnDemand($param);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'registerFreeItem') {
    require_once '../config/config_inc.php';
    $item = new Ecommerce();
    $item->registerFreeItem($_POST['id']);
}

if(isset($_POST['action']) && $_POST['action'] === 'addToCart') {
    require_once '../config/config_inc.php';
    $cart = new Ecommerce();
    $result = $cart->addToCart($_POST['id']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'removeFromCart') {
    require_once '../config/config_inc.php';
    $cart = new Ecommerce();
    $result = $cart->removeFromCart($_POST['id']);
    echo json_encode($result);
}