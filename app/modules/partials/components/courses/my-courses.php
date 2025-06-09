<div class="card h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header py-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">I <?php echo($_SESSION[SESSIONROOT]['group'] != 1 ? 'miei ' : '')?>corsi</span>
        </h3>
        <!--end::Title-->
        <!--begin::Toolbar-->
        <div class="m-0">
            <!--begin::Menu toggle-->
            <a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-6 text-muted me-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Filtra</a>
            <!--end::Menu toggle-->
            <!--begin::Menu 1-->
            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_filter_courses">
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
                        <label class="form-label fw-semibold" for="courses-creation">Data di creazione:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="courses-creation" name="courses-creation" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_courses" data-allow-clear="true">
                                <option></option>
                                <option value="1">Oggi</option>
                                <option value="7">Ultima settimana</option>
                                <option value="30">Ultimo mese</option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold" for="courses-start">Data di inizio:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="courses-start" name="courses-start" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_courses" data-allow-clear="true">
                                <option></option>
                                <option value="1">Oggi</option>
                                <option value="7">Prossima settimana</option>
                                <option value="30">Prossimo mese</option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold" for="courses-end">Data di fine:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="courses-end" name="courses-end" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_courses" data-allow-clear="true">
                                <option></option>
                                <option value="1">Oggi</option>
                                <option value="7">Prossima settimana</option>
                                <option value="30">Prossimo mese</option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <?php if($_SESSION[SESSIONROOT]['group'] == 1):?>
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label fw-semibold" for="courses-teachers">Insegnanti:</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div>
                                <select id="courses-teachers" name="courses-teachers" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_courses" data-allow-clear="true">
                                    <option></option>
                                </select>
                            </div>
                            <!--end::Input-->
                        </div>
                    <?php endif;?>
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <button id="reset-courses" type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                        <button type="button" onclick=reloadTable("#kt_datatable_courses_tab"); class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Filtra</button>
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
            <?php if($group != 2) :?>
                <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8 pr-4" id="kt_datatable_courses_tab">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Nome</th>
                        <th class="min-w-100px">Data creazione</th>
                        <th class="min-w-125px">Data inizio</th>
                        <th class="min-w-125px">Data fine</th>
                        <th class="min-w-100px">Studenti minimi</th>
                        <th class="min-w-100px">Studenti massimi</th>
                        <th class="min-w-125px">Azioni</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-bold"></tbody>
                </table>
            <?php else:?>
                <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8 pr-4" id="kt_datatable_st_courses_tab">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Nome</th>
                        <th class="min-w-125px">Data inizio</th>
                        <th class="min-w-125px">Data fine</th>
                        <th class="min-w-125px">Azioni</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-bold"></tbody>
                </table>
            <?php endif;?>
        </div>
        <!--end::Table-->
    </div>
    <!--end: Card Body-->
</div>
