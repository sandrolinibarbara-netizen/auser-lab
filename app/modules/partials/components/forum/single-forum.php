<div class="card card-flush h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header pt-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800" id="forum_title"></span>
        </h3>
        <!--end::Title-->
        <div class="m-0">
            <!--begin::Menu toggle-->
            <button id="add-thread-button" data-bs-toggle="modal" data-bs-target="#thread-modal" class="btn btn-light-bg btn-sm"><i class="ki-outline ki-plus-square fs-6"></i> Nuova discussione</button>
            <!--end::Menu toggle-->
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8" id="kt_datatable_forum_tab">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">

                    <th class="min-w-125px align-bottom sorting_disabled">Discussioni</th>
                    <th class="min-w-125px sorting_disabled">Data creazione</th>
                    <th class="min-w-125px sorting_disabled">Post</th>
                    <th class="min-w-125px sorting_disabled">Ultimo post</th>
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
<div class="text-start w-100 px-10 m-auto">
    <a href="<?=ROOT . 'forum'?>">&larr; Torna indietro</a>
</div>
