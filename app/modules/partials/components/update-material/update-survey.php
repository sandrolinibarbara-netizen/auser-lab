<div id="question-types" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-width="600px">
    <div class="p-8 scroll">
        <h4>Scegli il tipo di domanda</h4>
        <div class="d-flex align-items-center flex-wrap gap-2 mt-8">
            <div id="question-single" class="card p-6 bg-hover-light-bg text-start w-100" data-value="2">
                <h5>Valutazione</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-notification-status"></i>
                    <p class="mb-0">L'utente può esprimere il suo parere selezionando un punteggio da 1 a 5.</p>
                </div>
            </div>
            <div id="question-open" class="card p-6 bg-hover-light-bg text-start w-100" data-value="1">
                <h5>Opinione</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-notepad-edit"></i>
                    <p class="mb-0">L'utente può esprimere il suo parere scrivendo nel box di testo.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="new-survey">
    <form method="post" class="card w-75 my-8 mx-auto" id="new-survey-info">
            <div class="card-header d-flex align-items-center justify-content-between p-7" id="new-survey-header">
                <h3 class="mb-0">Informazioni generali</h3>
            </div>
                <div id="new-survey-body" class="card-body">
                    <label class="form-label" for="survey-title">Inserisci il titolo del sondaggio <span><em>(obbligatorio)</em></span></label>
                    <input id="survey-title" name="survey-title" class='form-control form-control-solid' value="<?= $data[0]['nomeSurvey']?>"/>

                    <label class="form-label mt-4" for="survey-description">Inserisci la descrizione del sondaggio</label>
                    <textarea id="survey-description" name="survey-description" class='form-control form-control-solid'><?= $data[0]['descrizioneSurvey']?></textarea>
                </div>
                <div class="card-footer">
                    <div class="w-100 d-flex gap-4 justify-content-end pe-7">
                        <button class="btn btn-primary" type="submit" id="survey-info-saveButton"><?php echo(isset($_GET['type']) && $_GET['type'] == 1) ? 'Aggiorna' : 'Salva'?></button>
                    </div>
                </div>
    </form>
    <div id="new-survey-questions">
        <?php foreach($data as $key => $question):?>
        <?php if($question['idDomanda'] !== null):?>
            <form method="post" class="accordion w-75 my-8 mx-auto" id="section-<?=$question['ordine']?>">
                <input id="section-<?=$question['ordine']?>-order" class="value" value="<?=$question['idDomanda']?>" hidden/>
                <div id="section-<?=$question['ordine']?>-item" class="accordion-item p-7">
                    <input id="section-<?=$question['ordine']?>-questionType" value="<?=$question['id_tipologia']?>" hidden readonly/>
                    <div id="section-<?=$question['ordine']?>-header" class="accordion-header d-flex align-items-center justify-content-between px-7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section-<?=$question['ordine']?>-body" aria-controls="#section-<?=$question['ordine']?>-body" aria-expanded="true">
                            <h3 id="section-<?=$question['ordine']?>-title" class="mb-0 px-7"><?=$question['ordine'].'. '.($question['id_tipologia'] == 2 ? 'Valutazione' : 'Opinione')?></h3>
                        </button>
                    </div>
                    <div id="section-<?=$question['ordine']?>-body" class="accordion-collapse collapse" aria-labelledby="section-<?=$question['ordine']?>-header" data-bs-parent="#section-<?=$question['ordine']?>">
                        <div id="section-<?=$question['ordine']?>-innerBody" class="accordion-body pb-0">
                            <label for="section-<?=$question['ordine']?>-titleQuestion" class="form-label">Inserisci il titolo della domanda</label>
                            <input id="section-<?=$question['ordine']?>-titleQuestion" class="form-control form-control-solid" value="<?=$question['titoloDomanda']?>"/>

                            <label for="section-<?=$question['ordine']?>-text" class="form-label">Inserisci la descrizione della domanda</label>
                            <textarea id="section-<?=$question['ordine']?>-text" class="form-control form-control-solid"><?=$question['descrizioneDomanda']?></textarea>
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
            <input id="section-<?=$question['ordine'] + 1?>-order" class="value" hidden/>
            <div id="section-<?=$question['ordine'] + 1?>-item" class="accordion-item p-7">
                <div id="section-<?=$question['ordine'] + 1?>-header" class="accordion-header d-flex align-items-center justify-content-between px-7">
                    <h3 id="section-<?=$question['ordine'] + 1?>-title" class="mb-0 px-7"><?=$question['ordine'] + 1?>. Nuova sezione</h3>
                    <i id="section-<?=$question['ordine'] + 1?>-showButton" class="ki-outline fs-1 ki-plus-circle fw-bold text-auser cursor-pointer pe-7" data-kt-drawer-target="#question-types" data-kt-drawer-show="true"></i>
                </div>
            </div>
            </form>
            <?php endif;?>
        <?php endforeach; ?>
    </div>
</div>
<input id="last-survey-added" value="<?= $data[0]['idSurvey']?>" hidden/>
<div class="card w-75 mx-auto mb-8" id="new-survey-buttons">
    <div class=" card-body w-100 d-flex gap-4 justify-content-end">
        <?php if(isset($_GET['type']) && $_GET['type'] == 1):?>
            <button type="button" value="1" class="btn btn-primary" id="survey-publishButton">
                        <span class="indicator-label">
                            Salva
                        </span>
                <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
            </button>
        <?php else: ?>
            <button type="button" value="2" class="btn btn-secondary" id="survey-saveButton">
                        <span class="indicator-label">
                            Salva in bozze
                        </span>
                <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
            </button>
            <button type="button" value="1" class="btn btn-primary" id="survey-publishButton">
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