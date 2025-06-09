<form class="modal fade rightFont" tabindex="-1" id="poll-video-modal" style="z-index: 2000">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">
                <div>
                    <h3 id="modal-poll-video-title fs-4" class="modal-title"></h3>
                    <h5 id="modal-poll-video-description fs-5" class="mt-2"></h5>
                </div>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div id="modal-poll-video-body" class="modal-body p-4">

            </div>

            <div class="modal-footer">
                <div id="error-poll-video" class="d-none text-danger">
                    Non hai risposto a tutte le domande obbligatorie!
                </div>
                <div>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary">Invia</button>
                </div>
            </div>
        </div>
    </div>
</form>