<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
?>

<?php loadPartial('layout/head')?>
<?php loadPartial('layout/page')?>
    <div id="kt_app_content" class="app-content flex-column-fluid bg-white">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="d-flex flex-row justify-content-between w-100">
                <img src="<?=ROOT?>app/assets/images/auser aps.webp" alt="logo auser aps"/>
                <img src="<?=ROOT?>app/assets/images/auser odv.webp" alt="logo auser odv"/>
            </div>

            <div style="height: 65vh; width: 40%" class="mx-auto d-flex flex-column gap-8 align-items-center justify-content-center">
                <h1>
                    Benvenuti nella piattaforma digitale
                </h1>
                <img class="w-100" src="<?=ROOT?>app/assets/images/auserlab.webp" alt="logo auser lab"/>
                <div class="w-100 text-end">
                    <a href="<?= ROOT."home?show=ecommerce" ?>" class="btn btn-success mt-4">Entra &rarr;</a>
                </div>
            </div>

            <div class="d-flex flex-row justify-content-center w-100">
                <img class="h-150px" src="<?=ROOT?>app/assets/images/ministero cultura.webp" alt="logo ministero cultura"/>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
    <input type="text" id="new-user-signup" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
<?php loadPartial('layout/scripts/new-user')?>
<?php loadPartial('layout/footer')?>

