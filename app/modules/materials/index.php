<?php
require_once '../../config/config_inc.php';
$query = $_SESSION[SESSIONROOT]['pages'];
$group = $_SESSION[SESSIONROOT]['group'];
$element = 'materiale';
?>

<?php loadPartial('layout/top', ['query' => $query])?>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <?php if($group == 2):?>
                <div>
                    <!--begin::Table widget 14-->
                    <?php loadPartial('components/materials/homeworks') ?>
                    <!--end::Table widget 14-->
                </div>
            <?php else:?>
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/materials/polls', ['group' => $group]) ?>
                <!--end::Table widget 14-->
            </div>
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/materials/lecture-notes', ['group' => $group]) ?>
                <!--end::Table widget 14-->
            </div>
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/materials/drafts', ['group' => $group]) ?>
                <?php loadPartial('components/materials/surveys') ?>
                <?php loadPartial('components/materials/drafts-surveys') ?>
                <!--end::Table widget 14-->
            </div>
            <?php endif;?>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
    <input type="text" id="element" value="<?php echo $element;?>" hidden readonly>
<?php loadPartial('components/modals/modal-remove', ['element' => $element])?>
<?php loadPartial('components/materials/download-modal') ?>
<?php loadPartial('components/materials/poll-modal')?>
<?php loadPartial('components/materials/lecture-note-modal')?>
<?php loadPartial('components/materials/qr-modal')?>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/materials')?>
    <script>
        function reloadTable(tabId) {
            $(tabId).DataTable().ajax.reload();
        }
    </script>
<?php loadPartial('layout/footer')?>