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
        <h2 class="mt-6 mb-8">La tua iscrizione è stata confermata!</h2>
        <p class="mb-2 fs-4">Ora puoi eseguire l'accesso alla piattaforma andando alla pagina di <a href="<?=ROOT.'login'?>">login</a>.</p>
        <p class="fs-4">Oppure puoi tornare alla <a href="<?=ROOT?>home?show=ecommerce">homepage</a> e curiosare tra i nostri corsi ed eventi!</p>
    </div>
</div>

<input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
<?php loadPartial('layout/footer')?>
