<?php

if(isset($_GET['update']) && $_GET['update'] == 'category'){
    require_once '../config/config_inc.php';
    $category = new Category($_GET['id']);
    $parsed = $category->get();
    loadView('categories', ['parsed' => $parsed], '/update-category.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteCategory') {
    require_once '../config/config_inc.php';
    $category = new Category($_POST['id']);
    $category->delete();
}

if(isset($_POST['action']) && $_POST['action'] === 'updateCategory') {
    require_once '../config/config_inc.php';
    $data = [
        'category' => $_POST['nome'] === "" ?  null : $_POST['nome'],
        'color' => $_POST['colore'] === "" ?  null : $_POST['colore'],
    ];

    if($_FILES['pic']['tmp_name']) {
        $data['tmpName'] = $_FILES['pic']['tmp_name'];
        $data['fileName'] = $_FILES['pic']['name'];
    }

    $category = new Category($_POST['idCategory']);
    $category->update($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAssociatedCategories') {
    require_once '../config/config_inc.php';

    $category = new Category($_POST['id']);
    $result = $category->getAssociatedEvents();

    echo json_encode($result);
}