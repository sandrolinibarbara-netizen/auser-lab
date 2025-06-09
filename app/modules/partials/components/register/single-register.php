<div class="card card-flush h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header pt-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Registro</span>
        </h3>
        <?php if($_SESSION[SESSIONROOT]['group'] === 3 || $_SESSION[SESSIONROOT]['group'] === 1) :?>
            <div class="m-0">
                <button id="download-reg" class="btn btn-light-bg btn-sm"><i class="ki-outline ki-file-down fs-6"></i> Scarica il registro</button>
            </div>
        <?php endif; ?>
        <!--end::Title-->
        <!--begin::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <!--begin::Table container-->
        <div id="register-card-table" class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 pb-8" id="kt_datatable_reg_tab">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                </thead>
                <tbody class="text-gray-600 fw-bold"></tbody>
            </table>
        </div>
        <div id="no-register-message" class="w-100 d-flex justify-content-center d-none">
            <p class="m-0 bg-danger-subtle border border-danger rounded px-8 py-4 w-400px text-center">A questo corso non risulta iscritto nessuno studente, oppure non sono ancora state inserite lezioni.</p>
        </div>
        <!--end::Table-->
    </div>

    <!--end: Card Body-->
</div>
    <div class="text-start w-100 px-10 py-7 m-auto">
        <a href="<?=ROOT . 'registro'?>">&larr; Torna indietro</a>
    </div>
