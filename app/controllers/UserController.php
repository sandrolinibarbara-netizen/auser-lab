<?php

if(isset($_GET['logout']) && $_GET['logout'] == 'confirmed') {
    require_once '../config/config_inc.php';
    $user = new Login();
    $user->out();
    loadView('login', [], '/logout.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'getCertificates') {
    require_once '../config/config_inc.php';
    $certificates = new User($_SESSION[SESSIONROOT]['user']);
    $result = $certificates->getCertificates($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_GET['token'])) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $dbObj = new Database();
    $linkValid = $dbObj->is_link_valid($_GET['token']);
    if($linkValid) {
        header("Location: " . ROOT . "sub-approval/confirmed");
    } else {
        header("Location: " . ROOT . "sub-approval/rejected");
    }
}

if(isset($_GET['proof'])) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $dbObj = new Database();
    $linkValid = $dbObj->is_link_valid($_GET['proof']);
    if($linkValid) {
        header("Location: " . ROOT . "modifica-password/confirmed?id=".$linkValid);
    } else {
        header("Location: " . ROOT . "modifica-password/rejected");
    }
}

if(isset($_POST['action']) && $_POST['action'] === 'updateCertificate') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['idUser']);
    $user->updateCertificate($_POST['idCourse']);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAvailableCourses') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['idUser']);
    $result = $user->getAvailableCourses(true, true, $_POST['class']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'moveUser') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['idUser']);
    $user->moveUser($_POST['idCourseRemoved'], $_POST['idCourseSelected']);
}

if(isset($_POST['action']) && $_POST['action'] === 'removeUserCourse') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['idUser']);
    $user->removeUser($_POST['idCourse']);
}

if(isset($_POST['action']) && $_POST['action'] === 'sendUserMessage') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['idUser']);
    $user->sendMessage($_POST['message']);
}

if(isset($_POST['action']) && $_POST['action'] === 'getCourses') {
    require_once '../config/config_inc.php';
    $courses = new User($_SESSION[SESSIONROOT]['user']);
    $result = $courses->getCourses($_POST['start'], $_POST['draw'], $_POST['length'], $_POST['courseCreation'], $_POST['courseStart'], $_POST['courseEnd'], $_POST['courseTeacher']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getSingleCourse') {
    require_once '../config/config_inc.php';
    $course = new User($_POST['idUser']);
    $result = $course->getSingleCourse($_POST['idCourse']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftCourses') {
    require_once '../config/config_inc.php';
    $drafts = new User($_SESSION[SESSIONROOT]['user']);
    $result = $drafts->getDrafts();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getRegisters') {
    require_once '../config/config_inc.php';
    $registers = new User($_SESSION[SESSIONROOT]['user']);
    $result = $registers->getRegisters($_POST['start'], $_POST['draw'], $_POST['length'], $_POST['allRegCreation'], $_POST['allRegStart'], $_POST['allRegEnd']);
    echo json_encode($result);
}

if(isset($_GET['user']) && $_GET['user'] == 'profile') {
    require_once '../config/config_inc.php';
    $profile = new User($_SESSION[SESSIONROOT]['user']);
    $data = $profile->getUserData();
    if($_GET['update'] == 1) {
        loadView('profile', ['data' => $data], '/update-profile.php');
    } else {
        loadView('profile', ['data' => $data]);
    }
}

if(isset($_GET['utente']) && $_GET['utente'] == 'infos') {
    require_once '../config/config_inc.php';
    $profile = new User($_GET['id']);
    if($_GET['update'] == 1) {
        $data = $profile->getUserData();
        loadView('profile', ['data' => $data], '/update-profile.php');
    } else {
        $data = $profile->getUserData('full');
        loadView('users', ['data' => $data], '/single-user.php');
    }
}

if(isset($_POST['action']) && $_POST['action'] === 'getPayments') {
    require_once '../config/config_inc.php';
    $payments = new User($_POST['user']);
    $result = $payments->getPayments($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getOldPayments') {
    require_once '../config/config_inc.php';
    $payments = new User($_POST['user']);
    $result = $payments->getOldPayments($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getOldSubs') {
    require_once '../config/config_inc.php';
    $subs = new User($_POST['user']);
    $result = $subs->getOldSubs($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateRegister') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['user']);
    $user->updateRegister($_POST['lesson'], $_POST['value']);
}

if(isset($_POST['action']) && $_POST['action'] === 'addUserCourse') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['user']);
    $user->updateCourses($_POST['selected'], 2 , false, true);
}

if(isset($_POST['action']) && $_POST['action'] === 'updatePayments') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['user']);
    $user->updatePayments($_POST['payOpts']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateSub') {
    require_once '../config/config_inc.php';
    $user = new User($_POST['user']);
    $user->updateSub($_POST['idTesseramento'], $_POST['value'], $_POST['year']);
}

if(isset($_POST['action']) && $_POST['action'] === 'getOtherUsers') {
    require_once '../config/config_inc.php';
    $others = new User($_SESSION[SESSIONROOT]['user']);
    $result = $others->getOtherUsers();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAllHomeworks') {
    require_once '../config/config_inc.php';
    $homeworks = new User($_SESSION[SESSIONROOT]['user']);
    $result = $homeworks->getAllHomeworks($_POST['lessonName'], $_POST['courseName'], $_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'uploadPrivacy') {
    require_once '../config/config_inc.php';

    $data = [
        'tmpName' => $_FILES['file']['tmp_name'],
        'fileName' => $_FILES['file']['name'],
        'year' => $_POST['year']
    ];

    $user = new User($_POST['user']);
    $user->uploadPrivacy($data);

}

if(isset($_POST['action']) && $_POST['action'] === 'updateUser') {
    require_once '../config/config_inc.php';
    $data = [

        'cognome' => $_POST['surname'] === "" ?  null : $_POST['surname'],
        'nome' => $_POST['user'] === "" ?  null : $_POST['user'],
        'dataNascita' => $_POST['date'] === "" ?  null : $_POST['date'],
        'indirizzo' => $_POST['address'] === "" ?  null : $_POST['address'],
        'telefono' => $_POST['phone'] === "" ?  null : $_POST['phone'],
        'email' => $_POST['email'] === "" ?  null : $_POST['email'],
        'underage' => $_POST['underage'] === "" ?  null : $_POST['underage'],
        'job' => $_POST['job'] === "" ?  null : $_POST['job'],
    ];

    if($_FILES['pic']['tmp_name']) {
        $data['tmpName'] = $_FILES['pic']['tmp_name'];
        $data['fileName'] = $_FILES['pic']['name'];
    }

    $user = new User($_POST['id']);
    $result = $user->updateUser($data);

    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'changePassword') {
    require_once __DIR__ . '/../../vendor/autoload.php';

    $user = new User(decryptStr($_POST['id']));
    $user->changePassword($_POST['password']);

}

if(isset($_POST['action']) && $_POST['action'] === 'getAssociatedTeachers') {
    require_once '../config/config_inc.php';

    $teacher = new User($_POST['id']);
    $result = $teacher->getAssociatedEvents();

    echo json_encode($result);
}

