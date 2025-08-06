<?php

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\Control\V2\Client\StorageControlClient;
use Google\Cloud\Storage\Control\V2\CreateFolderRequest;
use Google\Cloud\Storage\Control\V2\GetFolderRequest;
use Google\Cloud\Storage\WriteStream;

class Storage extends BaseModel
{
    private $uploadedVideo;

function upload_object(string $fileName, int $chunkIndex, int $totalChunks, string $id) {

// --- Configurazione ---

// Directory temporanea dove verranno salvati i chunk dei file.
// Assicurati che questa directory abbia i permessi di scrittura (es. 0777, ma con cautela in produzione).
    $temp_dir = 'temp_chunks/';

// --- Inizializzazione ---
// Crea la directory temporanea se non esiste.
    if (!is_dir($temp_dir)) {
        mkdir($temp_dir, 0777, true); // 0777 per permessi completi, da rivedere in produzione
    }

// Imposta l'intestazione per le risposte JSON
    header('Content-Type: application/json');

// --- Validazione della Richiesta ---
// Verifica che la richiesta sia di tipo POST.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Metodo non consentito
        echo json_encode(['error' => 'Method Not Allowed']);
        exit;
    }

// Verifica che tutti i parametri necessari e il file chunk siano presenti.
    if (empty($fileName) || !isset($chunkIndex) || !isset($totalChunks) || empty($_FILES['chunk'])) {
        http_response_code(400); // Richiesta malformata
        echo json_encode(['error' => 'Invalid request data. Missing fileName, chunkIndex, totalChunks, or chunk file.']);
        exit;
    }

// Informazioni sul chunk caricato
    $chunkFile = $_FILES['chunk']['tmp_name']; // Percorso temporaneo del chunk sul server
    $chunkFilePath = $temp_dir . $fileName . '.part' . $chunkIndex; // Percorso dove salveremo il chunk

// --- Gestione del Chunk ---
// Sposta il chunk caricato dalla directory temporanea di PHP alla nostra directory temporanea.
    if (!move_uploaded_file($chunkFile, $chunkFilePath)) {
        http_response_code(500); // Errore interno del server
        echo json_encode(['error' => 'Failed to save chunk to temporary directory.']);
        exit;
    }

// --- Controllo Completamento File ---
// Controlla se tutti i chunk per questo file sono stati caricati.
    $allChunksUploaded = true;
    for ($i = 0; $i < $totalChunks; $i++) {
        // Se anche un solo chunk manca, il file non è completo.
        if (!file_exists($temp_dir . $fileName . '.part' . $i)) {
            $allChunksUploaded = false;
            break;
        }
    }

// --- Unione e Caricamento su GCS (se tutti i chunk sono presenti) ---
    if ($allChunksUploaded) {
        $tempFilePath = $temp_dir . $fileName; // Percorso del file completo unito

        // Apri un handle per scrivere il file unito in modalità binaria.
        $fileHandle = fopen($tempFilePath, 'wb');

        if ($fileHandle === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create final file for merging chunks.']);
            exit;
        }

        // Unisci tutti i chunk in un unico file.
        for ($i = 0; $i < $totalChunks; $i++) {
            $currentChunkPath = $temp_dir . $fileName . '.part' . $i;
            $chunkContent = file_get_contents($currentChunkPath); // Leggi il contenuto del chunk
            fwrite($fileHandle, $chunkContent); // Scrivi nel file unito
        }
        fclose($fileHandle); // Chiudi il file unito

        // --- Caricamento su Google Cloud Storage ---
        try {
            $this->get_folder($id);

            $config = ['projectId' => 'auser-prova', 'keyFilePath' => UPLOADDIR.'app/constants/auser-prova-681b1e691b41.json'];
            $storage = new StorageClient($config);
            $bucket = $storage->bucket('auser-zoom-meetings');

            // Carica il file unito nel bucket.
            // Puoi specificare un percorso all'interno del bucket (es. 'videos/' . $fileName).
            $object = $bucket->upload(fopen($tempFilePath, 'r'), [
            'name' => $id.'/'.$fileName]);
            $user = $_SESSION[SESSIONROOT]['user'];
            $idLesson = $id;
            $this->db->update('dirette', [
                'path_video' => $fileName,
                'system_user_modified' => $user,
                ], ['id' => $idLesson]);

            // --- Pulizia ---
            // Elimina tutti i file temporanei (chunk e file unito) dopo l'upload su GCS.
            for ($i = 0; $i < $totalChunks; $i++) {
                unlink($temp_dir . $fileName . '.part' . $i); // Elimina i singoli chunk
            }
            unlink($tempFilePath); // Elimina il file unito

            // Risposta di successo al frontend
            echo json_encode(['success' => 'File uploaded successfully to Google Cloud Storage', 'gcsPath' => $object->name()]);

        } catch (Exception $e) {
            // Gestione degli errori durante l'upload su GCS
            http_response_code(500);
            echo json_encode(['error' => 'Google Cloud Storage upload failed: ' . $e->getMessage()]);
        }
    } else {
        // Se non tutti i chunk sono ancora arrivati, rispondi che il chunk corrente è stato salvato.
        echo json_encode(['success' => 'Chunk ' . $chunkIndex . ' saved successfully. Waiting for other chunks.']);
    }

}
    function delete_object(string $objectName, string $id)
    {
        $lesson = new Lesson($id);
        $lesson->deleteVideo();

        $config = ['projectId' => 'auser-prova', 'keyFilePath' => UPLOADDIR.'app/constants/auser-prova-681b1e691b41.json'];
        $storage = new StorageClient($config);

        $storage = new StorageClient($config);
        $bucket = $storage->bucket('auser-zoom-meetings');
        $object = $bucket->object($id.'/'.$objectName);
        $object->delete();
    }
    function get_folder(string $folderName)
    {
        $storageControlClient = new StorageControlClient();

        $formattedName = $storageControlClient->folderName('_', 'auser-zoom-meetings', $folderName);

        $request = new GetFolderRequest([
            'name' => $formattedName,
        ]);

        $result = array();
        try {
            $folder = $storageControlClient->getFolder($request);
            $result['folder'] = $folder->getName();
        } catch(Exception $e) {
            $result['folder'] = $this->create_folder($folderName);
        }

        return $result;
    }
    function create_folder(string $folderName)
    {
        $storageControlClient = new StorageControlClient();

        // Set project to "_" to signify global bucket
        $formattedName = $storageControlClient->bucketName('_', 'auser-zoom-meetings');

        $request = new CreateFolderRequest([
            'parent' => $formattedName,
            'folder_id' => $folderName,
        ]);

        try {
            $folder = $storageControlClient->createFolder($request);
            return $folder->getName();
        } catch(Exception $e) {
            printf('Error: %s\n', $e->getMessage());
        }
    }
}