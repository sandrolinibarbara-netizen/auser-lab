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
                <?php loadPartial('components/forum/all-forums', ['group' => $group]) ?>
                <!--end::Table widget 14-->
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('components/forum/modal-add-forum') ?>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/all-forums')?>
    <script>
        function reloadTable(tabId) {
            $(tabId).DataTable().ajax.reload();
        }
    </script>
<?php loadPartial('layout/footer')?>