<form class="modal fade" tabindex="-1" id="video-modal">
    <div class="modal-dialog mw-800px">
        <div class="modal-content p-7">
            <div id="modal-video-header" class="modal-header p-4 d-flex justify-content-between align-items-start">
                <div>
                    <h3 id="modal-video-title" class="modal-title">Materiali disponibili</h3>
                    <h5 id="modal-video-description" class="mt-2">Scegli il quiz o la dispensa da aggiungere a questo breakpoint</h5>
                </div>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div id="modal-video-body" class="modal-body p-4">
                <div id="modal-video-breakpoint">
                    <h4>Minutaggio del materiale</h4>
                    <p id="breakpoint-time" class="mb-0 p-4 rounded bg-auser text-white fw-bold fs-1 d-inline-block"></p>
                </div>
                <div id="modal-video-table" class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_materials_video">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-50px sorting_disabled align-bottom"></th>
                            <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                            <th class="min-w-125px sorting_disabled">Categoria</th>
                            <th class="min-w-125px sorting_disabled">Data di creazione</th>
                        </tr>
                        </thead>
                        <tbody id="materialsToSelectVideo" class="text-gray-600 fw-bold">

                        </tbody>
                    </table>
                </div>
            </div>

            <div id="modal-video-footer" class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Chiudi</button>
                <button id="modal-video-save" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Salva</button>
            </div>
        </div>
    </div>
</form>