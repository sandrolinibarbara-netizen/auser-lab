<?php

require_once '../../config/config_inc.php';

$query = $_SESSION[SESSIONROOT]['pages'];
$group = $_SESSION[SESSIONROOT]['group'];
?>

<?php loadPartial('layout/top', ['query' => $query])?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Calendar-->
        <?php loadPartial('components/update-lesson/lesson-steps', ['group' => $group]) ?>
        <!--end::Calendar-->
    </div>
    <!--end::Content container-->
</div>
<input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/update-lesson')?>
<script>
    function reloadTable(tabId) {
        $(tabId).DataTable().ajax.reload();
    }
</script>
<?php loadPartial('layout/footer')?>

