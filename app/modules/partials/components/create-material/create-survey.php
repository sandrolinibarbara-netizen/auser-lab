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
                <input id="survey-title" name="survey-title" class='form-control form-control-solid'/>

                <label class="form-label mt-4" for="survey-description">Inserisci la descrizione del sondaggio</label>
                <textarea id="survey-description" name="survey-description" class='form-control form-control-solid'></textarea>
            </div>
            <div class="card-footer">
                <div class="w-100 d-flex gap-4 justify-content-end pe-7">
                    <button class="btn btn-primary" type="submit" id="survey-info-saveButton">Salva</button>
                </div>
            </div>
    </form>
    <div id="new-survey-questions">
    </div>
</div>
<input id="last-survey-added" value="" hidden/>

<div class="card w-75 mx-auto mb-8" id="new-survey-buttons">
        <div class=" card-body w-100 d-flex gap-4 justify-content-end">
            <button class="btn btn-secondary" type="button" id="survey-saveButton" value="2">Salva bozza</button>
            <button class="btn btn-primary" type="button" id="survey-publishButton" value="1">Pubblica</button>
        </div>
    </div>
<div class="text-start w-75 px-7 m-auto">
    <a href="<?=ROOT . 'materiali'?>">&larr; Torna indietro</a>
</div>