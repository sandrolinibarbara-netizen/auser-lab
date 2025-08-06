<?php

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\Control\V2\Client\StorageControlClient;
use Google\Cloud\Storage\Control\V2\CreateFolderRequest;
use Google\Cloud\Storage\Control\V2\GetFolderRequest;
use Google\Cloud\Storage\WriteStream;

class Storage extends BaseModel
{
    private $uploadedVideo;
    function upload_object(string $objectName, string $contents, string $id)
    {
        $this->get_folder($id);

        $config = ['projectId' => 'auser-prova', 'keyFilePath' => UPLOADDIR.'app/constants/auser-prova-681b1e691b41.json'];
        $storage = new StorageClient($config);

//        if (!$file = fopen($contents, 'r')) {
//            throw new \InvalidArgumentException('Unable to open file for reading');
//        }

        $bucket = $storage->bucket('auser-zoom-meetings');

        $writeStream = new WriteStream(null, [
            'chunkSize' => 1024 * 256, // 256KB
        ]);
        $uploader = $bucket->getStreamableUploader($writeStream, [
            'name' => $id.'/'.$objectName,
        ]);
        $writeStream->setUploader($uploader);
        $result = array();
        $stream = fopen($contents, 'r');
        while (($line = stream_get_line($stream, 1024 * 256)) !== false) {
            $writeStream->write($line);
            $result[] = $line;
        }
        $writeStream->close();

//        $object = $bucket->upload($file, [
//        //CON IL FOLDER, IL NOME DEL FILE DIVENTA FOLDER/NAME
//            'name' => $id.'/'.$objectName,
//        ]);
        //AGGIUNGERE IL NOME DEL FILE AL DB
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLesson = $id;
        $this->db->update('dirette', [
            'path_video' => $objectName,
            'system_user_modified' => $user,
            ], ['id' => $idLesson]);

        return $result;
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