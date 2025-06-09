<form class="modal fade" tabindex="-1" id="move-user-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                    <h3 id="modal-move-user-title" class="modal-title">Sposta in un altro corso</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-move-user-body" class="modal-body p-4">
                <div class="w-100">
                    <h5 id="modal-move-user-description" class="mt-2">In quale corso vuoi spostare <span id="move-user"></span>?</h5>
                    <select id='course-selection' class="form-select" data-control="select2" name="course">
                    </select>
                    <div class="mt-4 p-4 rounded bg-danger-subtle">
                        <p><span class="fw-semibold">Attenzione:</span> le presenze registrate fino a questo momento dell'utente che si vuole spostare verranno cancellate.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button id="move-user-button" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Sposta</button>
                </div>

            </div>
        </div>
    </div>
</form>