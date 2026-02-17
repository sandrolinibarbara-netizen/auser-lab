<?php
function idDispensa($parsed) {
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
            return true;
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
            return true;
        }
    }

}
//function getVimeoIds($url) {
//        $arr = explode('/', $url);
//        $ids = array();
//        $ids[] = $arr[count($arr) - 2];
//        $ids[] = $arr[count($arr) - 1];
//        return $ids;
//    }
//
//if($parsed['lezione'][0]['url'] !== NULL) {
//    $vimeoIds = getVimeoIds($parsed['lezione'][0]['url']);
//    $url = 'https://vimeo.com/event/' . $vimeoIds[0] . '/embed/' . $vimeoIds[1] . '/interaction';
//}
?>

<div class="d-flex flex-column w-100 align-items-center justify-content-center gap-12 mb-7 mt-12">
    <header class="d-flex flex-column align-items-center justify-content-center">
        <h2 class="rightFont fs-4"><?=$parsed['lezione'][0]['nome']?></h2>
        <h3 class="rightFont fs-5"><?=$parsed['lezione'][0]['corso']?></h3>
    </header>
    <?php if($parsed['lezione'][0]['url'] !== NULL):?>
        <iframe src="<?= $parsed['lezione'][0]['url'] ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" class="w-75 h-500px rounded"></iframe>
    <?php elseif($parsed['lezione'][0]['path_video'] !== NULL) :?>
        <div id="waiting-div" class="w-75 h-500px rounded bg-light-bg d-flex align-items-center justify-content-center">
            <div class="spinner-border text-white" role="status">
                <span class="sr-only">Caricamento del video</span>
            </div>
        </div>
<!--        <video id="embedded-video" class="video-js vjs-default-skin w-75 h-500px rounded d-none" controls><source src="--><?php //=ROOT.'app/assets/videos/'.$parsed['lezione'][0]['path_video']?><!--"></video>-->
        <video id="embedded-video" class="video-js vjs-default-skin w-75 h-500px rounded d-none" controls><source src="https://storage.cloud.google.com/auser-zoom-meetings/<?=$parsed['lezione'][0]['id'].'/'.$parsed['lezione'][0]['path_video'].'?authuser=2'?>"></video>
    <?php elseif($parsed['lezione'][0]['zoom_meeting'] !== NULL) :?>
        <div id="wait-meeting" class="w-75 h-500px rounded bg-auser d-flex flex-column gap-8 align-items-center justify-content-center position-relative" style="z-index: 1500">
            <img alt="auser-logo" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.webp" class="w-50"/>
            <button id="join-meeting" class="rounded btn btn-light-bg text-black rightFont better-hover text-hover-white" onclick="getSignature()" style="border: #D2E5D3 solid 1px">Unisciti alla lezione</button>
            <button id="wait-connection" class="indicator-progress d-none rounded btn btn-light-bg text-black rightFont better-hover text-hover-white">
                Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </button>
            <div id="error-message" class="p-4 card bg-light-danger border border-danger w-75 d-flex flex-col align-items-center gap-4 d-none">
                <div class="d-flex gap-2 align-items-center">
                    <i class="ki-outline ki-information-5 fs-2 text-danger"></i>
                    <p class="fs-6 m-0 error-content"></p>
                </div>
                <button class="rounded btn btn-danger rightFont d-flex gap-2" onclick="getSignature()" style="border: #D2E5D3 solid 1px">
                    <i class="ki-outline ki-arrows-circle fs-4 text-white"></i>
                    Riprova
                </button>
            </div>
        </div>
        <div id="zoom-box" class="gap-0 d-flex d-none position-relative">
            <div id="meetingSDKElement" class="rounded" style="margin-left: auto; margin-right: auto"></div>
        </div>
        <input type="text" id="zoom-meeting" value="<?php echo $parsed['lezione'][0]['zoom_meeting'];?>" hidden readonly>
        <input type="text" id="zoom-pw" value="<?php echo $parsed['lezione'][0]['zoom_pw'];?>" hidden readonly>
        <input type="text" id="zoom-sdkkey" value="<?php echo ZOOMSDKKEY;?>" hidden readonly>
        <input type="text" id="user-fullname" value="<?php echo $parsed['user'][0]['nome'] . ' ' . $parsed['user'][0]['cognome'];?>" hidden readonly>
    <?php endif;?>
    <button id="more-info" type="button" class="position-fixed btn text-black rounded-circle p-3 icon-hover text-hover-white" style="top: 15vh; left:2vw; z-index:1500; border: #D2E5D3 solid 1px; background-color: #D2E5D3">
        <i class="ki-outline ki-information-2 p-0 text-black" style="font-size: 32px"></i>
    </button>
    <div id="lesson-info" class="bg-white flex-column p-7 gap-4 rightFont" style="z-index: 1700"
         data-kt-drawer="true"
         data-kt-drawer-activate="true"
         data-kt-drawer-toggle="#more-info"
         data-kt-drawer-close="#less-info"
         data-kt-drawer-direction="start"
         data-kt-drawer-width="600px">
         <div class="d-flex align-items-center justify-content-between w-100">
             <a href="<?=ROOT?>home?show=ecommerce"><img alt="auser-logo" class="w-150px" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_black.svg" /></a>
             <div id="less-info" class="btn btn-icon btn-sm btn-active-light-primary ms-2">
                 <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
             </div>
         </div>
        <div class="card d-flex gap-8 w-100">
            <h4 class="card-header p-7 fs-5" style="border-bottom: none; background-color: #F1F1F4 !important">Lezione</h4>
            <div class="card-body d-flex flex-row gap-12 px-4 pb-4 pt-0">
                <div class="d-flex flex-wrap gap-4 w-100">
                    <button style="font-size: 15px !important" class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#agenda-modal"><i class="fs-4 ki-outline ki-calendar"></i> Agenda</button>
                    <?php if($parsed['lezione'][0]['path_video'] !== NULL):?>
                        <button class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#question-modal"><i class="fs-4 ki-outline ki-message-question"></i> Fai una domanda</button>
                    <?php endif;?>
                    <button style="font-size: 15px !important" <?=isDisabled($parsed, 'lecture') ? 'disabled' : ''?> class="<?=isDisabled($parsed, 'lecture') ? 'text-gray-500 border border-gray-500' : 'btn-secondary'?> btn d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#lecture-note-modal" data-bs-idDispensa="<?=idDispensa($parsed)?>"><i class="<?=isDisabled($parsed, 'lecture') ? 'text-gray-400' : ''?> fs-4 ki-outline ki-scroll"></i> Dispense</button>
                    <button style="font-size: 15px !important" <?=isDisabled($parsed, 'poll') ? 'disabled' : ''?> class="<?=isDisabled($parsed, 'poll') ? 'text-gray-500 border border-gray-500' : 'btn-secondary'?> btn d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#poll-modal" data-bs-idPoll="<?=idPoll($parsed)?>"><i class="<?=isDisabled($parsed, 'poll') ? 'text-gray-400' : ''?> fs-4 ki-outline ki-questionnaire-tablet"></i> Quiz</button>
                    <button style="font-size: 15px !important" <?=isDisabled($parsed, 'survey') ? 'disabled' : ''?> class="<?=isDisabled($parsed, 'survey') ? 'text-gray-500 border border-gray-500' : 'btn-secondary'?> btn d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#survey-modal" data-bs-idSurvey="<?=idSurvey($parsed)?>"><i class=" <?=isDisabled($parsed, 'survey') ? 'text-gray-400' : ''?> fs-4 ki-outline ki-like"></i> Sondaggio</button>
                </div>
            </div>
        </div>
        <div class="card d-flex gap-8 w-100">
                <h4 class="card-header p-7 fs-5" style="border-bottom: none; background-color: #F1F1F4 !important">Insegnanti</h4>
                <div class="card-body d-flex flex-row gap-12 px-4 pb-4 pt-0 flex-wrap" style="font-size: 14px !important">
        <?php foreach($parsed['insegnanti'] as $teacher): ?>
            <div class="d-flex flex-column gap-4 align-items-center">
                <img src="<?php echo (explode(':', $teacher['immagine'])[0] === 'http' || explode(':', $teacher['immagine'])[0] === 'https' ? $teacher['immagine'] : ROOT . 'app/assets/uploaded-files/users-images/' . $teacher['immagine'])?>" alt="<?= $teacher['nome']. '-' . $teacher['cognome'] .'-avatar' ?>" class="bg-light-bg w-100px h-100px rounded-circle" style="object-fit: cover; object-position: top"/>
                <div class="text-center">
                    <p class="mb-1"><?=$teacher['nome']. ' ' . $teacher['cognome']?></p>
                    <p class="text-decoration-underline cursor-pointer" data-bs-toggle="modal" data-bs-target="#teacher-<?=$teacher['idUtente']?>-modal">Bio</p>
                </div>
            </div>
        <?php endforeach; ?>
                </div>
            </div>
        <?php if (count($parsed['sponsor']) > 0) : ?>
            <div class="card d-flex gap-8 w-100">
                <h4 class="card-header p-7 fs-5" style="border-bottom: none; background-color: #F1F1F4 !important">Partner</h4>
                <div class="card-body d-flex flex-row gap-12 px-4 pb-4 pt-0 flex-wrap" style="font-size: 14px !important">
                <?php foreach($parsed['sponsor'] as $sponsor): ?>
                    <div class="d-flex flex-column gap-4 align-items-center">
                        <img class="bg-light-bg rounded-circle w-100px h-100px" style="object-fit: cover; object-position: top" src="<?=ROOT.'app/assets/uploaded-files/sponsor-images/'.$sponsor['pic']?>" alt="<?=$sponsor['sponsor']?> avatar"/>
                        <div class="text-center">
                            <p class="mb-1"><?=$sponsor['sponsor']?></p>
                            <p class="text-decoration-underline cursor-pointer" data-bs-toggle="modal" data-bs-target="#sponsor-<?=$sponsor['idSponsor']?>-modal">Leggi di più</p>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="card d-flex gap-8 w-100">
            <h4 class="card-header p-7 fs-5" style="border-bottom: none; background-color: #F1F1F4 !important">Iscritti</h4>
            <div class="card-body d-flex flex-row gap-12 px-4 pb-4 pt-0 flex-wrap" style="font-size: 14px !important">
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
</div>
