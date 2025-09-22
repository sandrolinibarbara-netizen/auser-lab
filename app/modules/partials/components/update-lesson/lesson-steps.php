<div class="stepper stepper-pills" id="kt_stepper_draft_lesson">
    <div class="stepper-nav flex-center flex-wrap mb-10">
    <!--begin::Step 3-->
        <div class="stepper-item mx-4 my-2" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">1.</span>
                </div>
                <!--begin::Icon-->

                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">
                        Dati lezione
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--end::Step 3-->
        <!--begin::Step 4-->
        <div class="stepper-item mx-4 my-2" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">2.</span>
                </div>
                <!--begin::Icon-->

                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">
                        Genera video
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--end::Step 4-->
        <!--begin::Step 5-->
        <div class="stepper-item mx-4 my-2" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">3.</span>
                </div>
                <!--begin::Icon-->

                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">
                        Scegli i materiali
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--end::Step 5-->
        <div class="stepper-item mx-4 my-2" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">4.</span>
                </div>
                <!--begin::Icon-->

                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">
                        Scegli i compiti
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--begin::Step 6-->
        <div class="stepper-item mx-4 my-2<?php echo($group == 3) ? ' d-none' : ''?>" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">5.</span>
                </div>
                <!--begin::Icon-->

                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">
                        Scegli i partner
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--end::Step 6-->
        <!--begin::Step 7-->
        <div class="stepper-item mx-4 my-2" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number"><?php echo($group == 3) ? '5' : '6'?>.</span>
                </div>
                <!--begin::Icon-->

                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">
                        Riepilogo e pubblicazione
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--end::Step 7-->
    </div>

    <input id="user-permission" value="<?=$group?>" hidden readonly/>
    <form class="form card w-75 mx-auto" novalidate="novalidate" id="kt_stepper_draft_lesson_form">
        <div>
            <!--begin::Step 3-->
            <div class="flex-column current" data-kt-stepper-element="content">
                        <div id="lista-lezioni">
                        <!--begin::Item-->

                            <!--begin::Header-->
                            <div class="card-header py-7">
                                <h2 id='lezione_1_titolo' class="fw-semibold mb-0">Dati lezione</h2>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div id="lezione_1_body" class="card-body fs-6">
                                <div class="row my-6">
                                    <div class="col-6" id="lesson-name">
                                        <label class="form-label ms-2 mb-2" for="nome-lezione">Nome della lezione <span><em>(obbligatorio)</em></span></label>
                                        <input class="form-control form-control-solid" name="nome-lezione" id="nome-lezione"/>
                                    </div>
                                    <div class="col-6" id="lesson-date">
                                        <label class="form-label ms-2 mb-2" for="data-lezione">Data</label>
                                        <input class="form-control form-control-solid" name="data-lezione" id="data-lezione"/>
                                    </div>
                                </div>
                                <div class="row mb-6" id="lesson-times">
                                    <div class="col-6">
                                        <label class="form-label ms-2 mb-2" for="orario-inizio-lezione">Orario di inizio</label>
                                        <input class="form-control form-control-solid" name="orario-inizio-lezione" id="orario-inizio-lezione"/>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label ms-2 mb-2" for="orario-fine-lezione">Orario di fine</label>
                                        <input class="form-control form-control-solid" name="orario-fine-lezione" id="orario-fine-lezione"/>
                                    </div>
                                </div>
                                <div class="row mb-6" id="lesson-place">
                                    <div class="col-12">
                                        <label class="form-label ms-2 mb-2" for="luogo-lezione">Luogo</label>
                                        <input class="form-control form-control-solid" type="text" name="luogo-lezione" id="luogo-lezione"/>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-12">
                                        <label class="form-label ms-2 mb-2" for="descrizione-lezione">Descrizione</label>
                                        <input class="form-control form-control-solid" type="text" name="descrizione-lezione" id="descrizione-lezione"/>
                                    </div>
                                </div>
                            </div>
                            <div id="error-message" class="text-danger px-12 pb-4 d-none"><p>Il nome della lezione è obbligatorio.</p></div>
                            <div id="another-date-message" class="text-danger px-12 pb-4 d-none"><p>La data della lezione non può essere antecedente o coincidente alla data di oggi.</p></div>
                            <div id="date-error-message" class="text-danger px-12 pb-4 d-none"><p>È già presente una lezione di questo corso alla data indicata.</p></div>
                            <!--end::Body-->
                        </div>
                        <!--end::Item-->
                        <input type="text" id="newLesson" name="newLesson" value="8" hidden>
            </div>

            <!--end::Step 3-->

            <!--begin::Step 4-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-body">
                    <!--begin::Accordion-->
                    <div class="accordion" id="manage-video">
                        <!--begin::Item-->
                        <div class="border-bottom border-bottom-1 border-gray-300">
                            <div class="mb-4 rounded px-8 py-4 bg-primary-subtle border border-1 border-primary d-flex align-items-center justify-content-center">
                                <p class="m-0">Se questa lezione fa parte di un corso che si svolge esclusivamente in presenza,
                                    potete continuare senza inserire alcun link o video.</p>
                            </div>
                            <div id="video-box">
                                <!--begin::Header-->
                                <div id="video-header" class="accordion-header py-10 pe-4 d-flex w-100 d-flex justify-content-between align-items-center rounded">
                                    <h2 id="video-title" class="fw-semibold mb-0 ms-4">Genera Video - Caricamento video registrato</h2>
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div id="genera-video" class="fs-6 p-4 d-flex align-items-center justify-content-center gap-8 flex-column" data-bs-parent="#manage-video">
                                    <div class="d-flex flex-column gap-4 d-none w-100" id="choose-video"></div>
                                    <div class="loader d-none" id="video-loader"></div>
                                    <button type="button" class="btn btn-primary d-none" id="video-upload-button">Carica il video</button>
                                    <p class="d-none" id="upload-progress"></p>
                                    <div id="uploaded-video" class="w-100 d-none"></div>
                                    <div class="w-100 text-end">
                                        <button id="add-marker-button" data-bs-action="add" data-bs-idMarker="" data-bs-toggle="modal" data-bs-target="#video-modal" class="btn btn-light-bg btn-sm d-none my-4"><i class="ki-outline ki-plus-square fs-6"></i> Aggiungi marker</button>
                                        <button type="button" class="btn btn-light-bg btn-sm d-none my-4" id="video-remove-button"><i class="ki-outline ki-trash fs-6"></i> Rimuovi il video</button>
                                    </div>
                                    <div class="table-responsive d-none px-7" id="markers-table">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <thead>
                                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-50px sorting_disabled align-bottom">Minutaggio</th>
                                                <th class="min-w-150px sorting_disabled">Nome</th>
                                                <th class="min-w-125px sorting_disabled">Tipologia</th>
                                                <th class="min-w-125px sorting_disabled">Azioni</th>
                                            </tr>
                                            </thead>
                                            <tbody id="markers-polls-list" class="text-gray-600 fw-bold"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div id="link-box" class="border-bottom border-bottom-1 border-gray-300">
                            <!--begin::Header-->
                            <div id="link-header" class="accordion-header py-10 pe-4 d-flex w-100 d-flex justify-content-between align-items-center rounded">
                                <h2 id="link-title" class="fw-semibold mb-0 ms-4">Link Youtube</h2>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div id="crea-link" class="fs-6 px-4" data-bs-parent="#manage-video">
                                <input class="w-100 form-control form-control-solid mb-7" id="live-stream-link"/>
                            </div>
                            <!--end::Body-->
                        </div>
                        <div id="zoom-box">
                            <!--begin::Header-->
                            <div id="zoom-header" class="accordion-header py-10 pe-4 d-flex w-100 d-flex justify-content-between align-items-center rounded">
                                <h2 id="zoom-title" class="fw-semibold mb-0 ms-4">Diretta Zoom</h2>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div id="crea-zoom" class="fs-6 px-4 mt-4 pb-7" data-bs-parent="#manage-video">
                                <label for="zoom-meeting">Numero del meeting</label>
                                <input class="w-100 form-control form-control-solid mb-4 mt-2" id="zoom-meeting"/>
                                <label for="zoom-pw">Password del meeting</label>
                                <input class="w-100 form-control form-control-solid mb-4 mt-2" id="zoom-pw"/>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Accordion-->
            </div>
            <!--end::Step 4-->
        </div>
            <!--begin::Step 5-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header flex-column gap-2 py-7">
                    <h2 class="mb-0">Scegli i materiali</h2>
                    <p class="fw-semibold fs-5 mb-0">Puoi scegliere una dispensa e un quiz per lezione</p>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_materials_toAdd">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                                <th class="min-w-125px sorting_disabled">Categoria</th>
                                <th class="min-w-125px sorting_disabled">Data di creazione</th>
                            </tr>
                            </thead>
                            <tbody id="materialsToSelect" class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>

                <div class="card-header flex-column gap-2 py-7">
                    <h2 class="mb-0">Scegli il sondaggio</h2>
                    <p class="fw-semibold fs-5 mb-0">Puoi scegliere un questionario di gradimento da associare a questa lezione</p>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_surveys_toAdd">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                                <th class="min-w-125px sorting_disabled">Data di creazione</th>
                            </tr>
                            </thead>
                            <tbody id="surveysToSelect" class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Step 5-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header flex-column gap-2 py-7">
                    <h2 class="mb-0">Scegli i compiti</h2>
                    <p class="fw-semibold fs-5 mb-0">I compiti verranno visualizzati dallo studente prima o dopo le lezioni</p>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_homeworks_toAdd">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                                <th class="min-w-125px sorting_disabled">Categoria</th>
                                <th class="min-w-125px sorting_disabled">Data di creazione</th>
                            </tr>
                            </thead>
                            <tbody id="homeworksToSelect" class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
            </div>
            <!--begin::Step 5-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Scegli i partner</h2>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_sponsors_toAdd">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px sorting_disabled"></th>
                                <th class="min-w-125px sorting_disabled"></th>
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                                <th class="min-w-125px sorting_disabled">Data di creazione</th>
                            </tr>
                            </thead>
                            <tbody id="sponsorsToSelect" class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Step 5-->
            <!--begin::Step 5-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Riepilogo</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li id="recap-nome" class="list-group-item">
                        </li>
                        <li id="recap-descrizione" class="list-group-item">
                        </li>
                    </ul>
                    <ul class="list-group list-group-flush d-none" id="var-data">
                        <li id="recap-data" class="list-group-item border-top-1">
                        </li>
                        <li id="recap-inizio" class="list-group-item">
                        </li>
                        <li id="recap-fine" class="list-group-item">
                        </li>
                        <li id="recap-luogo" class="list-group-item">
                        </li>
                    </ul>
                    <div class="card-header px-0 py-7">
                        <h3 class="fs-4">Lista dei materiali</h3>
                    </div>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_materials_recap">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                                <th class="min-w-125px sorting_disabled">Categoria</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>

                    <div class="card-header px-0 py-7">
                        <h3 class="fs-4">Lista dei compiti</h3>
                    </div>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_homeworks_recap">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                                <th class="min-w-125px sorting_disabled">Categoria</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>

                    <div class="card-header px-0 py-7">
                        <h3 class="fs-4">Lista dei sondaggi</h3>
                    </div>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_surveys_recap">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <?php if($group == 1):?>
                    <div class="card-header px-0 py-7">
                        <h3 class="fs-4">Lista dei partner</h3>
                    </div>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_sponsors_recap">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-75px sorting_disabled"></th>
                                <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <?php endif;?>
                </div>
                <div id="publish-error-message" class="text-danger px-12 py-8 d-none"><p>Tutti i dati della lezione elencati al punto 1 devono essere compilati prima di procedere alla pubblicazione. Torna indietro e assicurati di aver inserito tutti i dati.</p></div>

            </div>
            <!--end::Step 5-->
        <div class="d-flex flex-stack card-footer">
            <!--begin::Wrapper-->
            <div class="me-2">
                <button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="previous">
                    Indietro
                </button>
            </div>
            <!--end::Wrapper-->

            <!--begin::Wrapper-->
            <div>
                <?php if(isset($_GET['type']) && $_GET['type'] == 1):?>
                    <button type="submit" value="1" class="btn btn-primary" data-kt-stepper-action="submit">
                        <span class="indicator-label">
                            Salva
                        </span>
                            <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                <?php else: ?>
                    <button type="submit" value="2" class="btn btn-secondary" data-kt-stepper-action="submit">
                        <span class="indicator-label">
                            Salva in bozze
                        </span>
                            <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button type="submit" value="1" class="btn btn-primary" data-kt-stepper-action="submit">
                        <span class="indicator-label">
                            Pubblica
                        </span>
                        <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                <?php endif;?>


                <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                    Continua
                </button>
            </div>
            <!--end::Wrapper-->
        </div>
    </form>
</div>
<div class="text-start w-75 px-10 py-7 m-auto">
    <a href="<?=ROOT . 'corso?get=course&id=' . explode("=", explode('&', $_SERVER["QUERY_STRING"])[0])[1]?>">&larr; Torna indietro</a>
</div>

<?php loadPartial('components/video-modals/add-poll-marker') ?>
