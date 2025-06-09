<?php
require_once '../../config/config_inc.php';
if($_SESSION[SESSIONROOT]['group'] == 2){
    $date = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
    $newInterval = new DateInterval('P7D');
    $newDate = $date->add($newInterval);
    $newDate = $newDate->format('Y-m-d');
    $warningLicense = $newDate > $_SESSION[SESSIONROOT]['end_license'];
}
?>

<?php loadPartial('layout/top', ['query' => $_SESSION[SESSIONROOT]['pages']])?>
<?php if($warningLicense): ?>
    <div id="warning-license" class="position-relative" style="z-index: 50">
        <div class="p-7 card bg-light-danger border border-danger w-280px position-fixed text-center" style="top: 75vh; right: 3%;">
            <div class="card-header p-0 flex justify-between align-items-center">
                <button class="btn btn-icon btn-sm btn-active-light-primary" disabled>
                </button>
                <i class="ki-outline ki-information-5 fs-2 text-danger"></i>
                <button id="close-warning" class="btn btn-icon btn-sm btn-active-light-primary">
                    <i class="ki-duotone ki-cross fs-3"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>
            <p class="m-0"><strong>La tua licenza scadrà a breve!</strong><br/>
                Rinnovala o non potrai più partecipare ai corsi di Auser UniPop.</p>
        </div>
    </div>
<?php endif; ?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Calendar-->
        <?php loadPartial('components/calendar/fullcalendar/fullcalendar') ?>
        <!--end::Calendar-->
        <div>
            <!--begin::Table widget 14-->
            <?php loadPartial('components/calendar/lessons/next-lessons') ?>
            <!--end::Table widget 14-->
        </div>
        <div>
            <!--begin::Table widget 14-->
            <?php loadPartial('components/calendar/events/next-events') ?>
            <!--end::Table widget 14-->
        </div>
    </div>
    <!--end::Content container-->
</div>
<input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/dashboard')?>
<script>
    function reloadTable(tabId) {
        $(tabId).DataTable().ajax.reload();
    }
</script>
<?php loadPartial('layout/footer')?>

