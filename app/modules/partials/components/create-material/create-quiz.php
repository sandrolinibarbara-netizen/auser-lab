<div id="question-types" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-width="600px">
    <div class="p-8 scroll">
        <h4>Scegli il tipo di domanda...</h4>
        <div class="d-flex align-items-center flex-wrap gap-2 mt-8">
            <div id="question-multiple" class="card p-6 bg-hover-light-bg text-start" data-value="3">
               <h5>Domanda a scelta multipla</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-questionnaire-tablet"></i>
                    <p class="mb-0">Le risposte corrette sono una o più. Si consiglia di mettere almeno due risposte corretta</p>
                </div>
            </div>
            <div id="question-single" class="card p-6 bg-hover-light-bg text-start" data-value="2">
                <h5>Domanda a scelta singola</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-notification-status"></i>
                    <p class="mb-0">La risposta corretta è una soltanto. Se tutte le risposte tra cui scegliere sono volutamente fuorvianti e quindi errate, si consiglia di aggiungere un'ulteriore risposta con la dicitura "Nessuna delle precedenti"</p>
                </div>
            </div>
            <div id="question-open" class="card p-6 bg-hover-light-bg text-start" data-value="1">
                <h5>Domanda a risposta aperta</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-notepad-edit"></i>
                    <p class="mb-0">Lo studente ha a disposizione un numero di righe a tua scelta per scrivere la risposta</p>
                </div>
            </div>
        </div>
        <h4 class="mt-8">...o il tipo di contenuto</h4>
        <div class="d-flex align-items-center flex-wrap gap-2 mt-8">
            <div id="slide-text" class="card p-6 bg-hover-light-bg text-start" data-value="5">
                <h5>Testo</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-notepad"></i>
                    <p class="mb-0">Slide di testo che illustra il tema e i passaggi successivi del quiz. Possono esserne presenti più di uno all'interno del medesimo quiz se questo è suddiviso in sezioni.</p>
                </div>
            </div>
            <div id="slide-link" class="card p-6 bg-hover-light-bg text-start" data-value="4">
                <h5>Link</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-fasten"></i>
                    <p class="mb-0">Slide che contiene un link a un contenuto (un'immagine, un video, un articolo) da consultare per poter rispondere alla domanda successiva</p>
                </div>
            </div>
            <div id="slide-pdf" class="card p-6 bg-hover-light-bg text-start" data-value="6">
                <h5>PDF</h5>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <i class="ki-outline fs-1 ki-file"></i>
                    <p class="mb-0">Slide che contiene un file in formato PDF (un'immagine, un video, un articolo) da visualizzare per poter rispondere alla domanda successiva</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="new-poll">
    <form method="post" class="card w-75 my-8 mx-auto" id="new-poll-info">
            <div class="class-header d-flex align-items-center justify-content-between p-7" id="new-poll-header">
                <h3 class="mb-0">Informazioni generali</h3>
            </div>
            <div id="new-poll-body" class="card-body">
                <label class="form-label" for="poll-title">Inserisci il titolo del quiz <span><em>(obbligatorio)</em></span></label>
                <input id="poll-title" name="poll-title" class='form-control form-control-solid'/>

                <label class="form-label mt-4" for="poll-description">Inserisci la descrizione del quiz</label>
                <textarea id="poll-description" name="poll-description" class='form-control form-control-solid'></textarea>
            </div>
            <div class="card-footer">
                <div class="w-100 d-flex gap-4 justify-content-end pe-7">
                    <button class="btn btn-primary" type="submit" id="poll-info-saveButton">Salva</button>
                </div>
            </div>
    </form>
    <div id="new-poll-questions">
    </div>
</div>
<input id="last-poll-added" value="" hidden/>

<div class="card w-75 mx-auto mb-8" id="new-poll-buttons">
        <div class=" card-body w-100 d-flex gap-4 justify-content-end">
            <button class="btn btn-secondary" type="button" id="poll-saveButton" value="2">Salva bozza</button>
            <button class="btn btn-primary" type="button" id="poll-publishButton" value="1">Pubblica</button>
        </div>
    </div>
<div class="text-start w-75 px-7 m-auto">
    <a href="<?=ROOT . 'materiali'?>">&larr; Torna indietro</a>
</div>