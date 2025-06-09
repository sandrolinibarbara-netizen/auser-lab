<form class="flex-column card w-75 my-7 mx-auto" data-kt-stepper-element="content" id="new-sponsor-form">
    <div class="card-header py-7">
        <h2 class="mb-0">Inserisci i dati del partner</h2>
    </div>
    <div class="card-body">
        <!--begin::Input group-->
        <div class="row mb-10">
            <h3 class="mb-4">Immagine</h3>
            <div id="sponsor-pic" class="d-flex flex-column w-100">
                <div class="w-100 text-start">
                    <p class="form-label">Scegli un'immagine</p>
                </div>
                <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center" data-kt-image-input="true">
                    <label title="Scegli un'immagine" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                        <input id="picInput" name="pic" type="file" accept=".png, .jpg, .jpeg"/>
                    </label>
                    <img id="pic" src="<?= ROOT.'app/assets/uploaded-files/sponsor-images/'.$parsed[0]['path_immagine']?>" class="h-75"/>
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="row mb-10">
            <h3 class="mb-4"><label for="link-video">Link video</label></h3>
            <div class="col-12">
                <input class="form-control form-control-solid" type="text" id="link-video" name="path-video" value="<?=$parsed[0]['link_video']?>"/>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="row mb-6">
            <h3 class="mb-4">Dati</h3>
            <div class="col-12">
                <label class="form-label ms-2 mb-2" for="nome">Nome <span><em>(obbligatorio)</em></span></label>
                <input class="form-control form-control-solid" type="text" name="nome" id="nome" value="<?=$parsed[0]['nome']?>"/>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-12">
                <label class="form-label ms-2 mb-2" for="lezioni">Sito web</label>
                <input class="form-control form-control-solid" type="text" name="website" id="website" value="<?=$parsed[0]['sito_web']?>"/>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-12">
                <label class="form-label ms-2 mb-2" for="ore">Telefono</label>
                <input class="form-control form-control-solid" type="text" name="phone" id="phone" value="<?=$parsed[0]['telefono']?>"/>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-12">
                <label class="form-label ms-2 mb-2" for="data-inizio">Indirizzo email</label>
                <input class="form-control form-control-solid" name="email" id="email" value="<?=$parsed[0]['mail']?>"/>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-12">
                <label class="form-label ms-2 mb-2" for="descrizione">Descrizione</label>
                <textarea class="form-control form-control-solid" type="text" name="descrizione" id="descrizione"><?=$parsed[0]['descrizione']?></textarea>
            </div>
        </div>

        <!--end::Input group-->
    </div>
    <div class="card-footer">
        <div class="w-100 d-flex align-items-center justify-content-end gap-4">
            <div id="error-name-alert" class="d-none">
                <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-column align-items-start">
                        <span class="text-start text-gray-600">Il nome del partner è obbligatorio</span>
                    </div>
                </div>
            </div>
            <button type="submit" value="2" class="btn btn-primary" data-kt-stepper-action="submit">Salva le modifiche</button>
        </div>
    </div>
</form>
<div class="text-start w-75 px-10 m-auto">
    <a href="<?=ROOT . 'partner'?>">&larr; Torna indietro</a>
</div>