<?php
require_once '../../config/config_inc.php';
?>

<?php loadPartial('layout/top', ['query' => $_SESSION[SESSIONROOT]['pages']])?>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <form class="card" tabindex="-1" id="poll-qr">
                        <div class=" card-header p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <h3 id="poll-qr-title" class="card-title"></h3>
                                <h5 id="poll-qr-description" class="mt-2"></h5>
                            </div>
                        </div>

                        <div id="poll-qr-body" class="card-body p-4">

                        </div>

                        <div id="action-buttons" class="card-footer d-flex gap-4 justify-content-end align-items-center">
                            <div id="error-poll-qr" class="d-none text-danger">
                                Non hai risposto a tutte le domande obbligatorie!
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Invia</button>
                            </div>
                        </div>
            </form>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/bottom')?>
<?php loadPartial('layout/scripts/qr-poll')?>
<?php loadPartial('layout/footer')?>