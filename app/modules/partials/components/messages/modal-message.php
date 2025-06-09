<form class="modal fade" tabindex="-1" id="message-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                <div>
                    <h3 id="modal-forum-title" class="modal-title">Manda un messaggio</h3>
                    <h6 class="mt-1">Ricorda che tutti i campi sono obbligatori</h6>
                </div>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-message-body" class="modal-body p-4">
                <div class="w-100">
                    <h5 class="mt-2 mb-4">Scegli il destinatario del messaggio</h5>
                    <select id='user-selection' class="form-select" data-control="select2" name="users">
                    </select>

                    <h5 class="mt-6 mb-4">Testo del messaggio</h5>
                    <textarea id="modal-message-post" class="form-control form-control-solid min-h-200px"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button id="message-button" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Invia</button>
                </div>

            </div>
        </div>
    </div>
</form>