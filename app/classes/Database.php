<?php
use Medoo\Medoo;
class Database extends \Medoo\Medoo
{

    public function __construct()
    {
        parent::__construct([
            'database_type' => DATABASETYPE,
            'database_name' => DATABASENAME,
            'server' => SERVER,
            'username' => USER,
            'password' => PWD
        ]);
    }

    public function is_session_valid($token) {

        $options = array();
        $options["token"] = decryptStr($token);

        if($this->has(USERS, $options)) {

            $user = new User($this->get(USERS, 'id', $options));
            $now = new DateTime();
            $token_end_time = new DateTime($user->token_end_time);

            if($now >= $token_end_time) {

                return false;

            } else {

                $user->refresh_public_date($now);
                return true;
            }
        }

        return false;
    }
    public function is_link_valid($tokenSub) {

        $options = array();
        $options["token_sub"] = decryptStr($tokenSub);

        if($this->has(USERS, $options)) {

            $user = new User($this->get(USERS, 'id', $options));
            $now = new DateTime();
            $tokenSubEnd = new DateTime($user->token_sub_end_time);

            if($now >= $tokenSubEnd) {
                $this->delete('utenti', ['id' => $user->id]);
                return false;

            } else {
                $this->update('utenti', [
                    'token_sub' => NULL,
                    'token_sub_end_time' => NULL,
                    'active' => 1
                ], ['id' => $user->id]);
                return cryptStr($user->id);
            }
        }

        return false;
    }

    public function login($username, $password) {

        $options = array();
        $options["username"] = cryptStr(clearHtml($username));
        $options["password"] = cryptStr(clearHtml($password));
        $options["active"] = 1;
        $options["deleted"] = 0;

        if($this->has(USERS, $options)) {

            $user = $this->get(USERS, array('id'), $options);

            $id_user = $user["id"];

            if($id_user && $id_user > 0) {

                $token = getGUID();
                $options = array();
                $options["token"] = $token;
                $token_start_time = new DateTime();
                $options["token_start_time"] = $token_start_time->format('Y-m-d H:i');
                $token_start_time->add(new DateInterval(SESSIONDURATION));
                $options["token_end_time"] = $token_start_time->format('Y-m-d H:i');

                $where = array();
                $where["id"] = $id_user;

                $this->update(USERS, $options, $where);

                $_SESSION[SESSIONROOT]['user_token'] = cryptStr($token);
                $query = "SELECT MAX(licenza_fine) FROM tesseramento WHERE id_utente = '$id_user'";
                $data = $this->query($query)->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION[SESSIONROOT]['end_license'] = $data[0]["MAX(licenza_fine)"];
                return true;
            }
        }
        return false;
    }
}