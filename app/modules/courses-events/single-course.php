<?php
$query = $_SESSION[SESSIONROOT]['pages'];
$group = $_SESSION[SESSIONROOT]['group'];
$element = 'lezione';
$type = 'corso';
?>

<?php loadPartial('layout/top', ['query' => $query])?>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/courses/single-course', ['data' => $data]) ?>
                <!--end::Table widget 14-->
            </div>
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/courses/next-lessons', ['data' => $data]) ?>
                <!--end::Table widget 14-->
            </div>
            <?php if($group != 2):?>
                <div>
                    <!--begin::Table widget 14-->
                    <?php loadPartial('components/courses/drafts-lessons') ?>
                    <!--end::Table widget 14-->
                </div>
            <?php endif;?>
            <?php if($data[0]['privato'] == 1 && $group != 2) : ?>
                <div>
                    <!--begin::Table widget 14-->
                    <?php loadPartial('components/courses/users-private') ?>
                    <!--end::Table widget 14-->
                </div>
            <?php endif; ?>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
    <input type="text" id="element" value="<?php echo $element;?>" hidden readonly>
    <input type="text" id="type" value="<?php echo $type;?>" hidden readonly>
<?php loadPartial('components/modals/modal-remove', ['element' => $element])?>
<?php loadPartial('components/courses/modal-clone-lesson')?>
<?php loadPartial('components/courses/modal-add-students')?>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/single-course')?>
    <script>
        function reloadTable(tabId) {
            $(tabId).DataTable().ajax.reload();
        }
    </script>
<?php loadPartial('layout/footer')?>