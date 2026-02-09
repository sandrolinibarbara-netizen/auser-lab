<div class="card h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header py-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Prossimi eventi live</span>
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
            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_filter_events">
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
                        <label class="form-label fw-semibold" for="events-dates">Periodo:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="events-dates" name="events-dates" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_events" data-allow-clear="true">
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
                        <label class="form-label fw-semibold" for="events-hours">Fascia oraria:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="events-hours" name="events-hours" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_events" data-allow-clear="true">
                                <option></option>
                                <option value="morning">08:00 - 12:00</option>
                                <option value="afternoon">12:00 - 16:00</option>
                                <option value="evening">16:00 - 20:00</option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold" for="events-location">Luogo:</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div>
                            <select id="events-location" name="events-location" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Seleziona" data-dropdown-parent="#kt_menu_filter_events" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <button id="reset-events" type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                        <button type="button" onclick=reloadTable("#kt_datatable_events_tab"); class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Filtra</button>
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
            <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8" id="kt_datatable_events_tab">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                    <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
                    <th class="min-w-125px sorting_disabled">Data</th>
                    <th class="min-w-125px sorting_disabled">Orario</th>
                    <th class="min-w-125px sorting_disabled">Posti disponibili</th>
                    <th class="min-w-100px sorting_disabled">Luogo</th>
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