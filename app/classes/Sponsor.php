<?php

class Sponsor extends BaseModel {

    private $sponsor;
    public function __construct($id) {
        parent::__construct();
        $this->table = SPONSORS;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function get() {
        $idSponsor = $this->id;
        $this->sponsor = $this->db->select("sponsor", '*', ['id' => $idSponsor] );
        return $this->sponsor;
    }
    public function update($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $sponsor = $infos['sponsor'];
        $description = $infos['descrizione'];
        $pathLink = $infos['pathLink'];
        $website = $infos['website'];
        $phone = $infos['phone'];
        $email = $infos['email'];
        $idSponsor = $this->id;

        if($infos['tmpName']){
            $tmpFile = $infos['tmpName'];
            $newFile = ROOT.'app/assets/uploaded-files/sponsor-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);

            $this->db->update('sponsor', [
                'path_immagine' => $infos['fileName'],
                'system_user_modified' => $user,
            ], ['id' => $idSponsor]);
        }

        $this->db->update('sponsor', [
            'nome' => $sponsor,
            'descrizione' => $description,
            'sito_web' => $website,
            'telefono' => $phone,
            'mail' => $email,
            'link_video' => $pathLink,
            'system_user_modified' => $user,
        ], ['id' => $idSponsor]);
    }
    public function delete() {
        $idSponsor = $this->id;

        $this->db->delete('sponsor', ['id' => $idSponsor]);
    }

}