<div class="card h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header py-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Quiz</span>
        </h3>
        <!--end::Title-->
        <!--begin::Toolbar-->
        <div class="m-0">
            <!--begin::Menu toggle-->
            <?php if($group != 2):?>
                <a href="<?=ROOT . 'app/modules/create-material/new-quiz.php?id='.$_SESSION[SESSIONROOT]['user']?>" class="btn btn-light-bg btn-sm"><i class="ki-outline ki-plus-square fs-6"></i> Aggiungi quiz</a>
            <?php endif;?>
            <a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-6 text-muted me-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Filtra</a>
            <!--end::Menu toggle-->
            <!--begin::Menu 1-->
            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_filter_polls">
                <!--begin::Header-->
                <div class="px-7 py-5">
                    <div class="fs-5 text-gray-900 fw-bold">Opzioni</div>
                </div>
                <!--end::Header-->
                <!--begin::Menu separator-->
                <div class="separator border-gray-200"></div>
                <!--end::Menu separator-->
                <!--begin::Form-->
                <div class="px-7 py-5">
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold" for="polls-lesson-names">Lezione/Evento:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="polls-lesson-names" name="polls-lesson-names" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_polls" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold" for="polls-course-names">Corso:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="polls-course-names" name="polls-course-names" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_polls" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <?php if($_SESSION[SESSIONROOT]['group'] == 1):?>
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label fw-semibold" for="polls-teachers">Insegnanti:</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div>
                                <select id="polls-teachers" name="polls-teachers" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_polls" data-allow-clear="true">
                                    <option></option>
                                </select>
                            </div>
                            <!--end::Input-->
                        </div>
                    <?php endif;?>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <button id="reset-polls" type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                        <button type="button" onclick=reloadTable("#kt_datatable_polls_tab"); class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Filtra</button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Form-->
            </div>
            <!--end::Menu 1-->
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8 pr-4" id="kt_datatable_polls_tab">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                    <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
                    <th class="min-w-125px sorting_disabled">Lezione/Evento</th>
                    <th class="min-w-125px sorting_disabled">Corso</th>
                    <th class="min-w-125px sorting_disabled">Data di creazione</th>
                    <th class="min-w-100px sorting_disabled">Azioni</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold"></tbody>
            </table>
        </div>
        <!--end::Table-->
    </div>
    <!--end: Card Body-->
</div>