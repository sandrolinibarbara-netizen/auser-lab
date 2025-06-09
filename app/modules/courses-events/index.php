<?php
require_once '../../config/config_inc.php';
$query = $_SESSION[SESSIONROOT]['pages'];
$group = $_SESSION[SESSIONROOT]['group'];
$element = 'corso/evento';
?>

<?php loadPartial('layout/top', ['query' => $query])?>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <?php if($group != 2):?>
            <div class="row row-cols-2 gx-4">
                <div class="col">
                    <div class="card">
                        <div class="card-title px-8 pt-8">
                            <h2>Crea un corso</h2>
                        </div>
                        <div class="card-body pt-4 pb-8">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/course.jpg" alt=""/>
                            <div class="w-100 text-end">
                                <a href="<?=ROOT.'new-course?create=create-course'?>" class="btn btn-secondary mt-4">Crea un corso &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-title px-8 pt-8">
                            <h2>Crea un evento</h2>
                        </div>
                        <div class="card-body pt-4 pb-8">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/event.jpg" alt=""/>
                            <div class="w-100 text-end">
                                <a href="<?=ROOT.'new-event?create=create-event'?>" class="btn btn-secondary mt-4">Crea un evento &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif;?>
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/courses/my-courses', ['group' => $group]) ?>
                <!--end::Table widget 14-->
            </div>
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/courses/events', ['group' => $group]) ?>
                <!--end::Table widget 14-->
            </div>
            <?php if($group != 2):?>
            <div>
                <!--begin::Table widget 14-->
                <?php loadPartial('components/courses/drafts-courses') ?>
                <!--end::Table widget 14-->
            </div>
            <?php endif;?>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
    <input type="text" id="element" value="<?php echo $element;?>" hidden readonly>
<?php loadPartial('components/modals/modal-remove', ['element' => $element])?>
<?php loadPartial('components/courses/modal-clone-course')?>
<?php loadPartial('components/modals/modal-add-users')?>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/courses')?>
<script>
    function reloadTable(tabId) {
        $(tabId).DataTable().ajax.reload();
    }
</script>
<?php loadPartial('layout/footer')?>