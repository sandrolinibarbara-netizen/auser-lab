<?php
    class Login {

        private static $database;

        public static function in($username, $password) {

            self::$database = new Database();

            if(self::$database->login($username, $password)) {

                $_SESSION[SESSIONROOT]["active"] = 1;
                return true;

            } else {

                session_destroy();
                return false;
            }
        }
        public static function out() {
            session_destroy();
        }
    }
?>