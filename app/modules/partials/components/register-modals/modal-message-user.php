<form class="modal fade" tabindex="-1" id="message-user-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                    <h3 class="modal-title">Manda un messaggio</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-message-user-body" class="modal-body p-4">
                <div class="w-100">
                    <h5 class="mt-2 mb-4">Testo del messaggio</h5>
                    <textarea id="modal-message-user-post" class="form-control form-control-solid min-h-200px"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button id="message-user-button" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Invia</button>
                </div>

            </div>
        </div>
    </div>
</form>