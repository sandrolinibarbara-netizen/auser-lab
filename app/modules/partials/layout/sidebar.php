<div id="kt_app_sidebar" class="app-sidebar flex-column w-225px">
    <!--begin::Logo-->
    <?php loadPartial('layout/logo-sidebar');?>
    <!--end::Logo-->
    <!--begin::Sidebar menu-->
    <?php loadPartial('layout/menus/menu-sidebar', ['query' => $query]);?>
    <!--end::Sidebar menu-->
    <!--begin::Footer-->
    <?php //loadPartial('layout/footer-sidebar');?>
    <!--end::Footer-->
</div>

