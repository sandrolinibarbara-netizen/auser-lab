<?php
$ondemand = $data[0]['data_inizio'] === '01/01/3000';
loadPartial('layout/head');
loadPartial('layout/page');
loadPartial('layout/header-ecommerce');

?>

    <div id="kt_app_content" class="app-content flex-column-fluid my-10">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="container px-4">
                <div id="position-relative courses-events-grid" class="row g-4">
                    <div class="h-500px mx-auto rounded overlay overlay-block p-0">
                        <div class="overlay-wrapper w-100">
                            <img class="rounded" style="height: 500px; width: 100%; object-fit: cover; object-position: center" src="<?=ROOT.'app/assets/uploaded-files/heros-images/'.$data[0]['immagine']?>" alt="course-pic"/>
                        </div>
                        <div class="overlay-layer bg-auser bg-opacity-75 rounded">
                            <div class="position-absolute" style="bottom: 10%; left: 6%; max-width: 50%">
                                <h2 class="text-white mb-4"><?=$data[0]['corso']?></h2>
                                <?php if(!$ondemand) :?>
                                    <p class="text-white mb-1">Inizio corso: <?=$data[0]['data_inizio']?></p>
                                    <p class="text-white">Fine corso: <?=$data[0]['data_fine']?></p>
                                <?php else :?>
                                    <p class="text-white">Corso on-demand</p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute card shadow-sm w-25" style="right: 10%; top: 500px">
                        <div class="card-body p-4">
                            <div class="h-150px rounded">
                                <?php if(!isset($data[0]['video']) || $data[0]['video'] == null) : ?>
                                    <img class="rounded" style="height: 150px; width: 100%; object-fit: cover; object-position: center" src="<?=ROOT.'app/assets/uploaded-files/heros-images/'.$data[0]['immagine']?>" alt="course-pic"/>
                                <?php else :?>
                                    <iframe class="h-150px w-100 rounded" src="<?=$data[0]['video']?>" frameborder="0" allow="autoplay"></iframe>
                                <?php endif;?>
                            </div>
                            <div class="d-flex flex-column align-items-center justify-content-center gap-4 mx-4 mb-4 mt-6">
                                <div class="w-100 text-start">
                                    <h4><?=$data[0]['corso']?></h4>
                                    <div class="separator mb-2"></div>
                                    <p class="p-1">Per partecipare a questo corso è necessario <?php echo(!isset($data[0]['iscrizione']) || $data[0]['iscrizione'] == null || count($data[0]['iscrizione']) == 0) ? '': 'essere tesserati e '?>versare il relativo contributo</p>
                                    <div class="separator m-2"></div>
                                    <p class="mt-4 mb-2 w-100 text-end fw-bold fs-4"><?php echo($data[0]['importo'] === 0 || !$data[0]['importo']) ? 'Gratuito' : '€' . $data[0]['importo'] . ',00'?></p>
                                </div>
                                <?php if($_SESSION[SESSIONROOT]['group'] != 1 && $_SESSION[SESSIONROOT]['group'] != 3) : ?>
                                    <?php if(isset($_SESSION[SESSIONROOT]['user'])) : ?>
                                        <?php if($data[0]['posti'] == 0 && !$ondemand) :?>
                                            <button class="btn btn-danger d-flex align-items-center justify-content-center"><i class="ki-outline ki-information-5 fs-2"></i> Posti esauriti</button>
                                        <?php elseif(!isset($data[0]['tesseramentoValido'])) :?>
                                            <button class="btn btn-danger d-flex align-items-center justify-content-center"><i class="ki-outline ki-information-5 fs-2"></i> Per poter effettuare l'acquisto devi essere tesserato</button>
                                        <?php elseif($data[0]['acquistato'] === 1):?>
                                            <button class="btn btn-warning d-flex align-items-center justify-content-center"><i class="ki-outline ki-information-5 fs-2"></i> Hai già acquistato questo corso</button>
                                        <?php elseif($data[0]['importo'] === 0 || !$data[0]['importo']):?>
                                            <button id='register-free' value="<?='c-'.$data[0]['id']?>" class="btn btn-success d-flex align-items-center justify-content-center"><i class="ki-outline ki-bookmark fs-2"></i> Registrati al corso</button>
                                        <?php else:?>
                                            <button
                                                    <?php
                                                        if(isset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']])) {
                                                            echo (in_array('c-'.$data[0]['id'], $_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']])) ? 'disabled' : '';
                                                        }
                                                    ?>
                                                    id="add-cart-button" value="<?='c-'.$data[0]['id']?>" class="btn btn-success d-flex align-items-center justify-content-center"><i class="ki-outline ki-handcart fs-2"></i> Aggiungi al carrello</button>
                                        <?php endif;?>
                                    <?php else: ?>
                                        <a href="<?= ROOT.'login'?>" class="btn btn-outline-success btn-outline d-flex align-items-center justify-content-center"><i class="ki-outline ki-key fs-2"></i> Effettua l'accesso</a>
                                    <?php endif;?>
                                <?php endif;?>
                            </div>
                        </div>

                    </div>
                    <div class="mt-20 ms-20 w-75 d-flex flex-column gap-8">
                        <div>
                            <h3 class="px-2">Descrizione</h3>
                            <div class="separator mt-4 mb-8 border-gray-900"></div>
                            <p class="px-2 mw-75"><?=$data[0]['descrizione']?></p>
                        </div>
                        <div>
                            <h3 class="px-2">Info</h3>
                            <div class="separator mt-4 mb-8 border-gray-900"></div>
                            <?php if(!$ondemand) :?>
                                <p class="px-2 d-flex align-items-center gap-2"><i class="ki-outline ki-time fs-2"></i> Durata del corso: <?=$data[0]['durata'] == 0 ? $data[0]['durataSettimane'] : $data[0]['durata']?> <?php
                                    if($data[0]['durata'] > 0) {
                                        if($data[0]['durata'] == 1) {
                                            echo 'mese';
                                        } else {
                                            echo 'mesi';
                                        }
                                    } else {
                                        if($data[0]['durataSettimane'] == 1) {
                                            echo 'settimana';
                                        } else {
                                            echo 'settimane';
                                        }
                                    }
                                    ?></p>
                            <?php endif;?>
                            <p class="px-2 d-flex align-items-center gap-2"><i class="ki-outline ki-briefcase fs-2"></i> Numero di lezioni: <?=$data[0]['lezioni']?></p>
                            <p class="px-2 d-flex align-items-center gap-2"><i class="ki-outline ki-timer fs-2"></i> Tempo a lezione: <?=$data[0]['lunghezza']?> <?= ($data[0]['lunghezza'] == 1) ? 'ora' : 'ore'?></p>
                            <?php if(!$ondemand) :?>
                             <p class="px-2 d-flex align-items-center gap-2"><i class="ki-outline ki-profile-user fs-2"></i> Posti disponibili: <?php echo($data[0]['posti'] == 0) ? 'Esauriti' : $data[0]['posti']?></p>
                            <?php endif;?>
                            <p class="px-2 d-flex align-items-center gap-2"><i class="ki-outline ki-pencil fs-2"></i> Iscrizione: <?=implode(', ', $data[0]['iscrizione'])?></p>
                            <p class="px-2 d-flex align-items-center gap-2"><i class="ki-outline ki-laptop fs-2"></i> Modalità: <?=implode(', ', $data[0]['modalita'])?></p>
                            <p class="px-2 d-flex align-items-center gap-2"><i class="ki-outline ki-laptop fs-2"></i> Contributo: <?php echo($data[0]['importo'] === 0 || !$data[0]['importo']) ? 'gratuito' : '€' . $data[0]['importo'] . ',00'?></p>
                        </div>
                        <div>
                            <h3 class="px-2"><?php echo(count($data[0]['insegnanti']) == 1) ? 'Insegnante' : 'Insegnanti'?></h3>
                            <div class="separator mt-4 mb-8 border-gray-900"></div>
                            <div>
                                <?php foreach($data[0]['insegnanti'] as $teacher): ?>
                                <div class="pb-4 d-flex gap-8">
                                    <div>
                                        <img src="<?php echo (explode(':', $teacher['avatar'])[0] === 'http' || explode(':', $teacher['avatar'])[0] === 'https' ? $teacher['avatar'] : ROOT . 'app/assets/uploaded-files/users-images/' . $teacher['avatar'])?>" alt="<?= $teacher['fullName'].'-avatar' ?>" class="bg-light-bg w-100px h-100px rounded-circle" style="object-fit: cover; object-position: top"/>
                                    </div>
                                    <div>
                                        <h4 class="pb-2"><?=$teacher['fullName']?></h4>
                                        <p><?=$teacher['bio']?></p>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                        <div>
                            <h3 class="px-2">Tag</h3>
                            <div class="separator mt-4 mb-8 border-gray-900"></div>
                            <a href="<?= ROOT. 'tag?tag=category&id=' . $data[0]['categoria']?>" class="btn btn-light-bg py-2 px-3"><?=$data[0]['argomento']?></a>
                            <?php foreach($data[0]['insegnanti'] as $teacher): ?>
                                <a href="<?= ROOT. 'tag?tag=teacher&id=' . $teacher['id']?>" class="btn btn-light-bg py-2 px-3"><?=$teacher['fullName']?></a>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
    <script src="<?=ROOT?>app/modules/partials/components/ecommerce/search.js"></script>
<?php loadPartial('layout/footer')?>