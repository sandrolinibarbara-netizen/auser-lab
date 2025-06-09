<?php
$query = $_SESSION[SESSIONROOT]['pages'];
$type = 'evento';
?>

<?php loadPartial('layout/top', ['query' => $query])?>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/courses/single-event', ['data' => $data]) ?>
                <!--end::Table widget 14-->
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
    <input type="text" id="type" value="<?php echo $type;?>" hidden readonly>
<?php loadPartial('components/courses/modal-add-students')?>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/single-event')?>
    <script>
        function reloadTable(tabId) {
            $(tabId).DataTable().ajax.reload();
        }
    </script>
<?php loadPartial('layout/footer')?>