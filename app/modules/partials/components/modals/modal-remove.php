<form class="modal fade" tabindex="-1" id="modal-remove">
    <div class="modal-dialog mw-600px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                <h3 id="modal-remove-title" class="modal-title">Rimuovi <?= $element?></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-remove-body" class="modal-body p-4">
                <div class="w-100 text-center">
                    <p id="modal-remove-description" class="mt-4 fs-3 fw-semibold">Vuoi davvero rimuovere questo <?= $element?>?</p>
                    <div class="pt-6 pb-4 d-flex w-100 align-items-center justify-content-center gap-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">No</button>
                        <button id="remove-button" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Sì</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>