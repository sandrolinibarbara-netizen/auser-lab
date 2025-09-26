<?php
    $total = 0;
    foreach($data['courses'] as $item) {
        $total += $item['importo'];
    }
    foreach($data['events'] as $item) {
        $total += $item['importo'];
    }
?>
<?php
loadPartial('layout/head');
loadPartial('layout/page');
loadPartial('layout/header-ecommerce');
?>

<section>
    <?php if(isset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]) && count($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]) > 0) : ?>
    <div class="w-75 mx-auto my-7 py-2 px-4">
        <a href="<?=ROOT?>home?show=ecommerce">&larr; Torna allo shop</a>
    </div>
        <div class="card w-75 mx-auto my-7 py-2 px-4">
        <?php foreach($data['courses'] as $item) :
            $isOndemandCourse = isset($item['data_inizio']) && $item['data_inizio'] === '01/01/3000';
            $courseHasEndDate = isset($item['data_fine']) && $item['data_fine'] !== '01/01/3000';
        ?>
            <div class="w-100 bg-gray-200 my-2 rounded">
                <div class="d-flex align-items-center justify-content-between gap-8" style="width: 95%">
                    <div class="d-flex align-items-center gap-8">
                        <img class="rounded w-150px h-100px" style="object-fit: cover; object-position: center" alt="course-image" src="<?=ROOT.'app/assets/uploaded-files/heros-images/Wavy_Edu-02_Single-01.jpg'?>"/>
                        <div>
                            <p class="fs-4 fw-semibold mb-0"><?= $item['corso'] ?></p>
                            <?php if(!$isOndemandCourse): ?>
                                <p class="fs-6 fw-semibold mb-0">Inizio corso: <?= $item['data_inizio'] ?></p>
                                <?php if($courseHasEndDate): ?>
                                    <p class="fs-6 fw-semibold mb-0">Fine corso: <?= $item['data_fine'] ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-8">
                        <p class="fs-4 fw-semibold mb-0">€ <?= $item['importo'] ?>,00</p>
                        <button value="<?= 'c-'.$item['id'] ?>" class="remove-button btn-active-danger btn bg-gray-100 btn-hover-outline"><i class="ki-outline ki-trash text-auser fs-6 p-0"></i></button>
                    </div>
                </div>
            </div>
                <?php endforeach;?>
            <?php foreach($data['events'] as $item) :
                $isOndemandEvent = isset($item['data_inizio']) && $item['data_inizio'] === '01/01/3000';
            ?>
                <div class="w-100 bg-gray-200 my-2 rounded">
                    <div class="d-flex align-items-center justify-content-between gap-8" style="width: 95%">
                        <div class="d-flex align-items-center gap-8">
                            <img class="rounded w-150px h-100px" style="object-fit: cover; object-position: center" alt="course-image" src="<?=ROOT.'app/assets/uploaded-files/heros-images/Wavy_Edu-02_Single-01.jpg'?>"/>
                            <div>
                                <p class="fs-4 fw-semibold mb-0"><?= $item['diretta'] ?></p>
                                <?php if(!$isOndemandEvent): ?>
                                    <p class="fs-6 fw-semibold mb-0">Inizio corso: <?= $item['data_inizio'] ?></p>
                                <?php endif; ?>
                                <p class="fs-6 fw-semibold mb-0">Orario: <?= $item['orario_inizio'].'-'.$item['orario_fine'] ?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-8">
                            <p class="fs-4 fw-semibold mb-0">€ <?= $item['importo'] ?>,00</p>
                            <button value="<?= 'e-'.$item['id'] ?>" class="remove-button btn-active-danger btn bg-gray-100 btn-hover-outline"><i class="ki-outline ki-trash text-auser fs-6 p-0"></i></button>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
            <div class="card-footer fs-3 fw-bold text-end w-100 p-8">
                Totale: € <?= $total ?>,00
            </div>
        </div>
        <div class="card bg-light-bg w-75 mx-auto text-center mt-12 p-8 gap-4">
            <h3 class="m-0">Scegli il metodo di pagamento</h3>
            <div class="w-100 mt-4 d-flex gap-4 align-items-center justify-content-center">
                <a href="<?=ROOT?>bonifico" class="btn btn-secondary btn-active-success">
                    <i class="ki-outline ki-bank text-auser fs-5 p-0"></i>
                    Bonifico bancario</a>
                <form action="<?= ROOT . 'app/modules/stripe/checkout.php' ?>" method="POST">
                    <button class="btn btn-secondary btn-active-success" type="submit" id="checkout-button">
                        <i class="ki-outline ki-credit-cart text-auser fs-5 p-0"></i>
                        Carta di debito o credito</button>
                </form>
            </div>
                <div class="alert alert-secondary d-flex align-items-center p-5 mt-2 mb-0">
                    <i class="ki-duotone ki-shield-tick fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column align-items-start">
                        <span class="text-start text-gray-600">Scegliendo l'opzione <em>Bonifico bancario</em> verrai reindirizzato a una pagina dove ti saranno comunicati i dati per effettuare il bonifico.<br/>Scegliendo l'opzione <em>Carta di debito o credito</em> verrai invece reindirizzato alla pagina di checkout di Stripe, dove dovrai inserire i dati della tua carta per procedere al pagamento.</span>
                    </div>
                </div>
        </div>
    <?php elseif(!isset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]) || count($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]) == 0) : ?>
    <div class="card w-75 mx-auto my-7 px-8 pb-8 pt-10 text-center">
        <h2 >Ooops! Com'è vuoto qui...</h2>
        <p class="my-4">Torna alla <a href="<?=ROOT?>home?show=ecommerce">pagina principale</a> per fare acquisti!</p>
    </div>
    <?php endif; ?>
</section>
    <input type="text" id="root" name="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/end-page');
loadPartial('layout/scripts/base');?>
    <script src="<?=ROOT?>app/modules/partials/components/ecommerce/cartManagement.js"></script>
<?php loadPartial('layout/footer')?>