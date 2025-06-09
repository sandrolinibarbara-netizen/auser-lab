<?php function idDispensa($parsed) {
    foreach ($parsed['lezione'] as $key => $value) {
        if ($parsed['lezione'][$key]['dispensa_embedded'] == 0) {
            echo $parsed['lezione'][$key]['idDispensa'];
            break;
        }
    }
}

function idPoll($parsed) {
    foreach ($parsed['lezione'] as $key => $value) {
        if ($parsed['lezione'][$key]['poll_embedded'] == 0) {
            echo $parsed['lezione'][$key]['idPoll'];
            break;
        }
    }
}

function idSurvey($parsed) {
    foreach ($parsed['lezione'] as $key => $value) {
        echo $parsed['lezione'][$key]['idSurvey'];
        break;
    }
}

function isDisabled($parsed, $type) {
    if($type === 'survey') {
        $surveys = 0;
        foreach ($parsed['lezione'] as $key => $value) {
            if($parsed['lezione'][$key]['idSurvey'] != null) {
                $surveys++;
            }
        }
        if($surveys === 0) {
            echo 'disabled';
        }
    } else {
        if($type === 'lecture') {
            $video = 'dispensa_embedded';
        } else {
            $video = 'poll_embedded';
        }
        $materialNotEmbedded = 0;
        foreach ($parsed['lezione'] as $key => $value) {
            if($parsed['lezione'][$key][$video] === 0) {
                $materialNotEmbedded++;
            }
        }
        if($materialNotEmbedded === 0) {
            echo 'disabled';
        }
    }

}
function getVimeoIds($url) {
    $arr = explode('/', $url);
    $ids = array();
    $ids[] = $arr[count($arr) - 2];
    $ids[] = $arr[count($arr) - 1];
    return $ids;
}

if($parsed['lezione'][0]['url'] !== NULL) {
    $vimeoIds = getVimeoIds($parsed['lezione'][0]['url']);
    $url = 'https://vimeo.com/event/' . $vimeoIds[0] . '/embed/' . $vimeoIds[1] . '/interaction';
}
?>

<div class="d-flex flex-column w-100 align-items-center justify-content-center gap-12 mb-7 mt-12">
    <header class="d-flex flex-column align-items-center justify-content-center">
        <h2 class="fs-1"><?=$parsed['lezione'][0]['nome']?></h2>
        <h3><?=$parsed['lezione'][0]['corso']?></h3>
    </header>
    <?php if($parsed['lezione'][0]['url'] !== NULL):?>
        <iframe src="<?= $url ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" class="w-75 h-500px rounded"></iframe>
    <?php elseif($parsed['lezione'][0]['path_video'] !== NULL) :?>
        <div id="waiting-div" class="w-75 h-500px rounded bg-light-bg d-flex align-items-center justify-content-center">
            <div class="spinner-border text-white" role="status">
                <span class="sr-only">Caricamento del video</span>
            </div>
        </div>
        <video id="embedded-video" class="video-js vjs-default-skin w-75 h-500px rounded d-none" controls><source src="<?=ROOT.'app/assets/videos/'.$parsed['lezione'][0]['path_video']?>"></video>
    <?php elseif($parsed['lezione'][0]['zoom_meeting'] !== NULL) :?>
        <div id="wait-meeting" class="w-75 h-500px rounded bg-auser d-flex flex-column gap-8 align-items-center justify-content-center">
            <img alt="auser-logo" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.svg" class="w-50"/>
            <button class="rounded btn btn-light-bg text-black" onclick="getSignature()">Unisciti alla lezione</button>
        </div>
        <div id="zoom-box" class="w-100 gap-16 d-flex d-none position-relative">
            <div id="meetingSDKElement" class="w-75 h-700px rounded"></div>
            <div id="zoom-chat" class="w-25 h-1px"></div>
            <div class="w-25 h-650px rounded bg-light-bg position-absolute z-index-n1 d-flex flex-column align-items-center justify-content-center px-4 text-center" style="right: 0; top: 50%; transform: translateY(-50%)">
                <p class="fs-1 fw-bold">Chat</p>
                <p class="fs-4 fw-medium">Clicca su '...' e seleziona 'Chat' per visualizzare la chat della lezione</p>
            </div>
        </div>
        <input type="text" id="zoom-meeting" value="<?php echo $parsed['lezione'][0]['zoom_meeting'];?>" hidden readonly>
        <input type="text" id="zoom-pw" value="<?php echo $parsed['lezione'][0]['zoom_pw'];?>" hidden readonly>
        <input type="text" id="zoom-sdkkey" value="<?php echo ZOOMSDKKEY;?>" hidden readonly>
        <input type="text" id="user-fullname" value="<?php echo $parsed['user'][0]['nome'] . ' ' . $parsed['user'][0]['cognome'];?>" hidden readonly>
    <?php endif;?>
    <div class="d-flex gap-4">
        <button class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#agenda-modal"><i class="fs-3 ki-outline ki-calendar"></i> Info</button>
        <?php if($parsed['lezione'][0]['path_video'] !== NULL):?>
            <button class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#question-modal"><i class="fs-3 ki-outline ki-message-notif"></i> Fai una domanda</button>
        <?php endif;?>
        <button <?=isDisabled($parsed, 'lecture')?> class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#lecture-note-modal" data-bs-idDispensa="<?=idDispensa($parsed)?>"><i class="fs-3 ki-outline ki-scroll"></i> Dispense</button>
        <button <?=isDisabled($parsed, 'poll')?> class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#poll-modal" data-bs-idPoll="<?=idPoll($parsed)?>"><i class="fs-3 ki-outline ki-questionnaire-tablet"></i> Quiz</button>
        <button <?=isDisabled($parsed, 'survey')?> class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#survey-modal" data-bs-idSurvey="<?=idSurvey($parsed)?>"><i class="fs-3 ki-outline ki-like"></i> Sondaggio</button>
    </div>

    <div class="card d-flex gap-8 p-7 w-75">
        <h4 class="card-header pb-7">Insegnanti</h4>
        <div class="card-body d-flex flex-row gap-12 p-4">
            <?php foreach($parsed['insegnanti'] as $teacher): ?>
                <div class="d-flex flex-column gap-4 align-items-center">
                    <img src="<?php echo (explode(':', $teacher['immagine'])[0] === 'http' || explode(':', $teacher['immagine'])[0] === 'https' ? $teacher['immagine'] : ROOT . 'app/assets/uploaded-files/users-images/' . $teacher['immagine'])?>" alt="<?= $teacher['nome']. '-' . $teacher['cognome'] .'-avatar' ?>" class="bg-light-bg w-100px h-100px rounded-circle" style="object-fit: cover; object-position: top"/>
                    <div class="text-center">
                        <p><?=$teacher['nome']. ' ' . $teacher['cognome']?></p>
                        <p class="text-decoration-underline cursor-pointer" data-bs-toggle="modal" data-bs-target="#teacher-<?=$teacher['idUtente']?>-modal">Bio</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if (count($parsed['sponsor']) > 0) : ?>
        <div class="card d-flex gap-8 p-7 w-75">
            <h4 class="card-header pb-7">Sponsor</h4>
            <div class="card-body d-flex flex-row gap-12 p-4">
                <?php foreach($parsed['sponsor'] as $sponsor): ?>
                    <div class="d-flex flex-column gap-4 align-items-center">
                        <img class="bg-light-bg rounded-circle w-100px h-100px" style="object-fit: cover; object-position: top" src="<?=ROOT.'app/assets/uploaded-files/sponsor-images/'.$sponsor['pic']?>" alt="<?=$sponsor['sponsor']?> avatar"/>
                        <div class="text-center">
                            <p><?=$sponsor['sponsor']?></p>
                            <p class="text-decoration-underline cursor-pointer" data-bs-toggle="modal" data-bs-target="#sponsor-<?=$sponsor['idSponsor']?>-modal">Leggi di più</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif ;?>

    <div class="card d-flex gap-8 p-7 w-75">
        <h4 class="card-header pb-7">Iscritti</h4>
        <div class="card-body d-flex flex-row gap-12 p-4">
            <?php foreach($parsed['partecipanti'] as $attendee): ?>
                <div class="d-flex flex-column gap-4 align-items-center">
                    <img src="<?php echo (explode(':', $attendee['immagine'])[0] === 'http' || explode(':', $attendee['immagine'])[0] === 'https' ? $attendee['immagine'] : ROOT . 'app/assets/uploaded-files/users-images/' . $attendee['immagine'])?>" alt="<?= $attendee['nome']. '-' . $attendee['cognome'] .'-avatar' ?>" class="bg-light-bg w-100px h-100px rounded-circle" style="object-fit: cover; object-position: top"/>
                    <div class="text-center">
                        <p><?=$attendee['nome']. ' ' . $attendee['cognome']?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>