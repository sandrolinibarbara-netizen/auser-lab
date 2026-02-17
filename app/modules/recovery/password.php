<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
?>

<?php loadPartial('layout/head')?>
<?php loadPartial('layout/page')?>
    <div class="bg-auser d-flex justify-content-center align-items-center">
        <div class="w-100 mx-10 d-flex justify-content-between align-items-center">
            <div class="py-4">
                <a href="<?=ROOT?>home?show=ecommerce">
                    <img alt="auser-logo" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.webp" class="h-75px app-sidebar-logo-default" />
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
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/users/new-user') ?>
                <!--end::Table widget 14-->
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

