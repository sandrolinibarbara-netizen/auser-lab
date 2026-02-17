<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
?>

<?php loadPartial('layout/head')?>
<?php loadPartial('layout/page')?>
    <div class="bg-auser d-flex justify-content-center align-items-center">
        <div class="w-100 mx-10 d-flex justify-content-between align-items-center">
            <div class="py-4">
                <a href="<?=ROOT?>home?show=ecommerce">
                    <img alt="auser-logo" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.webp" class="h-75px mx-10 my-4" />
                </a>
            </div>
            <div class="d-flex gap-4">
                <a class="btn btn-surface text-auser text-hover-white" href="<?=ROOT.'dashboard?id='.$_SESSION[SESSIONROOT]['user']?>">
                    <i class="ki-outline ki-user text-auser"></i>
                    Area riservata
                </a>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card w-75 my-10 text-center mx-auto p-7">
                <!--begin::Table widget 14-->
                <h2 class="mb-8 mt-2 d-flex align-items-center justify-content-center"><i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i> Registrazione avvenuta con successo!</h2>
                <h5 class="w-75 mx-auto mb-6">Controlla la tua casella di posta e clicca sul link nella mail che ti abbiamo mandato.</h5>
                <p>Non ti è arrivata la mail? Controlla nello spam.<br/>
                    Se la mail non è nello spam, o il link è scaduto, <a href="<?= ROOT.'recupero/mail?type=2'?>">te ne inviamo un'altra</a>.</p>
                <div class="w-100 text-end">
                    <a href="<?= ROOT . 'login'?>">&larr; Torna alla homepage</a>
                </div>
                <!--end::Table widget 14-->
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
    <input type="text" id="new-user-signup" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
<?php loadPartial('layout/footer')?>

