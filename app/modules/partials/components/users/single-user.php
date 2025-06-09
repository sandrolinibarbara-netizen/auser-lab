<?php
$today = new DateTime();
$thisYear = $today->format('Y');

$todayStr = $today->format("Y-m-d");
$todayTmsp = strtotime($todayStr);
$nextTmsp = strtotime("+1 year", $todayTmsp);
$nextYearStr = date("Y-m-d", $nextTmsp);
$nextYear = new DateTime($nextYearStr);
$nextYear = $nextYear->format('Y');
?>
<div class="d-flex flex-column align-items-center">
    <div class="card h-md-100 my-8 w-75">
        <!--begin::Header-->
        <div class="card-header py-7 d-flex flex-row justify-content-between align-items-center">
            <!--begin::Title-->
            <h3 class="card-title">
                <span class="card-label fw-bold text-gray-800">Profilo</span>
            </h3>
            <div class="d-flex gap-4 align-items-center">
                <?php if($data['user']['permissionGranted'] === 1 && $data['user']['gruppo'] === 2): ?>
                    <button data-bs-toggle="modal" data-bs-target="#user-modal" id='add-user-button' class="btn btn-light-bg btn-sm"><i class="ki-outline ki-plus-square fs-6"></i> Iscrivi a un corso</button>
                <?php endif; ?>
                <a class="text-decoration-none mt-1" href="<?= ROOT. 'utente?utente=infos&id=' . $data['user']['id'] .'&update=1'?>"><i class="ki-outline ki-message-edit fs-2 p-2 bg-light-bg text-auser rounded"></i></a>
            </div>
            <!--end::Title-->
            <!--begin::Toolbar-->
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body pt-6">
            <div>
                <p class="form-label">Immagini</p>
                <div class="image-input image-input-circle my-4" data-kt-image-input="true" style="background-color: #D2E5D3">
                    <!--begin::Image preview wrapper-->
                    <div class="image-input-wrapper w-150px h-150px" style="background-image: url('<?php echo (explode(':', $data['user']['immagine'])[0] === 'http' || explode(':', $data['user']['immagine'])[0] === 'https') ? $data['user']['immagine'] : ROOT . 'app/assets/uploaded-files/users-images/' .$data['user']['immagine']  ?>')">
                    </div>
                    <!--end::Image preview wrapper-->
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label" for="user-name">Nome</label>
                    <input class="form-control form-control-solid" id="user-name" value="<?=$data['user']['nome']?>" disabled/>
                </div>
                <div class="col-6">
                    <label class="form-label" for="user-surname">Cognome</label>
                    <input class="form-control form-control-solid" id="user-surname" value="<?=$data['user']['cognome']?>" disabled/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label" for="user-email">Email</label>
                    <input class="form-control form-control-solid" id="user-email" value="<?=$data['user']['email']?>" disabled/>
                </div>
                <div class="col-6">
                    <label class="form-label" for="user-birthdate">Data di nascita</label>
                    <input class="form-control form-control-solid" id="user-birthdate" value="<?=$data['user']['data_nascita']?>" disabled/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label" for="user-phone">Telefono</label>
                    <input class="form-control form-control-solid" id="user-phone" value="<?=$data['user']['telefono']?>" disabled/>
                </div>
                <div class="col-6">
                    <label class="form-label" for="user-address">Indirizzo</label>
                    <input class="form-control form-control-solid" id="user-address" value="<?=$data['user']['indirizzo']?>" disabled/>
                </div>
            </div>
            <div class="row">
                <div>
                    <label class="form-label" for="user-job">Situazione attuale</label>
                    <input class="form-control form-control-solid" id="user-job" value="<?=$data['user']['impiego'] ?? '-'?>" disabled/>
                </div>
            </div>
        </div>
        <!--end: Card Body-->
    </div>
    <?php if($_SESSION[SESSIONROOT]['group'] === 1):?>
        <div class="card h-md-100 my-8 w-75">
            <!--begin::Header-->
            <div class="card-header py-7 d-flex flex-row justify-content-between align-items-center">
                <!--begin::Title-->
                <h3 class="card-title">
                    <span class="card-label fw-bold text-gray-800">Documenti tesseramento</span>
                </h3>
                <?php if($data['user']['minorenne'] === 1) :?>
                    <span class="badge badge-danger"><i class="ki-outline ki-information-5 fs-5 text-white pe-1"></i> Utente minorenne</span>
                <?php endif; ?>
                <!--end::Title-->
            </div>
            <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eu lacus vitae nulla volutpat laoreet id vitae quam.
                        Sed eleifend egestas luctus. Phasellus lectus ex, accumsan ac scelerisque vel, porta dictum tellus. Donec tincidunt metus
                        ut lectus elementum, vitae accumsan diam blandit.</p>

                <div class="w-100 text-start mt-8">
                    <p class="form-label">Documenti caricati</p>
                </div>

                <p>Seleziona l'anno di validità dei documenti</p>
                <div class="row pt-4 pb-6">
                    <select class="form-select" data-control="select2" id="privacy-year" data-placeholder="Selezionare un anno">
                        <option value="<?=$thisYear?>">
                            <?=$thisYear?>
                        </option>
                        <option value="<?=$nextYear?>">
                            <?=$nextYear?>
                        </option>
                    </select>
                </div>
                <?php foreach($data['subs'] as $key => $sub) : ?>
                    <div id="container<?='-'.$key?>" class="<?php echo($key == $nextYear) ? 'd-none' : ''?>">
                        <?php if($sub['privacy'] == null) : ?>
                            <div class="w-100 text-center mt-10 fst-italic">
                                <p>Non sono presenti documenti.</p>
                            </div>
                        <?php else:?>
                                <?php if($sub['privacy'] !== null) :?>

                                <div class="w-75 d-flex flex-column justify-content-start align-items-center pt-6 mx-auto align-items-center">
                                    <img class="w-25" src="<?=ROOT?>app/assets/images/pdf.png" alt="pdf placeholder"/>
                                    <p class="fw-bold pt-3"><?= substr($sub['privacy'], 21)?></p>
                                    <a class="pt-2" href="<?=ROOT.'app/assets/documents/'.$sub['privacy']?>" download>Scarica <?=$sub['privacy']?></a>
                                </div>
                                <?php endif;?>
                        <?php endif;?>
                        <div class="d-flex gap-8 my-8">
                            <form id="fileBoxForm<?='-'.$key?>" class="d-flex flex-column w-100">
                                <div class="w-100 text-start">
                                    <p class="form-label">Carica un file</p>
                                </div>
                                <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center gap-4" data-kt-image-input="true">
                                    <label title="Scegli un file" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                        <input id="fileInput<?='-'.$key?>" name="file" type="file" accept=".pdf"/>
                                    </label>
                                    <img id="file<?='-'.$key?>" src="" class="h-75"/>
                                    <p id="fileName<?='-'.$key?>" class=""></p>
                                </div>
                                <div class="w-50 mt-4 mx-auto">
                                    <button type="submit" class="btn btn-primary d-none w-100" id="file-upload-button<?='-'.$key?>">Carica il file</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="card-header py-7 d-flex flex-row justify-content-between align-items-center">
                <!--begin::Title-->
                <h3 class="card-title">
                    <span class="card-label fw-bold text-gray-800">Tesseramento</span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">

                <p>Seleziona l'anno per cui procedere con il tesseramento</p>
                        <div class="row pt-4 pb-6">
                            <select class="form-select" data-control="select2" id="sub-year">
                                <option value="<?=$thisYear?>">
                                    <?=$thisYear?>
                                </option>
                                <option value="<?=$nextYear?>">
                                    <?=$nextYear?>
                                </option>
                            </select>
                        </div>
                <?php foreach($data['subs'] as $key => $sub) : ?>
                    <form id="subs-form<?='-'.$key?>" class="<?php echo($key == $nextYear) ? 'd-none' : ''?>">
                        <div class="row pt-6">
                            <button data-recorded="<?= $sub['approval']?>" type='button' class="<?php echo($key == $thisYear) ? 'sub-button' : 'up-button'?> btn p-6 d-flex gap-6 align-items-center col-8 border border-surface
                            <?php
                                switch($sub['approval']) {
                                    case NULL:
                                        echo 'btn-light';
                                        break;
                                    case 1:
                                        echo 'btn-success active';
                                        break;
                                    default:
                                        echo 'btn-color-muted bg-surface';
                                        break;
                                }

                            ?>" value="1">
                                <div class="d-flex align-items-center gap-2 min-w-100px">
                                    <i class="ki-outline ki-check fs-1"></i>Sì
                                </div>
                                <div>
                                    <p class="w-80 text-start mb-0">I documenti e il versamento SONO IDONEI per l’anno corrente di tesseramento</p>
                                </div>
                            </button>
                        </div>
                        <div class="row pt-3">
                            <button data-recorded="<?= $sub['approval']?>" type='button' class="<?php echo($key == $thisYear) ? 'sub-button' : 'up-button'?> btn p-6 d-flex gap-6 align-items-center col-8 border border-surface
                            <?php
                            switch($sub['approval']) {
                                case NULL:
                                    echo 'btn-light';
                                    break;
                                case 0:
                                    echo 'btn-success active';
                                    break;
                                default:
                                    echo 'btn-color-muted bg-surface';
                                    break;
                            }

                            ?>" value="0">
                                <div class="d-flex align-items-center gap-2 min-w-100px">
                                    <i class="ki-outline ki-cross fs-1"></i>No
                                </div>
                                <div>
                                    <p class="w-80 text-start mb-0">I documenti e il versamento NON SONO IDONEI per l’anno corrente di tesseramento</p>
                                </div>
                            </button>
                        </div>
                        <div class="row pt-3">
                            <button data-recorded="<?= $sub['approval']?>" type='button' class="<?php echo($key == $thisYear) ? 'sub-button' : 'up-button'?> btn p-6 d-flex gap-6 align-items-center col-8 border border-surface
                            <?php
                            switch($sub['approval']) {
                                case NULL:
                                    echo 'btn-light';
                                    break;
                                case 2:
                                    echo 'btn-success active';
                                    break;
                                default:
                                    echo 'btn-color-muted bg-surface';
                                    break;
                            }

                            ?>" value="2">
                                <div class="d-flex align-items-center gap-2 min-w-100px">
                                    <i class="ki-outline ki-information-5 fs-1"></i>In attesa
                                </div>
                                <div>
                                    <p class="w-80 text-start mb-0">I documenti e il versamento sono in attesa di conferma</p>
                                </div>
                            </button>
                        </div>
                        <div class="w-100 text-end">
                            <input id="idTesseramento<?='-'.$key?>" value="<?= $sub['id']?>" hidden readonly/>
                            <button id="save-and-generate<?='-'.$key?>" type="submit" class="btn btn-bg-surface text-gray-600 mt-3" disabled>Salva</button>
                        </div>
                    </form>
                <?php endforeach;?>
            </div>
            </div>
        <form class="card h-md-100 my-8 w-75" id="user-payments">
            <!--begin::Header-->
            <div class="card-header py-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Approvazione contributi</span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_payments_tab">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                            <th class="min-w-125px align-bottom sorting_disabled">Prodotto</th>
                            <th class="min-w-125px sorting_disabled">Periodo di validità</th>
                            <th class="min-w-125px sorting_disabled">Importo</th>
                            <th class="min-w-125px sorting_disabled">Stato</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-bold" id="user-payments-body"></tbody>
                    </table>
                        <div class="w-100 text-end">
                            <button type="submit" class="btn btn-outline border-success text-success mt-3">Salva</button>
                        </div>
                </div>
                <!--end::Table-->
            </div>
            <!--end: Card Body-->
        </form>
        <div class="card h-md-100 my-8 w-75">
            <!--begin::Header-->
            <div class="card-header py-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Storico</span>
                </h3>
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_payments_table">Contributi versati</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_sub_table">Tesseramenti</a>
                    </li>
                </ul>
                <!--end::Title-->
                <!--begin::Toolbar-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="kt_tab_payments_table" role="tabpanel">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_historyPay_tab">
                                <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                                    <th class="min-w-125px sorting_disabled align-bottom">Prodotto</th>
                                    <th class="min-w-125px sorting_disabled">Periodo di validità</th>
                                    <th class="min-w-125px sorting_disabled">Importo</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold"></tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="kt_tab_sub_table" role="tabpanel">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_historySub_tab">
                                <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                                    <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
                                    <th class="min-w-125px sorting_disabled">Data di creazione</th>
                                    <th class="min-w-125px sorting_disabled">Periodo di validità</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Table-->
            </div>
            <!--end: Card Body-->
        </div>
    <?php endif;?>
</div>
<div class="text-start w-75 px-10 m-auto">
    <a href="<?=ROOT . 'utenti'?>">&larr; Torna indietro</a>
</div>
