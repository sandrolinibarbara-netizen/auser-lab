<form class="modal fade" tabindex="-1" id="add-students-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                    <h3 id="modal-add-students-title" class="modal-title"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-add-students-body" class="modal-body p-4">
                <h5 id="modal-add-students-info" class="mt-4 mb-7"></h5>
                <div class="w-100">
                    <label id="modal-add-students-label" for="students-selection" class="mt-2 fw-semibold form-label"></label>
                    <select id='students-selection' class="form-select" data-control="select2" multiple="multiple" name="students" data-placeholder="Seleziona gli studenti">
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                <button id="add-students-button" type="submit" class="btn btn-primary">Invita</button>
            </div>
        </div>
    </div>
</form>