<div class="card card-flush h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header pt-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Registri disponibili</span>
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
            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_filter_all_reg">
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
                        <label class="form-label fw-semibold" for="all-reg-creation">Data di creazione:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="all-reg-creation" name="all-reg-creation" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_all_reg" data-allow-clear="true">
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
                        <label class="form-label fw-semibold" for="all-reg-start">Data di inizio:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="all-reg-start" name="all-reg-start" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_all_reg" data-allow-clear="true">
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
                        <label class="form-label fw-semibold" for="all-reg-end">Data di fine:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="all-reg-end" name="all-reg-end" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_all_reg" data-allow-clear="true">
                                <option></option>
                                <option value="1">Oggi</option>
                                <option value="7">Prossima settimana</option>
                                <option value="30">Prossimo mese</option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <button id="reset-reg" type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                        <button type="button" onclick=reloadTable("#kt_datatable_all_reg_tab"); class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Filtra</button>
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
            <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8" id="kt_datatable_all_reg_tab">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                    <th class="min-w-125px align-bottom">Nome</th>
                    <th class="min-w-100px">Data creazione</th>
                    <th class="min-w-125px">Data inizio</th>
                    <th class="min-w-125px">Data fine</th>
                    <th class="min-w-100px">Azioni</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold"></tbody>
            </table>
        </div>
        <!--end::Table-->
    </div>
    <!--end: Card Body-->
</div>
