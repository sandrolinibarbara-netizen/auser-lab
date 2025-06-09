<form class="modal fade" tabindex="-1" id="user-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                    <h3 id="modal-user-title" class="modal-title">Iscrizione</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-user-body" class="modal-body p-4">
                <div class="w-100">
                    <h5 id="modal-user-description" class="mt-2">Iscrivi <?=$data[0]['nome']. ' '. $data[0]['cognome']?> a un corso</h5>
                    <select id='course-selection' class="form-select" data-control="select2" multiple="multiple" name="courses">
                        <?php foreach ($data['availableCourses'] as $course) :?>
                            <option value="<?=$course['id']?>"><?=$course['nome']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Salva</button>
                </div>

            </div>
        </div>
    </div>
</form>