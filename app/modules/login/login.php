<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

?>

<?php loadPartial('layout/head')?>
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <div class="bg-auser w-100">
        <a href="<?=ROOT?>home?show=ecommerce">
            <img alt="auser-logo" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.webp" class="h-75px mx-10 my-4" />
        </a>
    </div>
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            <!--begin::Form-->
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-500px p-10">
                    <!--begin::Form-->
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="#">
                        <!--begin::Heading-->
                        <div class="text-center mb-11">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Accedi</h1>
                            <!--end::Title-->
                        </div>
                        <!--begin::Heading-->
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8">
                            <!--begin::Email-->
                            <input id="kt_form_login_username" name="kt_form_login_username" type="text" placeholder="Username" autocomplete="off" class="form-control bg-transparent" />
                            <!--end::Email-->
                        </div>
                        <!--end::Input group=-->
                        <div class="fv-row mb-3">
                            <!--begin::Password-->
                            <input id="kt_form_login_password" name="kt_form_login_password" type="password" placeholder="Password" autocomplete="off" class="form-control bg-transparent" />
                            <!--end::Password-->
                        </div>
                        <!--end::Input group=-->
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                            <div></div>
                            <!--begin::Link-->
                            <a href="<?= ROOT.'recupero/mail?type=1'?>" class="link-auser">Ho dimenticato la password</a>
                            <!--end::Link-->
                        </div>
                        <!--end::Wrapper-->
                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-success">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Accedi</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Attendi...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                <!--end::Indicator progress-->
                            </button>
                        </div>
                        <!--end::Submit button-->
                        <!--begin::Sign up-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">Non sei iscritto alla piattaforma Auser UniPop?
                            <a href="<?=ROOT.'iscrizione'?>" class="link-auser">Iscriviti</a></div>
                        <!--end::Sign up-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Form-->
            <!--begin::Footer-->
            <div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<input type="text" id="kt_root_login" name="kt_root_login" value="<?php echo ROOT;?>" hidden readonly>
<?php
loadPartial('layout/scripts/base');
loadPartial('layout/scripts/login');
loadPartial('layout/footer');?>