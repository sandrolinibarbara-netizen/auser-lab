<?php

require_once '../../config/config_inc.php';

$query = $_SESSION[SESSIONROOT]['pages'];
$group = $_SESSION[SESSIONROOT]['group'];
?>

<?php loadPartial('layout/top', ['query' => $query])?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div>
            <!--begin::Table widget 14-->
            <?php loadPartial('components/register/single-register') ?>
            <!--end::Table widget 14-->
        </div>
    </div>
    <!--end::Content container-->
</div>
<?php loadPartial('components/register-modals/modal-end-course')?>
<?php loadPartial('components/register-modals/modal-message-user')?>
<?php loadPartial('components/register-modals/modal-move-course')?>
<?php loadPartial('components/register-modals/modal-remove-course')?>
<?php loadPartial('components/materials/download-modal') ?>
<input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/single-reg')?>
<script>
    function reloadTable(tabId) {
        $(tabId).DataTable().ajax.reload();
    }
</script>
<?php loadPartial('layout/footer')?>




