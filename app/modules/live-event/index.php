<?php loadPartial('layout/head');
loadPartial('layout/page');?>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="upper-menu" class="bg-auser w-100 d-flex align-items-center justify-content-between position-relative" style="z-index: 1500">
            <img alt="auser-logo" class="w-200px py-2 px-8" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.svg" />
            <a class="btn btn-surface text-auser text-hover-white mx-8 rightFont better-hover" href="<?=ROOT.'dashboard?id='.$_SESSION[SESSIONROOT]['user']?>" style="border: #D9D9D9 solid 1px">
                <i class="ki-outline ki-user text-auser"></i>
                Area riservata
            </a>
        </div>
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/live-event/streaming-platform', ['parsed' => $parsed]) ?>
                <!--end::Table widget 14-->
            </div>
        </div>
        <!--end::Content container-->
        <?php loadPartial('components/live-modals/agenda-event-modal', ['parsed' => $parsed['lezione'][0]])?>
        <?php foreach($parsed['relatori'] as $speaker):?>
            <form class="modal fade rightFont" tabindex="-1" id="speaker-<?=$speaker['id']?>-modal" style="z-index: 2000">
                <div class="modal-dialog mw-800px">
                    <div class="modal-content p-7">
                        <div class="modal-header p-4 d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-6 align-items-center">
                                <img class="bg-light-bg rounded-circle w-100px h-100px" style="object-fit: cover; object-position: top" src="<?=ROOT.'app/assets/uploaded-files/speakers-images/'.$speaker['pic']?>" alt="<?=$speaker['nome']. '-' . $speaker['cognome']?> avatar"/>
                                <h3 id="modal-speaker-<?=$speaker['id']?>-title" class="modal-title fs-5"><?=$speaker['nome'] . ' ' . $speaker['cognome']?></h3>
                            </div>
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                                <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div id="modal-speaker-<?=$speaker['id']?>-body" class="modal-body p-4 mt-4">
                            <div><?=$speaker['bio']?></div>
                            <div class="mt-6 mb-2">
                                <h4 class="mb-4">Contatti</h4>
                                <p class="mb-1"><span class="fw-semibold">Email:</span> <?=$speaker['email']?></p>
                                <p class="mb-1"><span class="fw-semibold">Sito web:</span> <?=$speaker['sito']?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endforeach;?>

        <?php foreach($parsed['sponsor'] as $sponsor): ?>
            <form class="modal fade rightFont" tabindex="-1" id="sponsor-<?=$sponsor['idSponsor']?>-modal" style="z-index: 2000">
                <div class="modal-dialog mw-800px">
                    <div class="modal-content p-7">
                        <div class="modal-header p-4 d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-6 align-items-center">
                                <img class="bg-light-bg rounded-circle w-100px h-100px" style="object-fit: cover; object-position: top" src="<?=ROOT.'app/assets/uploaded-files/sponsor-images/'.$sponsor['pic']?>" alt="<?=$sponsor['sponsor']?> avatar"/>
                                <h3 id="modal-sponsor-<?=$sponsor['idSponsor']?>-title" class="modal-title fs-5"><?=$sponsor['sponsor']?></h3>
                            </div>
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                                <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div id="modal-sponsor-<?=$sponsor['idSponsor']?>-body" class="modal-body p-4 mt-4">
                            <div><?=$sponsor['bio']?></div>
                            <div class="mt-6 mb-2">
                                <h4 class="mb-2 fs-6">Contatti</h4>
                                <p class="mb-1 ms-1"><span class="fw-semibold">Email:</span> <?=$sponsor['mail']?></p>
                                <p class="mb-1 ms-1"><span class="fw-semibold">Telefono:</span> <?=$sponsor['telefono']?></p>
                                <p class="mb-1 ms-1"><span class="fw-semibold">Sito web:</span> <?=$sponsor['sito']?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endforeach;?>
        <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
        <?php loadPartial('components/live-modals/poll-modal')?>
        <?php loadPartial('components/live-modals/lecture-note-modal')?>
        <?php loadPartial('components/live-modals/survey-modal')?>
    </div>

<?php loadPartial('layout/bottom')?>
    <script>
        const search = window.location.search;
        const params = new URLSearchParams(search);
        const type = params.get('live')
        const lesson = params.get("id");
    </script>
<?php loadPartial('layout/scripts/live-event')?>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-3.11.2.min.js"></script>
<script src="<?=ROOT?>app/modules/partials/components/live-stream/zoomSDK.js"></script>
<?php loadPartial('layout/footer')?>