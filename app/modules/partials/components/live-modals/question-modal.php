<form class="modal fade rightFont" tabindex="-1" id="question-modal" style="z-index: 2000">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">
                <div class="w-100">
                    <h3 id="modal-question-title fs-4" class="modal-title">Fai una domanda all'insegnante</h3>
                        <h5 id="modal-question-description fs-5" class="mt-2">Il tuo messaggio verrà inviato via mail all'insegnante</h5>
                </div>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div id="modal-question-body" class="modal-body p-4">
                <label for="modal-question-name" class="form-label ms-4">Il tuo nome</label>
                <input id="modal-question-name" name="modal-question-name" class="form-control form-control-solid" type="text" value="<?= $parsed['nome'] . ' ' . $parsed['cognome']?>"/>

                <label for="modal-question-address" class="form-label ms-4">Il tuo indirizzo email</label>
                <input id="modal-question-address" name="modal-question-address" class="form-control form-control-solid" type="text" value="<?= $parsed['userEmail']?>"/>
                <input id="modal-question-teacher" name="modal-question-teacher" class="form-control form-control-solid" type="text" value="<?= $parsed['teacherEmail']?>" hidden/>

                <label for="modal-question-message" class="form-label ms-4">La tua domanda</label>
                <textarea id="modal-question-message" name="modal-question-message" class="form-control form-control-solid min-h-200px"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Invia</button>
            </div>
        </div>
    </div>
</form>