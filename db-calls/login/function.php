<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST)) {

        if ($_POST["action"] && $_POST["action"] != '') {

            switch ($_POST["action"]) {

                case 'login':
                    login($_POST['username'], $_POST['password']);
                    break;
            }
        }
    }
}

function login($username, $password) {

    if($username && $password) {
        if (Login::in($username, $password)) {

            $user = getUser();
            $groups = $user->getGroups();

            $path = 'app/modules/dashboard/index.php';
            for($i=0; $i<count($groups); $i++) {
                $groupId = $groups[$i];
                $group = new Group($groups[$i]);
                $pages  = $group->get_pages();
                $index = array_search(6, $pages);
                $page = new Page($pages[$index]);
                $path = $page->path;
                $query  = $group->get_pages_info();
                break;
            }
            $_SESSION[SESSIONROOT]['user'] = $user->id;
            $_SESSION[SESSIONROOT]['pages'] = $query;
            $_SESSION[SESSIONROOT]['group'] = $groupId;
            $return_data = array('return' => true, 'path' => $path, 'userId' => $user->id, 'session' => $_SESSION[SESSIONROOT]["active"]);

        } else {
            $return_data = array('return' => false, 'path' => '');
        }
    }
    echo json_encode($return_data);
}