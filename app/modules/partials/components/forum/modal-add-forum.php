<form class="modal fade" tabindex="-1" id="forum-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">
                <div>
                    <h3 id="modal-forum-title" class="modal-title">Crea un forum</h3>
                    <h6 class="mt-1">Ricorda che tutti i campi sono obbligatori</h6>
                </div>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-forum-body" class="modal-body p-4">
                <div class="w-100">
                    <h5 id="modal-forum-course" class="mt-2">Scegli il corso di cui creare il forum</h5>
                    <select id='single-course-selection' class="form-select" data-control="select2" name="courses" data-placeholder="Seleziona un corso">
                        <option value="-">Scegli un corso</option>
                    </select>
                </div>
                <div class="w-100 mt-12 d-none" id='user-selection-div'>
                    <h5 id="modal-forum-users" class="mt-2">Scegli, tra gli iscritti al corso, chi potrà partecipare al forum</h5>
                    <select id='user-selection' class="form-select" data-control="select2" multiple="multiple" name="users" data-placeholder="Seleziona gli studenti">
                        <option value="0">Tutti</option>
                    </select>
                </div>
                <div class="w-100 mt-12 d-none" id='brief-intro-div'>
                    <h5 id="modal-forum-intro" class="mt-2 mb-4">Il primo thread del forum serve a presentare il corso e a fare le presentazioni. Scrivi di seguito una breve introduzione e saluta i nuovi studenti!</h5>
                    <textarea id="brief-intro" class="form-control form-control-solid min-h-200px"></textarea>
                </div>
                <div class="w-100 mt-12 mb-8 d-none form-check form-check-custom form-check-solid d-flex flex-column align-items-start" id='answers'>
                    <h5 id="modal-forum-intro" class="mt-2 mb-4">Gli studenti sono autorizzati a creare discussioni e a inviare risposte?</h5>
                    <div class="w-100 mt-4 d-flex align-items-center justify-content-center gap-12">
                        <div>
                            <label class="form-check-label text-black" for="answers-yes"> Sì</label>
                            <input class="form-check-input" type="radio" value="1" name="answers-poss" id="answers-yes"/>
                        </div>
                        <div>
                            <label class="form-check-label text-black" for="answers-no"> No</label>
                            <input class="form-check-input" type="radio" value="0" name="answers-poss" id="answers-no"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>

            </div>
        </div>
    </div>
</form>