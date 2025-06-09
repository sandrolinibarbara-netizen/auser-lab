<div id="new-lecture-note">
    <form method="post" class="card w-75 my-8 mx-auto" id="new-lecture-note-info">
            <div class="card-header d-flex align-items-center justify-content-between p-7" id="new-lecture-note-header">
                <h3 class="mb-0">Informazioni generali</h3>
            </div>
                <div id="new-lecture-note-body" class="card-body">
                    <label class="form-label" for="lecture-note-title">Inserisci il titolo della dispensa <span><em>(obbligatorio)</em></span></label>
                    <input id="lecture-note-title" name="lecture-note-title" class='form-control form-control-solid' value="<?= $data[0]['nomeLectureNote']?>"/>

                    <label class="form-label mt-4" for="lecture-note-description">Inserisci la descrizione della dispensa</label>
                    <textarea id="lecture-note-description" name="lecture-note-description" class='form-control form-control-solid'><?= $data[0]['descrizioneLectureNote']?></textarea>
                </div>
                <div class="card-footer">
                    <div class="w-100 d-flex gap-4 justify-content-end pe-7">
                        <button class="btn btn-primary" type="submit" id="lecture-note-info-saveButton"><?php echo(isset($_GET['type']) && $_GET['type'] == 1) ? 'Aggiorna' : 'Salva'?></button>
                    </div>
                </div>
    </form>
    <div id="new-lecture-note-questions">
        <?php foreach($data as $key => $question):?>
        <?php if($question['idSezione'] !== null):?>
            <form method="post" class="accordion w-75 my-8 mx-auto" id="section-<?=$question['ordine']?>">
                <input id="section-<?=$question['ordine']?>-order" class="value" value="<?=$question['idSezione']?>" hidden/>
                <div id="section-<?=$question['ordine']?>-item" class="accordion-item p-7">
                    <input id="section-<?=$question['ordine']?>-questionType" value="<?=$question['id_tipologia']?>" hidden readonly/>
                    <div id="section-<?=$question['ordine']?>-header" class="accordion-header d-flex align-items-center justify-content-between px-7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section-<?=$question['ordine']?>-body" aria-controls="#section-<?=$question['ordine']?>-body" aria-expanded="true">
                            <h3 id="section-<?=$question['ordine']?>-title" class="mb-0 px-7"><?=$question['ordine'].'. Sezione'?></h3>
                        </button>
                    </div>
                    <div id="section-<?=$question['ordine']?>-body" class="accordion-collapse collapse" aria-labelledby="section-<?=$question['ordine']?>-header" data-bs-parent="#section-<?=$question['ordine']?>">
                        <div id="section-<?=$question['ordine']?>-innerBody" class="accordion-body pb-0">
                            <label for="section-<?=$question['ordine']?>-titleQuestion" class="form-label">Inserisci il titolo della sezione</label>
                            <input id="section-<?=$question['ordine']?>-titleQuestion" class="form-control form-control-solid" value="<?=$question['titoloSezione']?>"/>

                            <label for="section-<?=$question['ordine']?>-text" class="form-label">Inserisci la descrizione della sezione</label>
                            <textarea id="section-<?=$question['ordine']?>-text" class="form-control form-control-solid"><?=$question['descrizioneSezione']?></textarea>

                                <div class="d-flex gap-8 mt-8">
                                    <div id="section-<?=$question['ordine']?>-imageBox" class="d-flex flex-column w-100">
                                        <div class="w-100 text-start">
                                            <p class="form-label">Scegli un file</p>
                                        </div>
                                        <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center gap-4" data-kt-image-input="true">
                                            <label title="Scegli un file" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                                <input id="section-<?=$question['ordine']?>-fileInput" name="section-<?=$question['ordine']?>-file" type="file" accept=".pdf"/>
                                            </label>
                                            <img id="section-<?=$question['ordine']?>-file" src="<?=ROOT.'app/assets/images/pdf.png'?>" class="h-75"/>
                                            <p id="section-<?=$question['ordine']?>-fileName"><?= $question['file']?></p>
                                        </div>
                                    </div>
                                </div>
                            <div class="separator my-7"></div>
                            <div id="section-<?=$question['ordine']?>-footer" class="card-footer text-end">
                                <button id="section-<?=$question['ordine']?>-removeButton" type="submit" class="btn btn-secondary me-4">Rimuovi sezione</button>
                                <button id="section-<?=$question['ordine']?>-updateButton" type="submit" class="btn btn-primary">Salva sezione</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif;?>
            <?php if($key === count($data) - 1):?>
                <form method="post" class="accordion w-75 my-8 mx-auto" id="section-<?=$question['ordine'] + 1?>">
                    <input id="section-<?=$question['ordine'] + 1?>-order" class="value" value="" hidden/>
                    <div id="section-<?=$question['ordine'] + 1?>-item" class="accordion-item p-7">
                        <input id="section-<?=$question['ordine'] + 1?>-questionType" value="<?=$question['id_tipologia']?>" hidden readonly/>
                        <div id="section-<?=$question['ordine'] + 1?>-header" class="accordion-header d-flex align-items-center justify-content-between px-7">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section-<?=$question['ordine'] + 1?>-body" aria-controls="#section-<?=$question['ordine'] + 1?>-body" aria-expanded="true">
                                <h3 id="section-<?=$question['ordine'] + 1?>-title" class="mb-0 px-7"><?=($question['ordine'] + 1).'. Nuova sezione'?></h3>
                            </button>
                        </div>
                        <div id="section-<?=$question['ordine'] + 1?>-body" class="accordion-collapse collapse" aria-labelledby="section-<?=$question['ordine'] + 1?>-header" data-bs-parent="#section-<?=$question['ordine'] + 1?>">
                            <div id="section-<?=$question['ordine'] + 1?>-innerBody" class="accordion-body pb-0">
                                <label for="section-<?=$question['ordine'] + 1?>-titleQuestion" class="form-label">Inserisci il titolo della sezione</label>
                                <input id="section-<?=$question['ordine'] + 1?>-titleQuestion" class="form-control form-control-solid" value=""/>

                                <label for="section-<?=$question['ordine'] + 1?>-text" class="form-label">Inserisci la descrizione della sezione</label>
                                <textarea id="section-<?=$question['ordine'] + 1?>-text" class="form-control form-control-solid"></textarea>

                                <div class="d-flex gap-8 mt-8">
                                    <div id="section-<?=$question['ordine'] + 1?>-imageBox" class="d-flex flex-column w-100">
                                        <div class="w-100 text-start">
                                            <p class="form-label">Scegli un file</p>
                                        </div>
                                        <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center gap-4" data-kt-image-input="true">
                                            <label title="Scegli un file" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                                <input id="section-<?=$question['ordine'] + 1?>-fileInput" name="section-<?=$question['ordine'] + 1?>-file" type="file" accept=".pdf"/>
                                            </label>
                                            <img id="section-<?=$question['ordine'] + 1 ?>-file" src="" class="h-75"/>
                                            <p id="section-<?=$question['ordine'] + 1?>-fileName"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator my-7"></div>
                                <div id="section-<?=$question['ordine'] + 1?>-footer" class="card-footer text-end">
                                    <button id="section-<?=$question['ordine'] + 1?>-updateButton" type="submit" class="btn btn-primary">Salva sezione</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif;?>
        <?php endforeach; ?>
    </div>
</div>
<input id="last-lecture-note-added" value="<?= $data[0]['idLectureNote']?>" hidden/>
<div class="card w-75 mx-auto mb-8" id="new-lecture-note-buttons">
    <div class=" card-body w-100 d-flex gap-4 justify-content-end">
        <?php if(isset($_GET['type']) && $_GET['type'] == 1):?>
            <button type="button" value="1" class="btn btn-primary" id="lecture-note-publishButton">
                        <span class="indicator-label">
                            Salva
                        </span>
                <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
            </button>
        <?php else: ?>
            <button type="button" value="2" class="btn btn-secondary" id="lecture-note-saveButton">
                        <span class="indicator-label">
                            Salva in bozze
                        </span>
                <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
            </button>
            <button type="button" value="1" class="btn btn-primary" id="lecture-note-publishButton">
                        <span class="indicator-label">
                            Pubblica
                        </span>
                <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
            </button>
        <?php endif;?>
    </div>
</div>
<div class="text-start w-75 px-7 m-auto">
    <a href="<?=ROOT . 'materiali'?>">&larr; Torna indietro</a>
</div>