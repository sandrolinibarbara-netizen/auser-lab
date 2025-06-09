<form class="modal fade" tabindex="-1" id="thread-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                <div>
                    <h3 id="modal-forum-title" class="modal-title">Crea una discussione</h3>
                    <h6 class="mt-1">Ricorda che tutti i campi sono obbligatori</h6>
                </div>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-thread-body" class="modal-body p-4">
                <div class="w-100">
                    <h3 class="mt-2">Titolo della discussione</h3>
                    <input id="modal-thread-title" class="form-control form-control-solid" type="text"/>
                    <h5 class="mt-2">Descrizione della discussione</h5>
                    <textarea id="modal-thread-subtitle" class="form-control form-control-solid" type="text"></textarea>
                </div>
                <div class="w-100 mt-12">
                    <h5 class="mt-2 mb-4">Spiega l'argomento della discussione</h5>
                    <textarea id="modal-thread-post" class="form-control form-control-solid min-h-200px"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Salva</button>
                </div>

            </div>
        </div>
    </div>
</form>