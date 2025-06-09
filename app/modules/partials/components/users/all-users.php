<div class="card h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header p-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Iscritti</span>
        </h3>
        <!--end::Title-->
        <div class="m-0">
            <!--begin::Menu toggle-->
            <a href="<?= ROOT.'new-user'?>" id='add-user-button' class="btn btn-light-bg btn-sm"><i class="ki-outline ki-plus-square fs-6"></i> Aggiungi utente</a>
            <!--end::Menu toggle-->
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8" id="kt_datatable_all_users_tab">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                    <th class="min-w-125px sorting_disabled"></th>
                    <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
                    <th class="min-w-100px sorting_disabled">Data creazione</th>
                    <th class="min-w-125px sorting_disabled">Tesseramento</th>
                    <th class="min-w-125px sorting_disabled">Versamento contributi</th>
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
