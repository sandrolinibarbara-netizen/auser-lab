<?php
require_once __DIR__.'/../../../vendor/autoload.php';
session_start();
if (isset($_SESSION[SESSIONROOT]['timer']) && $_SESSION[SESSIONROOT]['timer'] < time()) {
    $cart = new Ecommerce();
    foreach($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']] as $item) {
        $cart->removeFromCart($item);
    }
    unset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]);
    unset($_SESSION[SESSIONROOT]['timer']);
}

?>
<!--comment-->

<?php loadPartial('layout/head');
loadPartial('layout/page');
loadPartial('layout/header-ecommerce');?>
    <div id="kt_app_content" class="app-content flex-column-fluid my-10">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="container px-4">
                <form class="mb-8 border border-success rounded" id="form">
                    <input class="form-control form-control-solid" id="search" placeholder="Cerca un corso"/>
                    <button type="submit" class="d-none">Cerca</button>
                </form>
                <div id="courses-events-grid" class="row g-4">
                    <div class="col-6">
                        <div class="card bg-light-bg h-600px">
                            <div class="card-body d-flex flex-column">
                                <img class="h-250px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/course.webp" alt=""/>
                                <h2 class="mt-12">
                                    Come faccio a registrarmi e tesserarmi?
                                </h2>
                                <p class="mt-2">
                                    Per iniziare, clicca sul tasto <strong>"Iscriviti"</strong> che trovi in alto a destra nella homepage.
                                    Compila il modulo inserendo tutti i dati richiesti: ti verrà chiesto un indirizzo email valido,
                                    che dovrai confermare durante la registrazione.
                                    Se possiedi già una <strong>tessera Auser in corso di validità</strong>, inserisci il numero della tessera
                                    nell’apposito campo. Se invece <strong>non hai ancora una tessera</strong>, potrai comunque completare
                                    la registrazione e procedere al tesseramento in un secondo momento.
                                </p>
                                <div class="w-100 text-end mt-auto">
                                    <button type="submit" class="btn btn-success mt-4">
                                        Continua &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php
loadPartial('layout/footer-ecommerce');
loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
    <script src="<?=ROOT?>app/modules/partials/components/ecommerce/search.js"></script>
    <script src="<?=ROOT?>app/helpers.js"></script>
    <script>
        load();
    </script>
<?php loadPartial('layout/footer')?>