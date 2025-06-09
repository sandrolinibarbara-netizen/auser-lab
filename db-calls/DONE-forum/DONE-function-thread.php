<?php
$db = new Database();

$thread = $_GET["id"];


$totalPages = "SELECT count(posts.id_thread) AS total FROM posts WHERE posts.id_thread = '$thread'";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$query = "SELECT posts.testo, posts.system_date_modified as data_modifica, gruppi.nome as ruolo, gruppi.icona, utenti.nome, utenti.cognome, utenti.immagine, thread.titolo, thread.descrizione FROM posts 
JOIN utenti ON posts.id_utente_autore = utenti.id
JOIN utenti_gruppi ON utenti.id = utenti_gruppi.id_utente
JOIN gruppi ON gruppi.id = utenti_gruppi.id_gruppo
JOIN thread ON thread.id = $thread WHERE posts.id_thread = thread.id ORDER BY posts.system_date_modified ASC;";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['orario_modifica'] = formatTime($data[$key]['data_modifica']);
    $data[$key]['data_modifica'] = formatDate($data[$key]['data_modifica']);
}
