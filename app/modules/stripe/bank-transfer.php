<?php
require_once '../../config/config_inc.php';

?>
<?php
loadPartial('layout/head');
loadPartial('layout/page');
loadPartial('layout/header-ecommerce');
?>

    <section>
        <div class="card w-75 mx-auto my-7 px-8 pb-8 pt-10 text-center">
            <div class="card-header">
                <h2 class="card-title m-0 pb-8">Dati per il bonifico bancario</h2>
            </div>
            <div class="card-body">
                <ul class="list-group text-start">
                    <li class="list-group-item p-0">
                        <span class="w-100px d-inline-block rounded-top bg-light-bg me-2" style="padding: calc(0.75rem - 1px)">Intestatario</span> Auser
                    </li>
                    <li class="list-group-item p-0">
                        <span class="w-100px d-inline-block bg-light-bg me-2" style="padding: calc(0.75rem - 1px)">Banca</span> Banca Sella, filiale Piazza Stradivari, 26100 Cremona (CR)
                    </li>
                    <li class="list-group-item p-0">
                        <span class="w-100px d-inline-block bg-light-bg me-2" style="padding: calc(0.75rem - 1px)">IBAN</span> NL69ABNA8835804299
                    </li>
                    <li class="list-group-item p-0">
                        <span class="w-100px d-inline-block rounded-bottom bg-light-bg me-2" style="padding: calc(0.75rem - 1px)">Causale</span> Contributo iscrizione *nomi corsi* - *nome cognome*
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <div class="alert alert-secondary d-flex align-items-center p-5">
                    <i class="ki-duotone ki-shield-tick fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column align-items-start">
                        <h4 class="mb-1 text-gray-600">Attenzione!</h4>
                        <span class="text-start text-gray-600">Cliccando il bottone sottostante verrai iscritto ai corsi che hai scelto.<br/>I corsi non saranno visibili nella tua Area Riservata fino a che non avrai effettuato il bonifico e un addetto di Auser non ne avrà verificata l'effettiva ricezione.<br/>Il tuo carrello verrà svuotato.</span>
                    </div>
                </div>
                <a href="<?=ROOT . 'success/empty-and-subscribe?bank-transfer=confirmed'?>" class="btn btn-success mt-4">Ho preso nota dei dati per il bonifico</a>
            </div>
        </div>
            <p class="w-75 mx-auto my-6 text-center">Torna alla <a href="<?=ROOT?>home?show=ecommerce">pagina principale</a> per continuare a fare acquisti!</p>
    </section>
    <input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
    <script src="<?=ROOT?>app/modules/partials/components/ecommerce/cartManagement.js"></script>
<?php loadPartial('layout/footer')?>