<?php
require_once '../../config/config_inc.php';
$query = $_SESSION[SESSIONROOT]['pages'];
$element = 'categoria';
?>

<?php loadPartial('layout/top', ['query' => $query])?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Calendar-->
        <?php loadPartial('components/categories/categories') ?>
        <!--end::Calendar-->
    </div>
    <!--end::Content container-->
</div>
<input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
<input type="text" id="element" value="<?php echo $element;?>" hidden readonly>
<?php loadPartial('components/modals/modal-remove', ['element' => $element])?>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/categories')?>
<script>
    function reloadTable(tabId) {
        $(tabId).DataTable().ajax.reload();
    }
</script>
<?php loadPartial('layout/footer')?>
