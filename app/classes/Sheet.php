<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Sheet extends Spreadsheet
{
    public function __construct() {
        parent::__construct();
    }
    public function getResults($idPoll) {

        $db = new Database();
        $spreadsheet = new Sheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getColumnDimension('A')->setWidth(24);
        $activeWorksheet->setCellValue('A2', 'STUDENTE');

        $queryUsers = "SELECT utenti.nome, utenti.cognome, utenti.id FROM utenti
                    JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
                    JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    JOIN corsi ON corsi_utenti.id_corso = corsi.id
                    JOIN polls ON corsi.id = polls.id_corso
                    WHERE polls.id = '$idPoll' AND utenti_gruppi.id_gruppo = 2";
        $dataUsers = $db->query($queryUsers)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataUsers as $key => $row) {
            $id = $row['id'];

            $activeWorksheet->setCellValue('A'. $key + 2, $row['nome'] . " " . $row['cognome']);

            $queryAnswers = "SELECT sceltepossibili.titolo as risposta, domande.titolo as domanda, domande.ordine FROM sceltepossibili
                                JOIN rispostescelta ON sceltepossibili.id = rispostescelta.id_risposta
                                JOIN domande ON sceltepossibili.id_domanda = domande.id
                                WHERE rispostescelta.id_utente = '$id' AND domande.id_poll = '$idPoll' AND (domande.id_tipologia = 2 OR domande.id_tipologia = 3)
                                UNION 
                                SELECT rispostetesto.risposta as risposta, domande.titolo as domanda, domande.ordine FROM rispostetesto 
                                JOIN domande ON rispostetesto.id_domanda = domande.id
                                WHERE rispostetesto.id_utente = '$id' AND domande.id_poll = '$idPoll' AND domande.id_tipologia = 1
                                ORDER BY ordine";
            $dataAnswers = $db->query($queryAnswers)->fetchAll(PDO::FETCH_ASSOC);

            $answers = array();

            foreach ($dataAnswers as $i => $value) {
                if($dataAnswers[$i]['ordine'] !== $dataAnswers[$i - 1]['ordine']) {
                    $dataAnswers[$i]['given'] = array();
                    $dataAnswers[$i]['given'][] = [$dataAnswers[$i]['risposta']];
                    $answers[] = $dataAnswers[$i];
                } else {
                    $answers[array_key_last($answers)]['given'][] = [$dataAnswers[$i]['risposta']];
                }
            }

            $queryQuestions = "SELECT domande.id_tipologia, domande.titolo as domanda, domande.ordine, sceltepossibili.titolo, sceltepossibili.corretta FROM domande LEFT JOIN sceltepossibili ON sceltepossibili.id_domanda = domande.id WHERE domande.id_poll = '$idPoll' AND (domande.id_tipologia = 1 OR domande.id_tipologia = 2 OR domande.id_tipologia = 3) ORDER BY ordine";
            $dataQuestions = $db->query($queryQuestions)->fetchAll(PDO::FETCH_ASSOC);

            $questions = array();

            foreach ($dataQuestions as $i => $value) {
                if($dataQuestions[$i]['ordine'] !== $dataQuestions[$i - 1]['ordine']) {
                    $dataQuestions[$i]['correct'] = array();
                    $dataQuestions[$i]['correct'][] = [$dataQuestions[$i]['titolo'], $dataQuestions[$i]['corretta']];
                    $questions[] = $dataQuestions[$i];
                } else {
                    $questions[array_key_last($questions)]['correct'][] = [$dataQuestions[$i]['titolo'], $dataQuestions[$i]['corretta']];
                }
            }

            foreach($questions as $j => $question) {
                foreach($question['correct'] as $anotherKey => $correct) {
                    if(count($correct) === 0) {
                        continue;
                    } else {
                        if($correct[1] === 1 && $anotherKey == count($question['correct']) - 1) {
                            $correctAnswers .= $correct[0];
                        } else if($correct[1] === 1 && $anotherKey != count($question['correct']) - 1) {
                            $correctAnswers .= $correct[0] . ', ';
                        }
                    }
                }


                    foreach($answers[$j]['given'] as $given) {
                        $givenAnswers .= $given[0] . ' ';
                    }


                if($question['id_tipologia'] !== 1) {
                    $activeWorksheet->setCellValueByColumnAndRow($j + 2,1, 'Domanda ' . $question['ordine'] . '. Risposte corrette: ' . $correctAnswers);
                    unset($correctAnswers);
                } else {
                    $activeWorksheet->setCellValueByColumnAndRow($j + 2,1, 'Domanda ' . $question['ordine']);
                }

                $activeWorksheet->setCellValueByColumnAndRow($j + 2, $key + 2 , 'Risposte: ' . $givenAnswers);
                unset($givenAnswers);

            }

        }


        $writer = new Xlsx($spreadsheet);
        $writer->save('../assets/documents/Risultati_Quiz_'. $idPoll .'.xlsx');
        $filePath = ROOT.'app/assets/documents/Risultati_Quiz_'. $idPoll .'.xlsx';
        return $filePath;
    }
    public function getResultsSurvey($idSurvey) {

        $db = new Database();
        $spreadsheet = new Sheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getColumnDimension('A')->setWidth(24);
        $activeWorksheet->setCellValue('A2', 'STUDENTE');

        $queryUsers = "SELECT utenti.nome, utenti.cognome, utenti.id FROM utenti
                    JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
                    JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    JOIN corsi ON corsi_utenti.id_corso = corsi.id
                    JOIN dirette ON dirette.id_corso = corsi.id
                    JOIN sondaggi ON dirette.id = sondaggi.id_diretta
                    WHERE sondaggi.id = '$idSurvey' AND utenti_gruppi.id_gruppo = 2";
        $dataUsers = $db->query($queryUsers)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataUsers as $key => $row) {
            $id = $row['id'];

            $activeWorksheet->setCellValue('A'. $key + 2, $row['nome'] . " " . $row['cognome']);

            $queryAnswers = "SELECT rispostesondaggi.contenuto_risposta as risposta, domandesondaggi.titolo as domanda, domandesondaggi.ordine FROM rispostesondaggi
                                JOIN domandesondaggi ON rispostesondaggi.id_domanda = domandesondaggi.id
                                WHERE rispostesondaggi.id_utente = '$id' AND domandesondaggi.id_sondaggio = '$idSurvey'
                                ORDER BY ordine";
            $dataAnswers = $db->query($queryAnswers)->fetchAll(PDO::FETCH_ASSOC);

            foreach($dataAnswers as $j => $answer) {
                $activeWorksheet->setCellValueByColumnAndRow($j + 2,1, 'Domanda ' . $answer['ordine'] . ': ' . $answer['domanda']);
                $activeWorksheet->setCellValueByColumnAndRow($j + 2, $key + 2 , $answer['risposta']);
            }

        }


        $writer = new Xlsx($spreadsheet);
        $writer->save('../assets/documents/Risultati_Sondaggio_'. $idSurvey .'.xlsx');
        $filePath = ROOT.'app/assets/documents/Risultati_Sondaggio_'. $idSurvey .'.xlsx';
        return $filePath;
    }

    public function getRegister($idCourse) {

        $db = new Database();
        $spreadsheet = new Sheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getColumnDimension('A')->setWidth(24);
        $activeWorksheet->setCellValue('A2', 'STUDENTE');

        $dataObj = new Course($idCourse);
        $courseName = $dataObj->nome;
        $data = $dataObj->getRegister();

        foreach ($data['data'] as $key => $row) {
            $activeWorksheet->setCellValue('A'. $key + 2, $row['nome']);

            foreach ($row as $date => $value) {
                $numericKeys = array_keys($row);
                if($date !== 'azioni' && $date !== 'nome') {
                    $index = array_search($date, $numericKeys);
                    $activeWorksheet->setCellValueByColumnAndRow($index, 1, $date);
                    $attendance = explode('/', $value)[0];
                    switch($attendance) {
                        case '0':
                            $attendance = 'Assente';
                            break;
                        case '1':
                            $attendance = 'Presente';
                            break;
                        default:
                            $attendance = '-';
                            break;

                    }
                    $activeWorksheet->setCellValueByColumnAndRow($index, $key + 2, $attendance);
                }
            }
        }


        $writer = new Xlsx($spreadsheet);
        $writer->save('../assets/documents/Registro_Corso_'. $courseName .'.xlsx');
        $filePath = ROOT.'app/assets/documents/Registro_Corso_'. $courseName .'.xlsx';
        return $filePath;
    }
}