<?php
require_once '../../config/config_inc.php';
?>
<?php
loadPartial('layout/head');
loadPartial('layout/page');
loadPartial('layout/header-ecommerce');
?>

    <div class="card w-75 mx-auto my-7 px-8 pb-8 pt-10 text-center">
        <h2>Transazione effettuata con successo!</h2>
        <p class="my-4">Torna alla <a href="<?=ROOT?>home?show=ecommerce">pagina principale</a></p>
    </div>
    <input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
    <script src="<?=ROOT?>app/modules/partials/components/ecommerce/cartManagement.js"></script>
<?php loadPartial('layout/footer')?>