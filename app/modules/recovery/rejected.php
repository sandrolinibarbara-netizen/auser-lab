<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

?>
<?php
loadPartial('layout/head');
loadPartial('layout/page');
loadPartial('layout/header-ecommerce');
?>

<div class="card w-75 mx-auto my-7">
    <div class="card-body text-center">
        <h2 class="mt-6 mb-8">Questo link non è più valido.</h2>
        <p class="mb-2 fs-4"><span class="fw-bold">COSA PUÒ ESSERE ANDATO STORTO?</span><br/>Il link di conferma è valido 1 ora dal momento dell'invio, oppure ti è già stata inviata un'altra mail con un altro link, o magari hai già completato la procedura per cambiare la tua password.</p>
    </div>
    <div class="w-100 text-end p-7">
        <a href="<?= ROOT . 'login'?>">&larr; Torna alla homepage</a>
    </div>
</div>

<input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
<?php loadPartial('layout/footer')?>
