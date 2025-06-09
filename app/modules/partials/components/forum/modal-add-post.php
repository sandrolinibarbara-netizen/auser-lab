<form class="modal fade" tabindex="-1" id="post-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                    <h3 class="modal-title">Crea un <?php echo($data[0]['type'] == 'post') ? 'post' : 'messaggio'?></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-post-body" class="modal-body p-4">
                <div class="w-100">
                    <h5 class="mt-2 mb-4">Cosa vuoi scrivere?</h5>
                    <textarea id="modal-post-content" class="form-control form-control-solid min-h-400px"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button value="<?php echo($data[0]['type'] == 'post') ? '1' : '2-'. $data[0]['id_talker'] ?>" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Salva</button>
                </div>

            </div>
        </div>
    </div>
</form>