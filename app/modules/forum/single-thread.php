<?php
$element = 'post';
$query = $_SESSION[SESSIONROOT]['pages'];
?>

<?php loadPartial('layout/top', ['query' => $query])?>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/forum/single-thread', ['data' => $data]) ?>
                <!--end::Table widget 14-->
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
    <input type="text" id="element" value="<?php echo $element;?>" hidden readonly>
<?php loadPartial('components/forum/modal-add-post', ['data' => $data]) ?>
<?php loadPartial('components/modals/modal-remove', ['element' => $element])?>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/single-thread')?>
<?php loadPartial('layout/footer')?>