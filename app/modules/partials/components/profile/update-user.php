<div class="d-flex flex-column align-items-center">
    <form id="update-user" class="card h-md-100 my-8 w-75">
        <!--begin::Header-->
        <div class="card-header py-7 d-flex flex-row justify-content-between align-items-center">
            <!--begin::Title-->
            <h3 class="card-title">
                <span class="card-label fw-bold text-gray-800">Modifica il tuo profilo</span>
            </h3>
            <!--end::Title-->
            <!--begin::Toolbar-->
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body pt-6">
            <div class="mb-8">
                <p class="form-label">Avatar</p>
                <div id="avatar-pic" class="d-flex flex-column w-100">
                    <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center" data-kt-image-input="true">
                        <label title="Scegli un'immagine" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                            <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                            <input id="picInput" name="pic" type="file" accept=".png, .jpg, .jpeg"/>
                        </label>
                        <img id="pic" src="<?php echo (explode(':', $data[0]['immagine'])[0] === 'http' || explode(':', $data[0]['immagine'])[0] === 'https' ? $data[0]['immagine'] : ROOT . 'app/assets/uploaded-files/users-images/' . $data[0]['immagine'])?>" class="h-75"/>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
            <div class="fv-row col-6">
                <label class="form-label" for="user-name">Nome</label>
                <input class="form-control form-control-solid" id="user-name" name="nome" value="<?=$data[0]['nome']?>"/>
            </div>
            <div class="fv-row col-6">
                <label class="form-label" for="user-surname">Cognome</label>
                <input class="form-control form-control-solid" id="user-surname" name="cognome" value="<?=$data[0]['cognome']?>"/>
            </div>
            </div>
            <div class="row mb-4">
                <div class="fv-row col-6">
                    <label class="form-label" for="user-email">Email</label>
                    <input class="form-control form-control-solid" id="user-email" name="email" value="<?=$data[0]['email']?>"/>
                </div>
                <div class="fv-row col-6">
                    <label class="form-label" for="user-birthdate">Data di nascita</label>
                    <input class="form-control form-control-solid" id="user-birthdate" name="birth" value="<?=$data[0]['data_nascita']?>"/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label" for="user-phone">Telefono</label>
                    <input class="form-control form-control-solid" id="user-phone" value="<?=$data[0]['telefono']?>"/>
                </div>
                <div class="col-6">
                    <label class="form-label" for="user-address">Indirizzo</label>
                    <input class="form-control form-control-solid" id="user-address" value="<?=$data[0]['indirizzo']?>"/>
                </div>
            </div>
            <div class="row">
                <div>
                    <label class="form-label" for="user-job">Situazione attuale</label>
                    <select class="form-select form-select-solid" data-hide-search="true" id="user-job" name="user-job" data-control="select2" data-placeholder="Selezione un'opzione">
                        <option></option>
                        <option value="1" <?= $data[0]['jobId'] == 1 ? 'selected' : '' ?> >Studente/studentessa</option>
                        <option value="2" <?= $data[0]['jobId'] == 2 ? 'selected' : '' ?> >Lavoratore/lavoratrice</option>
                        <option value="3" <?= $data[0]['jobId'] == 3 ? 'selected' : '' ?> >Inoccupato/inoccupata</option>
                        <option value="4" <?= $data[0]['jobId'] == 4 ? 'selected' : '' ?> >Pensionato/pensionata</option>
                    </select>
                </div>
            </div>
            <div class="fv-row row mt-6 mb-4 form-check form-check-custom form-check-solid">
                    <h6 class="mb-6"><?php echo($_GET['id'] == $_SESSION[SESSIONROOT]['user']) ? 'Sei' : 'L\'utente è'?> maggiorenne? <span><em>(obbligatorio)</em></span></h6>
                <div class="col-3">
                    <label class="form-label mx-2" for="underage-yes">Sì</label>
                    <input class="form-check-input" type="radio" value='0' name="underage" id="underage-yes" <?php echo($data[0]['minorenne'] === 0) ? 'checked' :'' ?> required/>
                </div>
                <div class="col-3">
                    <label class="form-label mx-2" for="underage-no">No</label>
                    <input class="form-check-input" type="radio" value='1' name="underage" id="underage-no" <?php echo($data[0]['minorenne'] === 1) ? 'checked' :'' ?> required/>
                </div>
            </div>
        </div>
        <!--end: Card Body-->
        <div class="card-footer d-flex justify-content-end align-items-center gap-4">
            <div id="email-alert" class="d-none">
                <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-column align-items-start">
                        <span class="text-start text-gray-600">Questo indirizzo email è già stato registrato</span>
                    </div>
                </div>
            </div>
            <div id="date-alert" class="d-none">
                <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-column align-items-start">
                        <span class="text-start text-gray-600">La data di nascita non può essere coincidente o successiva alla data di oggi.</span>
                    </div>
                </div>
            </div>
            <div id="age-mismatch-alert" class="d-none">
                <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-column align-items-start">
                        <span class="text-start text-gray-600">La data di nascita non coincide con la dichiarazione di maggiore età.</span>
                    </div>
                </div>
            </div>
            <div id="error-box" class="d-none">
                <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-column align-items-start">
                        <span id="name-error-message" class="text-start text-gray-600"></span>
                    </div>
                </div>
            </div>
<!--            <div id="name-error-message" class="text-danger px-12 pb-4"></div>-->
            <div class="">
                <button id="update-user-button" type="submit" value="<?=$_GET['id']?>" class="btn btn-primary" data-sameuser="<?php echo($_GET['id'] == $_SESSION[SESSIONROOT]['user']) ? '1' : '0'?>" >Salva</button>
            </div>
        </div>
    </form>
<?php if($_SESSION[SESSIONROOT]['group'] === 2):?>
<div class="card card-flush h-md-100 my-8 w-75">
    <!--begin::Header-->
    <div class="card-header pt-7 d-flex flex-column">
        <!--begin::Title-->
        <h3 class="card-title">
            <span class="card-label fw-bold text-gray-800">Il tuo tesseramento</span>
        </h3>
        <!--end::Title-->
        <!--begin::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <div class="row mb-4">
            <div class="col-6">
                <h4>1.</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus eu lacus vitae nulla volutpat laoreet id vitae quam. Sed eleifend egestas luctus.
                    Phasellus lectus ex, accumsan ac scelerisque vel, porta dictum tellus.
                    Donec tincidunt metus ut lectus elementum, vitae accumsan diam blandit.</p>
            </div>
            <div class="col-6">
                <h4>2.</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus eu lacus vitae nulla volutpat laoreet id vitae quam. Sed eleifend egestas luctus.
                    Phasellus lectus ex, accumsan ac scelerisque vel, porta dictum tellus.
                    Donec tincidunt metus ut lectus elementum, vitae accumsan diam blandit.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h4>3.</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus eu lacus vitae nulla volutpat laoreet id vitae quam. Sed eleifend egestas luctus.
                    Phasellus lectus ex, accumsan ac scelerisque vel, porta dictum tellus.
                    Donec tincidunt metus ut lectus elementum, vitae accumsan diam blandit.</p>
            </div>
            <div class="col-6">
                <h4>4.</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus eu lacus vitae nulla volutpat laoreet id vitae quam. Sed eleifend egestas luctus.
                    Phasellus lectus ex, accumsan ac scelerisque vel, porta dictum tellus.
                    Donec tincidunt metus ut lectus elementum, vitae accumsan diam blandit.</p>
            </div>
        </div>
    </div>
    <!--end: Card Body-->
</div>
    <div class="card h-md-100 my-8 w-75">
        <!--begin::Header-->
        <div class="card-header py-7 d-flex flex-row justify-content-between align-items-center">
            <!--begin::Title-->
            <h3 class="card-title">
                <span class="card-label fw-bold text-gray-800">File da scaricare</span>
            </h3>
        </div>
        <div class="row mx-4 mt-8 w-100">
            <div class="col-4 text-end">
                <a href="<?= ROOT . 'app/assets/documents/User7_CI.pdf'?>" download="User7_CI.pdf" id='privacy-policy' class="btn btn-light-bg btn-sm"><i class="ki-outline ki-file-down fs-6"></i> Privacy policy</a>
            </div>
            <div class="col-8 text-start">
                <a href="<?= ROOT . 'app/assets/documents/User7_CI.pdf'?>" download="User7_CI.pdf" id='liberatoria' class="btn btn-light-bg btn-sm"><i class="ki-outline ki-file-down fs-6"></i> Liberatoria per utenti minorenni</a>
            </div>
        </div>
        <div class="card-body pt-6">
        </div>

    </div>
<?php endif; ?>
</div>
<div class="text-start w-75 px-10 m-auto">
    <a href="<?php
    if($_GET['id'] == $_SESSION[SESSIONROOT]['user']) {
        echo ROOT . 'profilo?user=profile&id=' . explode("=", explode('&', $_SERVER["QUERY_STRING"])[1])[1];
    } else {
        echo ROOT . 'utente?utente=infos&id='. explode("=", explode('&', $_SERVER["QUERY_STRING"])[1])[1];
    }
    ?>">&larr; Torna indietro</a>
</div>
