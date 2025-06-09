<?php
require_once "BaseModel.php";
foreach (glob(__DIR__ . "/*.php") as $filename) {
    require_once $filename;
}