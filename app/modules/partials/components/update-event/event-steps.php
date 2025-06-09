<!--begin::Stepper-->
<div class="stepper stepper-pills" id="kt_stepper_draft_event">
    <!--begin::Nav-->
    <div class="stepper-nav flex-center flex-wrap mb-10">
        <!--begin::Step 1-->
        <div class="stepper-item mx-4 my-2 current" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">1.</span>
                </div>
                <!--end::Icon-->

                <!--begin::Label-->
                <div class="stepper-label">
                    <h3 class="stepper-title">
                        Scegli la categoria
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--end::Step 1-->
        <!--begin::Step 2-->
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
                        Dati evento
                    </h3>
                </div>
                <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
        </div>
        <!--end::Step 2-->
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
                        Scegli i relatori
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
                    <span class="stepper-number">4.</span>
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
                    <span class="stepper-number">5.</span>
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
        <!--begin::Step 6-->
        <div class="stepper-item mx-4 my-2<?php echo($group == 3) ? ' d-none' : ''?>" data-kt-stepper-element="nav">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
                <!--begin::Icon-->
                <div class="stepper-icon">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">6.</span>
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
                    <span class="stepper-number"><?php echo($group == 3) ? '6' : '7'?>.</span>
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
    </div>
    <!--end::Nav-->

    <input id="user-permission" value="<?=$group?>" hidden readonly/>
    <form method="post" class="form card w-75 mx-auto" novalidate="novalidate" id="kt_stepper_draft_event_form">
        <!--begin::Group-->
        <div>
            <!--begin::Step 1-->
            <div class="flex-column current" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Scegli la categoria</h2>
                </div>
                <!--begin::Input group-->
                <div class="row card-body" id="topics-list">
                </div>
                <div id="error-message" class="text-danger px-12 pb-4 d-none"><p>Il tipo di argomento è obbligatorio.</p></div>
                <!--end::Input group-->
            </div>
            <!--end::Step 1-->
            <!--begin::Step 2-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Inserisci i dati dell'evento</h2>
                </div>
                <div class="card-body">
                    <!--begin::Input group-->
                    <div class="row mb-10">
                        <h3 class="mb-4">Immagine</h3>
                        <p>Dimensioni ideali pari a <strong>1920 x 1080 pixel</strong>, peso non superiore a <strong>800kB</strong>. Formati accettati: <strong>.png, .jpg</strong></p>
                        <div id="sponsor-pic" class="d-flex flex-column w-100">
                            <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center" data-kt-image-input="true">
                                <label title="Scegli un'immagine" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                                    <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                    <input id="picInput" name="pic" type="file" accept=".png, .jpg, .jpeg"/>
                                </label>
                                <img id="pic" src="" class="h-75"/>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <h3 class="mb-4">Dati</h3>
                        <div class="col-12">
                            <label class="form-label ms-2 mb-2" for="nome-evento">Titolo dell'evento <span><em>(obbligatorio)</em></span></label>
                            <input class="form-control form-control-solid" type="text" name="nome-evento" id="nome-evento"/>
                        </div>
                    </div>
                    <div class="row mb-6" id="event-details">
                        <div class="col-6">
                            <label class="form-label ms-2 mb-2" for="data-evento">Data evento</label>
                            <input class="form-control form-control-solid" name="data-evento" id="data-evento"/>
                        </div>
                        <div class="col-6">
                            <label class="form-label ms-2 mb-2" for="luogo-evento">Luogo</label>
                            <input class="form-control form-control-solid" name="luogo-evento" id="luogo-evento"/>
                        </div>
                    </div>
                    <div class="row mb-6" id="event-times">
                        <div class="col-6">
                            <label class="form-label ms-2 mb-2" for="orario-inizio-evento">Orario di inizio</label>
                            <input class="form-control form-control-solid" name="orario-inizio-evento" id="orario-inizio-evento"/>
                        </div>
                        <div class="col-6">
                            <label class="form-label ms-2 mb-2" for="orario-fine-evento">Orario di fine</label>
                            <input class="form-control form-control-solid" name="orario-fine-evento" id="orario-fine-evento"/>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-12">
                            <label class="form-label ms-2 mb-2" for="descrizione-evento">Descrizione dell'evento</label>
                            <textarea class="form-control form-control-solid" type="text" name="descrizione-evento" id="descrizione-evento"></textarea>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-6" id="event-fee">
                            <label class="form-label ms-2 mb-2" for="contributo-evento">Contributo (in €)</label>
                            <input class="form-control form-control-solid" type="number" name="contributo-evento" id="contributo-evento"/>
                        </div>
                        <div class="col-6" id="event-max">
                            <label class="form-label ms-2 mb-2" for="max-evento">N° massimo di partecipanti</label>
                            <input class="form-control form-control-solid" type="number" name="max-evento" id="max-evento"/>
                        </div>
                    </div>
                    <div class="row mb-10" id="event-modes">
                        <h3 class="mb-4">L'evento è online o in presenza?</h3>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="remoto-evento">Online
                                <input class="form-check-input" type="radio" name="modalità" id="remoto-evento" value="1"/>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="presenza-evento">In presenza
                                <input class="form-check-input" type="radio" name="modalità" id="presenza-evento" value="2"/>
                            </label>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="mod-boh-evento">De definire
                                <input class="form-check-input" type="radio" name="modalità" id="mod-boh-evento" value="3"/>
                            </label>
                        </div>
                    </div>
                    <div class="row mb-10">
                        <h3 class="mb-4">L'evento prevede tesseramento?</h3>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="tess-yes-evento">Sì
                                <input class="form-check-input" type="radio" name="tesseramento" id="tess-yes-evento" value="1"/>
                            </label>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="tess-no-evento">No
                                <input class="form-check-input" type="radio" name="tesseramento" id="tess-no-evento" value="0"/>
                            </label>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="tess-boh-evento">Da definire
                                <input class="form-check-input" type="radio" name="tesseramento" id="tess-boh-evento" value="2"/>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="mb-4">L'evento è pubblico o privato?</h3>
                        <div class="col-12">
                            <div class="btn p-6 d-flex gap-6 align-items-center col-8 btn-color-muted bg-light-bg text-auser form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="radio" name="visibilità" id="privato-evento" value="1"/>
                                <div class="d-flex align-items-center gap-2 min-w-100px">
                                    <label class="d-flex gap-2 align-items-center" for="privato-evento"><i class="ki-outline ki-sms fs-1 text-auser"></i> Privato</label>
                                </div>
                                <div>
                                    <p class="w-80 text-start mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam interdum felis at dignissim pulvinar. Praesent et tellus finibus, mollis nunc et, iaculis erat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="btn p-6 d-flex gap-6 align-items-center col-8 btn-color-muted bg-light-bg text-auser form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="radio" name="visibilità" id="pubblico-evento" value="0"/>
                                <div class="d-flex align-items-center gap-2 min-w-100px">
                                    <label class="d-flex gap-2 align-items-center" for="pubblico-evento"><i class="ki-outline ki-people fs-1 text-auser"></i> Pubblico</label>
                                </div>
                                <div>
                                    <p class="w-80 text-start mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam interdum felis at dignissim pulvinar. Praesent et tellus finibus, mollis nunc et, iaculis erat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="another-date-message" class="text-danger px-12 py-8 d-none"><p>La data della lezione non può essere antecedente o coincidente alla data di oggi.</p></div>
                    <div id="name-error-message" class="text-danger px-12 py-8 d-none"><p>Il nome dell'evento è obbligatorio.</p></div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Step 2-->
            <!--begin::Step 5-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Scegli i relatori</h2>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_speakers_toAdd">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px sorting_disabled"></th>
                                <th class="min-w-125px sorting_disabled"></th>
                                <th class="min-w-150px sorting_disabled align-bottom">Nome</th>
                                <th class="min-w-125px sorting_disabled">Data di creazione</th>
                            </tr>
                            </thead>
                            <tbody id="speakersToSelect" class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Step 5-->
            <!--begin::Step 4-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-body">
                    <!--begin::Accordion-->
                    <div class="accordion" id="manage-video-event">
                        <!--begin::Item-->
                        <div id="link-box" class="border-bottom border-bottom-1 border-gray-300 pb-7">
                            <!--begin::Header-->
                            <div class="accordion-header pt-10 pb-3 d-flex w-100 d-flex justify-content-between align-items-center">
                                <h2 class="fw-semibold mb-0 ms-4" id="link-title">Crea link piattaforma per streaming</h2>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div id="crea-link" class="fs-6 px-4 mt-4" data-bs-parent="#manage-video-event">
                                <input class="w-100 form-control form-control-solid" id="live-stream-link"/>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Item-->
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
                    </div>
                    <!--end::Accordion-->
                </div>
                <!--end::Step 4-->
            </div>
            <!--end::Step 4-->
            <!--begin::Step 5-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header flex-column gap-2 py-7">
                    <h2 class="mb-0">Scegli i materiali</h2>
                    <p class="fw-semibold fs-5 mb-0">Puoi scegliere una dispensa e un quiz per evento</p>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_materials_event_toAdd">
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
                    <p class="fw-semibold fs-5 mb-0">Puoi scegliere un questionario di gradimento da associare a questo evento</p>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_surveys_event_toAdd">
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
            <!--begin::Step 5-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Scegli i partner</h2>
                </div>
                <div class="card-body">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_sponsors_event_toAdd">
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
                        <li id="recap-event-nome" class="list-group-item">
                        </li>
                        <li id="recap-event-descrizione" class="list-group-item">
                        </li>
                        <li id="recap-event-importo" class="list-group-item">
                        </li>
                    </ul>
                    <ul class="list-group list-group-flush d-none" id="var-data">
                        <li id="recap-event-data" class="list-group-item">
                        </li>
                        <li id="recap-event-inizio" class="list-group-item">
                        </li>
                        <li id="recap-event-fine" class="list-group-item">
                        </li>
                        <li id="recap-event-luogo" class="list-group-item">
                        </li>
                    </ul>
                    <div class="card-header px-0 py-7">
                        <h3 class="fs-4">Lista dei relatori</h3>
                    </div>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_speakers_event_recap">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-75px sorting_disabled"></th>
                                <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold"></tbody>
                        </table>
                    </div>
                    <div class="card-header px-0 py-7">
                        <h3 class="fs-4">Lista dei materiali</h3>
                    </div>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_materials_event_recap">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-75px sorting_disabled"></th>
                                <th class="min-w-125px sorting_disabled align-bottom">Nome</th>
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_surveys_event_recap">
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 px-4" id="kt_datatable_sponsors_event_recap">
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
                <div id="publish-error-message" class="text-danger px-12 py-8 d-none"><p>Tutti i dati dell'evento elencati al punto 2, con l'eccezione dell'immagine di copertina, devono essere compilati prima di procedere alla pubblicazione. Torna indietro e assicurati di aver inserito tutti i dati.</p></div>
            </div>
            <!--end::Step 5-->
        </div>
        <!--end::Group-->

        <!--begin::Actions-->
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
        <!--end::Actions-->
    </form>
    <!--end::Form-->
    <div class="text-start w-75 px-10 py-7 m-auto">
        <a href="<?=ROOT . 'corsi-eventi'?>">&larr; Torna indietro</a>
    </div>
</div>
<!--end::Stepper-->