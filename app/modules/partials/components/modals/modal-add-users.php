<form class="modal fade" tabindex="-1" id="modal-add-users">
    <div class="modal-dialog mw-600px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                <h3 id="modal-add-users-title" class="modal-title">Aggiungi utenti non iscritti</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-add-users-body" class="modal-body p-4">
                <div class="w-100 text-center">
                    <p id="modal-add-users-description" class="mt-4 fs-3 fw-semibold">Ci sono <span id="avail-users"></span> posti disponibili.<br/>Quanti posti vuoi riservare?</p>
                    <div class="mb-4 d-flex gap-2 w-100 justify-content-center align-items-center">
                        <input min="0" id="new-users" type="number" class="form-control form-control-solid w-100px"/>
                    </div>
                </div>
            </div>
                    <div class="modal-footer pt-6 pb-4 d-flex w-100 align-items-center gap-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                        <button id="add-users-button" type="submit" class="btn btn-primary">Salva</button>
                    </div>
        </div>
    </div>
</form>