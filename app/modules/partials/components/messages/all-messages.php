<div class="card h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header py-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Messaggi</span>
        </h3>
        <!--end::Title-->
        <!--begin::Toolbar-->
        <div class="m-0">
            <!--begin::Menu toggle-->
            <button data-bs-toggle="modal" data-bs-target="#message-modal" class="btn btn-light-bg btn-sm"><i class="ki-outline ki-plus-square fs-6"></i> Manda un messaggio</button>
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8" id="kt_datatable_all_mess_tab">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                    <th class="min-w-125px align-bottom sorting_disabled">Nome</th>
                    <th class="min-w-125px sorting_disabled">Messaggi</th>
                    <th class="min-w-125px sorting_disabled">Ultimo messaggio</th>
                    <th class="min-w-125px sorting_disabled">Azioni</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold"></tbody>
            </table>
        </div>
        <!--end::Table-->
    </div>
    <!--end: Card Body-->
</div>
