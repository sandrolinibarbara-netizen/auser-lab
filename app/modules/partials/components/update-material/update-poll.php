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
            <div class="card-header d-flex align-items-center justify-content-between p-7" id="new-poll-header">
                <h3 class="mb-0">Informazioni generali</h3>
            </div>
                <div id="new-poll-body" class="card-body">
                    <label class="form-label" for="poll-title">Inserisci il titolo del quiz <span><em>(obbligatorio)</em></span></label>
                    <input id="poll-title" name="poll-title" class='form-control form-control-solid' value="<?= $data[0]['nomePoll']?>"/>

                    <label class="form-label mt-4" for="poll-description">Inserisci la descrizione del quiz <span><em>(obbligatorio)</em></span></label>
                    <textarea id="poll-description" name="poll-description" class='form-control form-control-solid'><?= $data[0]['descrizionePoll']?></textarea>
                </div>
                <div class="card-footer">
                    <div class="w-100 d-flex gap-4 justify-content-end pe-7">
                        <button class="btn btn-primary" type="submit" id="poll-info-saveButton"><?php echo(isset($_GET['type']) && $_GET['type'] == 1) ? 'Aggiorna' : 'Salva'?></button>
                    </div>
                </div>
    </form>
    <div id="new-poll-questions">
        <?php foreach($data as $key => $question):?>
        <?php if($question['idDomanda'] !== null):?>
            <form method="post" class="accordion w-75 my-8 mx-auto" id="section-<?=$question['ordine']?>">
                <input id="section-<?=$question['ordine']?>-order" class="value" value="<?=$question['idDomanda']?>" hidden/>
                <div id="section-<?=$question['ordine']?>-item" class="accordion-item p-7">
                    <input id="section-<?=$question['ordine']?>-questionType" value="<?=$question['id_tipologia']?>" hidden readonly/>
                    <div id="section-<?=$question['ordine']?>-header" class="accordion-header d-flex align-items-center justify-content-between px-7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section-<?=$question['ordine']?>-body" aria-controls="#section-<?=$question['ordine']?>-body" aria-expanded="true">
                            <h3 id="section-<?=$question['ordine']?>-title" class="mb-0 px-7"><?=$question['ordine'].'. '.$question['tipologia']?></h3>
                        </button>
                    </div>
                    <div id="section-<?=$question['ordine']?>-body" class="accordion-collapse collapse" aria-labelledby="section-<?=$question['ordine']?>-header" data-bs-parent="#section-<?=$question['ordine']?>">
                        <div id="section-<?=$question['ordine']?>-innerBody" class="accordion-body pb-0">
                            <label for="section-<?=$question['ordine']?>-titleQuestion" class="form-label">Inserisci il titolo della domanda</label>
                            <input id="section-<?=$question['ordine']?>-titleQuestion" class="form-control form-control-solid" value="<?=$question['titoloDomanda']?>"/>

                            <label for="section-<?=$question['ordine']?>-text" class="form-label">Inserisci la descrizione della domanda</label>
                            <textarea id="section-<?=$question['ordine']?>-text" class="form-control form-control-solid"><?=$question['descrizioneDomanda']?></textarea>

                            <?php if($question['id_tipologia'] == 4):?>
                                <label for="section-<?=$question['ordine']?>-link" class="form-label">Link</label>
                                <input id="section-<?=$question['ordine']?>-link" class="form-control form-control-solid" value="<?=$question['link']?>"/>
                            <?php endif; ?>

                            <?php if($question['id_tipologia'] == 1 || $question['id_tipologia'] == 2 || $question['id_tipologia'] == 3): ?>
                                <div class="<?php echo($question['id_tipologia'] == 1) ? 'row mt-4' : 'row mt-4 gap-8'?>">
                                    <?php if($question['id_tipologia'] == 1):?>
                                        <div class="col-4">
                                            <label for="section-<?=$question['ordine']?>-minLines" class="form-label">Min caratteri</label>
                                            <input id="section-<?=$question['ordine']?>-minLines" class="form-control form-control-solid" value="<?=$question['min_caratteri']?>"/>
                                        </div>
                                        <div class="col-4">
                                            <label for="section-<?=$question['ordine']?>-maxLines" class="form-label">Max caratteri</label>
                                            <input id="section-<?=$question['ordine']?>-maxLines" class="form-control form-control-solid" value="<?=$question['max_caratteri']?>"/>
                                        </div>
                                    <?php endif;?>
                                    <div class="<?php echo($question['id_tipologia'] == 1) ? 'col-2' : 'col-5'?>">
                                        <label for="section-<?=$question['ordine']?>-points" class="form-label">Punti</label>
                                        <input id="section-<?=$question['ordine']?>-points" class="form-control form-control-solid" value="<?=$question['punti']?>"/>
                                    </div>
                                    <div class="<?php echo($question['id_tipologia'] == 1) ? 'col-2 form-check form-check-custom form-check-solid gap-2 d-flex flex-column justify-content-start align-items-start' : 'col-5 form-check form-check-custom form-check-solid gap-2 pt-2 d-flex flex-column justify-content-start align-items-start'?>">
                                        <label for="section-<?=$question['ordine']?>-mandatory" class="form-label mb-0">Obbligatoria</label>
                                        <input id="section-<?=$question['ordine']?>-mandatory" name="section-<?=$question['ordine']?>-mandatory" type="checkbox" class="form-check-input" <?php echo($question['obbligatoria'] == 1) ? 'checked' : ''?>/>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($question['id_tipologia'] != 4):?>
                                <div class="d-flex gap-8 mt-8">
                                    <?php if($question['id_tipologia'] == 2 || $question['id_tipologia'] == 3):?>
                                        <div id="section-<?=$question['ordine']?>-answersBox" class="d-flex flex-column w-50">
                                            <?php foreach($question['answers'] as $index => $answer): ?>
                                                <li id="section-<?=$question['ordine']?>-answerInfo-<?=$index + 1?>" class="d-flex align-items-center gap-6 w-100 list-group-item">
                                                    <input id="section-<?=$question['ordine']?>-answerId-<?=$index + 1?>" value="<?=$answer['id']?>" type="number" hidden readonly/>
                                                    <div class="pb-4">
                                                        <label for="section-<?=$question['ordine']?>-answerText-<?=$index + 1?>" class="form-label">Risposta <?=$index + 1?></label>
                                                        <input id="section-<?=$question['ordine']?>-answerText-<?=$index + 1?>" class="form-control form-control-solid" value="<?=$answer['risposta']?>"/>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid gap-2 pt-2">
                                                        <label for="section-<?=$question['ordine']?>-answerCorrect-<?=$index + 1?>" class="form-label">Corretta</label>
                                                        <input id="section-<?=$question['ordine']?>-answerCorrect-<?=$index + 1?>" name="<?php echo($question['id_tipologia'] == 3) ? 'section-'. $question['ordine'] .'-answerCorrect-'.($index + 1) : 'section-'. $question['ordine'] .'-answerCorrect'?>" type="<?php echo($question['id_tipologia'] == 2) ? 'radio' : 'checkbox'?>" class="form-check-input" <?php echo($answer['corretta'] == 1) ? 'checked' : ''?>/>
                                                    </div>
                                                    <button id="section-<?=$question['ordine']?>-answerId-<?=$index + 1?>-removeAnswer" type="button" class="btn pb-2 pt-4" value="<?=$answer['id']?>">
                                                        <i class="ki-outline ki-cross fs-1 p-0"></i>
                                                    </button>
                                                </li>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div id="section-<?=$question['ordine']?>-imageBox" class="<?php echo($question['id_tipologia'] == 2 || $question['id_tipologia'] == 3) ? 'd-flex flex-column w-50' : 'd-flex flex-column w-100'?>">
                                        <div class="w-100 text-start">
                                            <p class="form-label"><?php echo($question['id_tipologia'] == 6) ? 'Scegli un file' : 'Se necessaria, scegli un\'immagine'?></p>
                                        </div>
                                        <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center <?php echo($question['id_tipologia'] == 6) ? 'gap-4' : ''?>" data-kt-image-input="true">
                                            <label title="<?php echo($question['id_tipologia'] == 6) ? 'Scegli un file' : 'Scegli un\'immagine'?>" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                                <input id="section-<?=$question['ordine']?>-<?php echo($question['id_tipologia'] == 6) ? 'fileInput' : 'picInput'?>" name="section-<?=$question['ordine']?>-<?php echo($question['id_tipologia'] == 6) ? 'file' : 'pic'?>" type="file" accept="<?php echo($question['id_tipologia'] == 6) ? '.pdf' : '.png, .jpg, .jpeg'?>"/>
                                            </label>
                                            <img id="section-<?=$question['ordine']?>-<?php echo($question['id_tipologia'] == 6) ? 'file' : 'pic'?>" src="<?php
                                                if($question['id_tipologia'] == 6) {
                                                    echo(ROOT.'app/assets/images/pdf.png');
                                                } else if(isset($question['pic'])) {
                                                    echo(ROOT.'app/assets/uploaded-files/polls-images/'.$question['pic']);
                                                }?>" class="h-75"/>
                                            <?php if($question['id_tipologia'] == 6): ?>
                                            <p id="section-<?=$question['ordine']?>-fileName"><?= $question['file']?></p>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if($question['id_tipologia'] == 2 || $question['id_tipologia'] == 3): ?>
                                <button id="section-<?=$question['ordine']?>-addAnswerButton" class="btn btn-secondary" type="button">
                                    Aggiungi una risposta
                                </button>
                            <?php endif; ?>
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
<input id="last-poll-added" value="<?= $data[0]['idPoll']?>" hidden/>
<div class="card w-75 mx-auto mb-8" id="new-poll-buttons">
    <div class=" card-body w-100 d-flex gap-4 justify-content-end">
        <?php if(isset($_GET['type']) && $_GET['type'] == 1):?>
            <button type="button" value="1" class="btn btn-primary" id="poll-publishButton">
                        <span class="indicator-label">
                            Salva
                        </span>
                <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
            </button>
        <?php else: ?>
            <button type="button" value="2" class="btn btn-secondary" id="poll-saveButton">
                        <span class="indicator-label">
                            Salva in bozze
                        </span>
                <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
            </button>
            <button type="button" value="1" class="btn btn-primary" id="poll-publishButton">
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