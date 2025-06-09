<?php

if(isset($_POST['action']) && $_POST['action'] === 'deleteSection') {
    require_once '../config/config_inc.php';
    $section = new Section($_POST['idSection'], $_POST['type']);
    $section->deleteSection();
}

if(isset($_POST['action']) && $_POST['action'] === 'updateSection') {
    require_once '../config/config_inc.php';
    if($_POST['type'] == 'lecture'){

        $data = [
            'numeroSezione' => $_POST['numeroSezione'],
            'titolo' => $_POST['titolo'],
            'descrizione' => $_POST['descrizione'] === "" ? null : $_POST['descrizione'],
        ];

        if($_FILES['file']['tmp_name']) {
            $data['tmpName'] = $_FILES['file']['tmp_name'];
            $data['fileName'] = $_FILES['file']['name'];
        }

        $section = new Section($_POST['idSection'], $_POST['type']);
        $result = $section->updateSection($data);
        echo json_encode($result);

    } else if($_POST['type'] == 'poll') {

        $data = [

            'numeroDomanda' => $_POST['numeroDomanda'],
            'tipologia' => $_POST['tipologia'],
            'titolo' => $_POST['titolo'],
            'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
            'punti' => $_POST['punti'] ?? null,
            'obbligatoria' => $_POST['obbligatoria'] ?? null,
            'risposte' => json_decode($_POST['risposte'], true) ?? null,
            'maxCaratteri' => $_POST['maxCaratteri'] ?? null,
            'minCaratteri' => $_POST['minCaratteri'] ?? null,
            'link' => $_POST['link'] ?? null,
        ];

        if($_FILES['file']['tmp_name']) {
            $data['tmpName'] = $_FILES['file']['tmp_name'];
            $data['fileName'] = $_FILES['file']['name'];
        }

        $poll = new Section($_POST['idSection'], $_POST['type']);
        $result = $poll->updateSection($data);
        echo json_encode($result);

    } else {

        $data = [

            'numeroDomanda' => $_POST['numeroDomanda'],
            'titolo' => $_POST['titolo'],
            'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        ];

        $survey = new Section($_POST['idSection'], $_POST['type']);
        $result = $survey->updateSection($data);
        echo json_encode($result);
    }
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteAnswer') {
    require_once '../config/config_inc.php';
    $idAnswer = $_POST['idAnswer'];
    $section = new Section($_POST['idSection'], 'poll');
    $result = $section->deleteAnswer($idAnswer);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'addSectionChoice') {
    require_once '../config/config_inc.php';
    $section = new Section($_POST['idSection'], 'poll');
    $result = $section->addAnswer();
    echo json_encode($result);
}
