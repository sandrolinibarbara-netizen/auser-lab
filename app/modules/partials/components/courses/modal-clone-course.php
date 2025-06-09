<form class="modal fade" tabindex="-1" id="clone-course-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div class="modal-header p-4 d-flex justify-content-between align-items-start">

                    <h3 id="modal-clone-course-title" class="modal-title">Opzioni di duplicazione</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div id="modal-clone-course-body" class="modal-body p-4">
                <div class="w-100 d-flex flex-column align-items-center justify-content-center gap-4 pb-4">
                        <h3 class="p-4">Che tipo di corso sarà l'elemento che vuoi duplicare?</h3>
                        <div class="w-100 btn p-6 d-flex gap-6 align-items-center col-8 btn-color-muted bg-light-bg text-auser">
                            <input class="form-check form-check-custom form-check-solid" type="radio" name="classe" id="same-class" value="1"/>
                            <div class="min-w-150px">
                                <label for="same-class"> Una classe dello stesso corso</label>
                            </div>
                            <div>
                                <p class="w-80 text-start mb-0">Verranno duplicate anche tutte le lezioni associate al corso di partenza, e tutti i materiali associati a quelle lezioni.<br/>
                                    Se le lezioni della nuova classe saranno in date e orari diversi da quelli del corso di partenza, dovrai modificarle singolarmente.<br/>
                                    Se il nuovo corso che vuoi creare ha solo qualche somiglianza con il corso di partenza, allora non si tratta di una classe di quel corso e ti consigliamo di creare un nuovo corso da zero.
                                </p>
                            </div>
                        </div>

                        <div class="w-100 btn mb-4 p-6 d-flex gap-6 align-items-center col-8 btn-color-muted bg-light-bg text-auser">
                            <input class="form-check form-check-custom form-check-solid" type="radio" name="classe" id="another-class" value="2"/>
                            <div class="min-w-150px">
                                <label for="another-class"> Un altro corso</label>
                            </div>
                            <div>
                                <p class="w-80 text-start mb-0">Verranno duplicati solamente i dati generali del corso. Le lezioni associate a quel corso e tutti i relativi materiali non saranno duplicati.</p>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                    <button id="clone-course-button" type="submit" class="btn btn-primary">Duplica</button>
                </div>

            </div>
        </div>
    </div>
</form>