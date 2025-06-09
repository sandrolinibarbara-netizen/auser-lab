<div class="d-flex flex-column align-items-center">
    <div class="card h-md-100 my-8 w-75">
        <!--begin::Header-->
        <div class="card-header py-7 d-flex flex-row justify-content-between align-items-center">
            <!--begin::Title-->
            <h3 class="card-title">
                <span class="card-label fw-bold text-gray-800" id="permission-title"></span>
            </h3>
            <!--end::Title-->
            <!--begin::Toolbar-->
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <form class="card-body pt-6" id="pagesPermissions">
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_all_pages_tab">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px sorting_disabled"></th>
                        <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
<!--                        <th class="min-w-125px sorting_disabled">Azioni</th>-->
                    </tr>
                    </thead>
                    <tbody id="pagesToSelect" class="text-gray-600 fw-bold"></tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="w-100 text-end">
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </div>
        </form>
        <!--end: Card Body-->
    </div>
</div>
<div class="text-start w-75 px-10 m-auto">
    <a href="<?=ROOT . 'permessi'?>">&larr; Torna indietro</a>
</div>

