<?php

use PHPMailer\PHPMailer\PHPMailer;

class Email extends PHPMailer {

    public function __construct($exceptions = null) {
        parent::__construct($exceptions);
        $this->config();
    }

    private function config() {

        $this->SMTPDebug = true;
        $this->isSMTP();
        $this->Helo = 'ec2-3-69-38-63.eu-central-1.compute.amazonaws.com';
        $this->Hostname = 'ec2-3-69-38-63.eu-central-1.compute.amazonaws.com';
        $this->Host = EMAILHOST;
        $this->SMTPAuth = EMAILSMPTAUTH;
        $this->Username = EMAILUSERNAME;
        $this->Password = EMAILPWD;
        $this->Port = EMAILPORT;
        $this->CharSet = 'UTF-8';
//        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
    }

    public function sendEmail($infos, $confirmation = false, $password = false, $purchase = false, $newCard = false) {

        try {
                    $receiverEmail = $infos['receiverEmail'];
                    $userMessage = $infos['userMessage'];

                    if(!$confirmation && !$password && !$newCard) {
                        $userEmail = $infos['userEmail'];
                        $userName = $infos['userName'];
                    }

                    $this->setFrom(EMAILSENDER, EMAILSENDERAVATAR);
                    $this->addAddress($receiverEmail);
                    if($confirmation || $password || $purchase || $newCard) {
                        $this->isHTML();
                    } else {
                        $this->isHTML(false);
                    }
                    $this->SMTPDebug = 0;

                    if($confirmation) {
                        $subject = 'Conferma la tua email';
                    } elseif($password) {
                        $subject = 'Recupero password';
                    } elseif($purchase) {
                        $subject = "Grazie per l'acquisto!";
                    } elseif($newCard) {
                        if($infos['adminSbj']) {
                            $subject = $infos['adminSbj'];
                        } else {
                            $subject = "Verifica tessera | Auser Unipop";
                        }
                    } else {
                        $subject = $userName . ' ti ha fatto una domanda!';
                        $this->addReplyTo($userEmail, $userName);
                    }

                    $body = $userMessage;
                    $this->Subject = $subject;
                    $this->Body = $body;
//                    printAllStop($infos);

                    $this->send();

        } catch (phpmailerException $e) {

            echo $e->errorMessage(); //Pretty error messages from PHPMailer

        } catch (Exception $e) {

            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }
    public function sendMultipleEmails($primaryReceiver, $students, $type) {

        try {
            $receiverEmail = $primaryReceiver['email'];
            if($type == 'course') {
                $userMessage = '<p>'. $primaryReceiver['nome'] . ' ' . $primaryReceiver['cognome'] .' ti ha iscritto a un corso privato di Auser UniPop.<br/>
                                Le lezioni del corso saranno già visibili nella tabella "Prossime lezioni" della tua dashboard personale la prossima volta che eseguirari l\'accesso alla piattaforma!<br/>
                                Buon apprendimento!</p>';
                $subject = 'Sei stato invitato a un corso di Auser UniPop!';
            } else {
                $userMessage = '<p>'. $primaryReceiver['nome'] . ' ' . $primaryReceiver['cognome'] .' ti ha iscritto a un evento privato di Auser UniPop.<br/>
                                L\' evento sarà già visibile nella tabella "Prossimi eventi" della tua dashboard personale la prossima volta che eseguirari l\'accesso alla piattaforma!<br/>
                                Buon apprendimento!</p>';
                $subject = 'Sei stato invitato a un evento di Auser UniPop!';
            }

            $this->setFrom(EMAILSENDER, EMAILSENDERAVATAR);
            $this->addAddress($receiverEmail);
            foreach ($students as $student) {
                $this->addBCC($student);
            }
            $this->isHTML();
            $this->SMTPDebug = 0;

            $body = $userMessage;
            $this->Subject = $subject;
            $this->Body = $body;

            $this->send();

        } catch (phpmailerException $e) {

            echo $e->errorMessage(); //Pretty error messages from PHPMailer

        } catch (Exception $e) {

            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }
}