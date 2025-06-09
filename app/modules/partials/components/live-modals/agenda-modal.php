<form class="modal fade rightFont" tabindex="-1" id="agenda-modal" style="z-index: 2000">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">
                <div class="w-100">
                    <h3 id="modal-agenda-title" class="modal-title fs-4"><?=$parsed['nome']?></h3>
                        <h5 id="modal-agenda-description" class="mt-2 fs-5"><?=$parsed['corso']?></h5>
                        <p id="modal-agenda-description" class="mt-2 mb-0 d-flex align-items-center gap-2"><i class="ki-outline ki-calendar-2 fs-3 fw-semibold"></i> <?=$parsed['data'] . ', ' . $parsed['orario']?></p>
                </div>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div id="modal-agenda-body" class="modal-body p-4">
                <p><?=$parsed['descrizione']?></p>
            </div>
        </div>
    </div>
</form>