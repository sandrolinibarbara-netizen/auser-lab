<form class="modal fade" tabindex="-1" id="remove-user-modal">
    <div class="modal-dialog mw-600px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                <h3 id="modal-remove-user-title" class="modal-title">Rimuovi dal corso</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-remove-user-body" class="modal-body p-4">
                <div class="w-100 text-center">
                    <p id="modal-remove-user-description" class="mt-2 fs-3 fw-semibold">Vuoi davvero rimuovere <span id="remove-user"></span> dal <span id="remove-course"></span>?</p>
                    <div class="pt-6 pb-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">No</button>
                        <button id="remove-user-button" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Sì</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>