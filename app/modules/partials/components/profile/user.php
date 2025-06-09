
<div class="d-flex flex-column align-items-center">
<div class="card h-md-100 my-8 w-75">
    <!--begin::Header-->
    <div class="card-header py-7 d-flex flex-row justify-content-between align-items-center">
        <!--begin::Title-->
        <h3 class="card-title">
            <span class="card-label fw-bold text-gray-800">Il tuo profilo</span>
        </h3>
        <a class="text-decoration-none" href="<?= ROOT. 'profilo?user=profile&id=' . $_SESSION[SESSIONROOT]['user'].'&update=1'?>"><i class="ki-outline ki-message-edit fs-2 p-2 bg-light-bg text-auser rounded"></i></a>
        <!--end::Title-->
        <!--begin::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <div>
            <p class="form-label">Avatar</p>
            <div class="image-input image-input-circle my-4" data-kt-image-input="true" style="background-color: #D2E5D3">
                <!--begin::Image preview wrapper-->
                <div class="image-input-wrapper w-150px h-150px" style="background-image: url('<?php echo (explode(':', $data[0]['immagine'])[0] === 'http' || explode(':', $data[0]['immagine'])[0] === 'https' ? $data[0]['immagine'] : ROOT . 'app/assets/uploaded-files/users-images/' . $data[0]['immagine'])?>')">
                </div>
            </div>
        </div>
        <div class="row mb-4">
        <div class="col-6">
            <label class="form-label" for="user-name">Nome</label>
            <input class="form-control form-control-solid" id="user-name" value="<?=$data[0]['nome']?>" disabled/>
        </div>
        <div class="col-6">
            <label class="form-label" for="user-surname">Cognome</label>
            <input class="form-control form-control-solid" id="user-surname" value="<?=$data[0]['cognome']?>" disabled/>
        </div>
        </div>
        <div class="row mb-4">
            <div class="col-6">
                <label class="form-label" for="user-email">Email</label>
                <input class="form-control form-control-solid" id="user-email" value="<?=$data[0]['email']?>" disabled/>
            </div>
            <div class="col-6">
                <label class="form-label" for="user-birthdate">Data di nascita</label>
                <input class="form-control form-control-solid" id="user-birthdate" value="<?=$data[0]['data_nascita']?>" disabled/>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-6">
                <label class="form-label" for="user-phone">Telefono</label>
                <input class="form-control form-control-solid" id="user-phone" value="<?=$data[0]['telefono']?>" disabled/>
            </div>
            <div class="col-6">
                <label class="form-label" for="user-address">Indirizzo</label>
                <input class="form-control form-control-solid" id="user-address" value="<?=$data[0]['indirizzo']?>" disabled/>
            </div>
        </div>
        <div class="row">
            <div>
                <label class="form-label" for="user-job">Situazione attuale</label>
                <input class="form-control form-control-solid" id="user-job" value="<?=$data[0]['impiego'] ?? '-'?>" disabled/>
            </div>
        </div>
    </div>
    <!--end: Card Body-->
</div>
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
        <div class="mx-4 mt-8 w-100 d-flex justify-content-center gap-4">
            <a href="<?= ROOT . 'app/assets/documents/User7_CI.pdf'?>" download="User7_CI.pdf" id='privacy-policy' class="btn btn-light-bg btn-sm"><i class="ki-outline ki-file-down fs-6"></i> Privacy policy</a>
            <a href="<?= ROOT . 'app/assets/documents/User7_CI.pdf'?>" download="User7_CI.pdf" id='liberatoria' class="btn btn-light-bg btn-sm"><i class="ki-outline ki-file-down fs-6"></i> Liberatoria per utenti minorenni</a>
        </div>
        <div class="w-100 mt-8 text-center">
            <p class="mb-0"><em>Inviare i documenti firmati a test@gmail.com</em></p>
        </div>
        <div class="card-body pt-6">
        </div>

    </div>
<?php endif; ?>
</div>
