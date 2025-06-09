<?php loadPartial('layout/head');
loadPartial('layout/page');
loadPartial('layout/header-ecommerce');?>
    <div id="kt_app_content" class="app-content flex-column-fluid my-10">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="container px-4">
                <div id="courses-events-grid" class="row g-4">
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
    <script src="<?=ROOT?>app/modules/partials/components/ecommerce/search.js"></script>
    <script>
        loadTag();
    </script>
<?php loadPartial('layout/footer')?>