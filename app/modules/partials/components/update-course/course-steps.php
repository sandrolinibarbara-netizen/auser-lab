<?php
$ondemand = $parsed['corso'][0]['data_inizio'] === '01/01/3000';
?>

<!--begin::Stepper-->
<div class="stepper stepper-pills" id="kt_stepper_course">
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
                        Dati corso
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
    </div>
    <!--end::Nav-->

    <form method="post" class="form card w-75 mx-auto" novalidate="novalidate" id="kt_stepper_course_form">
        <!--begin::Group-->
        <div>
            <!--begin::Step 1-->
            <div class="flex-column current" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Scegli la categoria</h2>
                </div>
                <!--begin::Input group-->
                <div class="row card-body">
                    <?php foreach($parsed['argomenti'] as $topic):?>
                        <div class="col-3">
                            <label class="form-check-image">
                                <div class="form-check-wrapper w-150px h-150px" style="background-color: <?=$topic['colore']?>">
                                    <img src="<?php echo (explode(':', $topic['immagine'])[0] === 'http' || explode(':', $topic['immagine'])[0] === 'https' ? $topic['immagine'] : ROOT . 'app/assets/uploaded-files/category-images/' . $topic['immagine'])?>"/>
                                </div>

                                <div class="form-check form-check-custom form-check-solid d-flex justify-content-center w-100">
                                    <input <?php echo($parsed['corso'][0]['id_topic'] === $topic['id']) ? 'checked' : '' ?> class="form-check-input radio-check" type="radio" value="<?=$topic['id']?>" name="argomento"/>
                                    <div class="form-check-label">
                                        <?=$topic['nome']?>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach;?>
                </div>
                <!--end::Input group-->
                <div id="error-message" class="text-danger px-12 pb-4 d-none"><p>Il tipo di argomento è obbligatorio.</p></div>
            </div>
            <!--end::Step 1-->

            <!--begin::Step 2-->
            <div class="flex-column" data-kt-stepper-element="content">
                <div class="card-header py-7">
                    <h2 class="mb-0">Inserisci i dati del corso</h2>
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
                            <img id="pic" src="<?php echo ($parsed['corso'][0]['immagine']) ? ROOT.'app/assets/uploaded-files/heros-images/'.$parsed['corso'][0]['immagine'] : '' ?>" class="h-75"/>
                        </div>
                    </div>
                </div>
                <div class="row mb-10">
                    <h3 class="mb-4"><label for="link-video">Link video di presentazione corso (Youtube)</label></h3>
                    <div class="col-12">
                        <input class="form-control form-control-solid" type="text" id="path-video" name="path-video" value="<?=$parsed['corso'][0]['video'] ?? ''?>"/>
                    </div>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                    <div class="fv-row row mb-6">
                        <h3 class="mb-4">Dati</h3>
                        <div class="col-12">
                            <label class="form-label ms-2 mb-2" for="nome">Nome del corso <span><em>(obbligatorio)</em></span></label>
                            <input class="form-control form-control-solid" type="text" name="nome" id="nome" value="<?=$parsed['corso'][0]['corso']?>"/>
                        </div>
                    </div>
                    <div class="fv-row row mb-6">
                        <div class="col-12">
                            <label class="form-label ms-2 mb-2" for="lezioni">Numero di lezioni</label>
                            <input class="form-control form-control-solid" type="text" name="lezioni" id="lezioni" value="<?=$parsed['corso'][0]['lezioni']?>"/>
                        </div>
                    </div>
                    <div class="fv-row row mb-6">
                        <div class="col-12">
                            <label class="form-label ms-2 mb-2" for="ore">Durata delle lezioni (in ore)</label>
                            <input class="form-control form-control-solid" type="text" name="ore" id="ore" value="<?=$parsed['corso'][0]['durata']?>"/>
                        </div>
                    </div>
                        <div class="row mb-6 <?php echo($ondemand) ? 'd-none' : '' ?>">
                            <div class="fv-row col-6">
                                <label class="form-label ms-2 mb-2" for="data-inizio">Da</label>
                                <input class="form-control form-control-solid" name="data-inizio" id="data-inizio" value="<?=$parsed['corso'][0]['data_inizio']?>"/>
                            </div>
                            <div class="fv-row col-6">
                                <label class="form-label ms-2 mb-2" for="data-fine">A</label>
                                <input class="form-control form-control-solid" name="data-fine" id="data-fine" value="<?=$parsed['corso'][0]['data_fine']?>"/>
                            </div>
                        </div>
                    <div class="fv-row row mb-6">
                        <div class="col-12">
                            <label class="form-label ms-2 mb-2" for="descrizione">Descrizione del corso</label>
                            <input class="form-control form-control-solid" type="text" name="descrizione" id="descrizione" value="<?=$parsed['corso'][0]['descrizione']?>">
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="fv-row <?php echo($ondemand) ? 'col-12' : 'col-4' ?>">
                            <label class="form-label ms-2 mb-2" for="contributo">Contributo (in €)</label>
                            <input class="form-control form-control-solid" type="number" name="contributo" id="contributo" value="<?=$parsed['corso'][0]['importo']?>"/>
                        </div>
                            <div class="fv-row col-4 <?php echo($ondemand) ? 'd-none' : '' ?>">
                                <label class="form-label ms-2 mb-2" for="min">Minimo studenti</label>
                                <input class="form-control form-control-solid" type="number" name="min" id="min" value="<?=$parsed['corso'][0]['min']?>"/>
                            </div>
                            <div class="fv-row col-4 <?php echo($ondemand) ? 'd-none' : '' ?>">
                                <label class="form-label ms-2 mb-2" for="max">Massimo studenti</label>
                                <input class="form-control form-control-solid" type="number" name="max" id="max" value="<?=$parsed['corso'][0]['max']?>"/>
                            </div>
                    </div>
                    <div class="fv-row row mb-10">
                        <div class="col-12">
                            <label class="form-label ms-2 mb-2" for="insegnanti">Insegnanti</label>
                            <select multiple class="form-select form-select-solid" data-control="select2" data-placeholder="Scegli uno o più insegnanti" name="insegnanti" id="insegnanti">
                                <option></option>
                                <?php foreach($parsed['insegnanti'] as $teacher):?>
                                    <option <?php echo($teacher['checked'] != 0) ? 'selected' : '' ?> value="<?=$teacher['id']?>"><?=$teacher['insegnante']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="fv-row row mb-10 <?php echo($ondemand) ? 'd-none' : '' ?>">
                            <h3 class="mb-4">Il corso è online o in presenza?</h3>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="remoto">Online
                            <input <?php echo($parsed['corso'][0]['remoto'] === 1 && $parsed['corso'][0]['presenza'] === 0) ? 'checked' : '' ?> class="form-check-input" type="radio" name="modalità" id="remoto" value="1"/>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="presenza">In presenza
                            <input <?php echo($parsed['corso'][0]['remoto'] === 0 && $parsed['corso'][0]['presenza'] === 1) ? 'checked' : '' ?> class="form-check-input" type="radio" name="modalità" id="presenza" value="2"/>
                            </label>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="mista">Modalità mista
                                <input <?php echo($parsed['corso'][0]['remoto'] === 1 && $parsed['corso'][0]['presenza'] === 1) ? 'checked' : '' ?> class="form-check-input" type="radio" name="modalità" id="mista" value="3"/>
                            </label>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="mod-boh">De definire
                                <input <?php echo($parsed['corso'][0]['remoto'] === 2 && $parsed['corso'][0]['presenza'] === 2) ? 'checked' : '' ?> class="form-check-input" type="radio" name="modalità" id="mod-boh" value="4"/>
                            </label>
                        </div>
                    </div>
                    <div class="fv-row row mb-10">
                            <h3 class="mb-4">Il corso prevede tesseramento?</h3>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="tess-yes">Sì
                            <input <?php echo($parsed['corso'][0]['tesseramento'] === 1 ) ? 'checked' : '' ?> class="form-check-input" type="radio" name="tesseramento" id="tess-yes" value="1"/>
                            </label>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="tess-no">No
                            <input <?php echo($parsed['corso'][0]['tesseramento'] === 0 ) ? 'checked' : '' ?> class="form-check-input" type="radio" name="tesseramento" id="tess-no" value="0"/>
                            </label>
                        </div>
                        <div class="col-3 form-check form-check-custom form-check-solid">
                            <label class="form-label ms-2 mb-2 d-flex gap-2" for="tess-boh">Da definire
                                <input <?php echo($parsed['corso'][0]['tesseramento'] === 2 ) ? 'checked' : '' ?> class="form-check-input" type="radio" name="tesseramento" id="tess-boh" value="2"/>
                            </label>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <h3 class="mb-4">Il corso è pubblico o privato?</h3>
                        <div class="col-12">
                            <div class="btn p-6 d-flex gap-6 align-items-center btn-color-muted bg-light-bg text-auser form-check form-check-custom form-check-solid">
                            <input class="form-check-input" <?php echo($parsed['corso'][0]['privato'] === 1 ) ? 'checked' : '' ?> type="radio" name="visibilità" id="privato" value="1"/>
                                <div class="d-flex align-items-center gap-2 min-w-100px">
                                    <label class="d-flex gap-2 align-items-center" for="privato"><i class="ki-outline ki-sms fs-1 text-auser"></i> Privato</label>
                                </div>
                                <div>
                                    <p class="w-80 text-start mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam interdum felis at dignissim pulvinar. Praesent et tellus finibus, mollis nunc et, iaculis erat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="btn p-6 d-flex gap-6 align-items-center btn-color-muted bg-light-bg text-auser form-check form-check-custom form-check-solid">
                            <input class="form-check-input" <?php echo($parsed['corso'][0]['privato'] === 0 ) ? 'checked' : '' ?> type="radio" name="visibilità" id="pubblico" value="0"/>
                                <div class="d-flex align-items-center gap-2 min-w-100px">
                                    <label class="d-flex gap-2 align-items-center" for="pubblico"><i class="ki-outline ki-people fs-1 text-auser"></i> Pubblico</label>
                                </div>
                                <div>
                                    <p class="w-80 text-start mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam interdum felis at dignissim pulvinar. Praesent et tellus finibus, mollis nunc et, iaculis erat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--end::Input group-->
                </div>
                <div id="name-error-message" class="text-danger px-12 pb-4"></div>
                <div id="publish-error-message" class="text-danger px-12 py-8 d-none"><p>Tutti i dati del corso, con l'eccezione dell'immagine di copertina e del link al video, devono essere compilati prima di procedere alla pubblicazione. Assicurati di aver inserito tutti i dati.</p></div>
                <div id="another-date-message" class="text-danger px-12 py-8 d-none"><p>Le date di inizio e fine del corso non possono essere antecedenti o coincidenti alla data di oggi.</p></div>
            </div>
            <!--end::Step 2-->
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
                    <button id="publish-button" type="submit" value="1" class="btn btn-primary" data-kt-stepper-action="submit">
                        <span class="indicator-label">
                            Salva
                        </span>
                        <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                <?php else: ?>
                    <button id="save-button" type="submit" value="2" class="btn btn-secondary" data-kt-stepper-action="submit">
                        <span class="indicator-label">
                            Salva in bozze
                        </span>
                        <span class="indicator-progress">
                            Attendi... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button id="publish-button" type="submit" value="1" class="btn btn-primary" data-kt-stepper-action="submit">
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