<?php

    require_once __DIR__.'/../../vendor/autoload.php';

    session_start();

    if(isset($_SESSION[SESSIONROOT]["active"]) && ($_SESSION[SESSIONROOT]["active"] === 1)) {

        $database = new Database();

        if(!$database->is_session_valid($_SESSION[SESSIONROOT]["user_token"])) {

            session_destroy();

            if(isset($_GET['live']) && $_GET['live'] == 'fill-poll') {
                header("Location: " . ROOT . "login?live=fill-poll&id=" . $_GET['id']);
            } else {
                header("Location: " . ROOT . "login");
            }
        }

        if($_SERVER["PHP_SELF"] == ROOT . "index.php") {

            session_destroy();

            if(isset($_GET['live']) && $_GET['live'] == 'fill-poll') {
                header("Location: " . ROOT . "login?live=fill-poll&id=" . $_GET['id']);
            } else {
                header("Location: " . ROOT . "login");
            }
        }

    } else {

        session_destroy();

        if(isset($_GET['live']) && $_GET['live'] == 'fill-poll') {
            header("Location: " . ROOT . "login?live=fill-poll&id=" . $_GET['id']);
        } else {
            header("Location: " . ROOT . "login");
        }
    }