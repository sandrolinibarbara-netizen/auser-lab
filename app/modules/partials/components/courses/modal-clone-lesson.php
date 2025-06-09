<form class="modal fade" tabindex="-1" id="clone-lesson-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                    <h3 id="modal-clone-lesson-title" class="modal-title">Opzioni di duplicazione</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-clone-lesson-body" class="modal-body p-4">
                <div class="w-100">
                    <h5 id="modal-clone-lesson-description" class="mt-2">Di quale corso farà parte la nuova lezione?</h5>
                    <div class="d-flex mt-8 mb-4 gap-6">
                        <div class="d-flex gap-2">
                            <label class="form-label" for="same-course">Questo corso</label>
                            <input id="same-course" name="course" type="radio" class="form-check-custom form-check-input"/>
                        </div>
                        <div class="d-flex gap-2">
                            <label class="form-label" for="another-course">Un altro corso</label>
                            <input id="another-course" value="0" name="course" type="radio" class="form-check-custom form-check-input"/>
                        </div>
                    </div>
                    <div class="d-none" id="select-box">
                        <select id='course-selection' class="form-select" data-control="select2">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button id="clone-lesson-button" type="submit" class="btn btn-primary">Duplica</button>
                </div>

            </div>
        </div>
    </div>
</form>