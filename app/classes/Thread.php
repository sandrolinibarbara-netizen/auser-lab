<?php

class Thread extends BaseModel {
    private $thread;
    private $post;
    public function __construct($id) {
        parent::__construct();
        $this->table = THREADS;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function get($message = false) {
        $thread = $this->id;

        if($message) {
            $recipient =  "SELECT conversazioni.id as conversazione, utenti.nome, utenti.cognome, utenti.immagine, utenti.id as interlocutore FROM conversazioni 
                            LEFT JOIN utenti ON (utenti.id = conversazioni.id_utente_2 OR utenti.id = conversazioni.id_utente_1) WHERE conversazioni.id = '$thread'";
            $dataRecipient = $this->db->query($recipient)->fetchAll(PDO::FETCH_ASSOC);

            foreach($dataRecipient as $key => $row) {
                if($dataRecipient[$key]['interlocutore'] == $_SESSION[SESSIONROOT]['user']) {
                    unset($dataRecipient[$key]);
                }
            }

            $dataRecipient = array_values($dataRecipient);

            $query = "SELECT messaggi.testo, messaggi.system_date_modified as data_modifica, gruppi.nome as ruolo, gruppi.icona, utenti.nome, utenti.cognome, utenti.immagine, utenti.id FROM messaggi 
                    JOIN utenti ON messaggi.id_mittente = utenti.id
                    JOIN utenti_gruppi ON utenti.id = utenti_gruppi.id_utente
                    JOIN gruppi ON gruppi.id = utenti_gruppi.id_gruppo
                    JOIN conversazioni ON conversazioni.id = messaggi.id_conversazione WHERE messaggi.id_conversazione = '$thread' ORDER BY messaggi.system_date_modified ASC;";
            $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        } else {
            $query = "SELECT posts.id, posts.testo, posts.system_date_modified as data_modifica, gruppi.nome as ruolo, gruppi.icona, utenti.nome, utenti.cognome, utenti.immagine, thread.titolo, thread.descrizione, corsi.risposte_studenti FROM posts 
                    JOIN utenti ON posts.id_utente_autore = utenti.id
                    JOIN utenti_gruppi ON utenti.id = utenti_gruppi.id_utente
                    JOIN gruppi ON gruppi.id = utenti_gruppi.id_gruppo
                    JOIN thread ON thread.id = $thread
                    JOIN corsi ON corsi.id = thread.id_corso
                    WHERE posts.id_thread = thread.id ORDER BY posts.system_date_modified ASC";
            $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach ($data as $key => $value) {
            $data[$key]['orario_modifica'] = formatTime($data[$key]['data_modifica']);
            $data[$key]['data_modifica'] = formatDate($data[$key]['data_modifica']);
            $data[$key]['thread'] = $thread;

            if($message) {
                $data[0]['type'] = 'message';
                $data[0]['titolo'] = 'Conversazione con ' . $dataRecipient[0]['nome'] . ' ' . $dataRecipient[0]['cognome'];
                $data[0]['descrizione'] = '';
                $data[0]['id_talker'] = $dataRecipient[0]['interlocutore'];
            } else {
                $data[0]['type'] = 'post';
            }
        }

        $this->thread = $data;
        return $this->thread;
    }
    public function createPost($postContent) {
        $course = $this->id_corso;
        $thread = $this->id;
        $post = $postContent;
        $user = $_SESSION[SESSIONROOT]['user'];

        $this->db->insert('posts', [
            'testo' => $post,
            'id_utente_autore' => $user,
            'id_corso' => $course,
            'id_thread' => $thread,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $parsed = array();
        $parsed['username'] = $user;
        $this->post = $parsed;
        return $this->post;
    }
    public function createMessage($postRecipient, $postContent) {
        $thread = $this->id;
        $recipient = $postRecipient;
        $post = $postContent;
        $user = $_SESSION[SESSIONROOT]['user'];

        $this->db->insert('messaggi', [
            'testo' => $post,
            'id_mittente' => $user,
            'id_destinatario' => $recipient,
            'id_conversazione' => $thread,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $parsed = array();
        $parsed['user'] = $user;
        $this->post = $parsed;
        return $this->post;
    }
    public function deletePost($idPost) {
        $this->db->delete('posts', ['id' => $idPost]);
    }
    public function deleteThread() {
        $id = $this->id;
        $this->db->delete('thread', ['id' => $id]);
        $this->db->delete('posts', ['id_thread' => $id]);
    }
}