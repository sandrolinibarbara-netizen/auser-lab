<?php
require_once '../../config/config_inc.php';
$query = $_SESSION[SESSIONROOT]['pages'];

?>

<?php loadPartial('layout/top', ['query' => $query])?>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row row-cols-2 gx-4">
                <div class="col">
                    <div class="card bg-surface min-h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/course.jpg" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Come faccio a registrarmi e tesserarmi?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button id="accordion-button" class="fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                        <div id="accordion-text" class="px-8 overflow-hidden" style="max-height: 0; transition: max-height 1s;">
                            <p>
                                1. Accesso al sito web: Prima di tutto, è necessario accedere al sito web del circolo.
                                Questo può essere fatto digitando l'URL del sito nel browser web o seguendo un link fornito tramite email
                                o sui social media del circolo.
                            </p>
                            <p>
                                2. Navigazione alla pagina di registrazione o di tesseramento: Una volta sul sito web del circolo,
                                cerca un link o un pulsante che ti permetta di registrarti come nuovo membro o di tesserarti.
                                Questo link potrebbe essere collocato nel menu principale del sito o in una sezione dedicata come
                                "Iscriviti" o "Tesseramento".
                            </p>
                            <p>
                                3. Compilazione del modulo di registrazione: Una volta trovata la pagina di registrazione o di tesseramento,
                                sarai probabilmente guidato attraverso un modulo online da compilare. Questo modulo potrebbe richiedere
                                informazioni personali come nome, cognome, data di nascita, indirizzo email, numero di telefono,
                                e altri dettagli pertinenti. Potresti anche essere tenuto a fornire informazioni aggiuntive, come
                                il tipo di tessera che desideri (se il circolo offre più opzioni di adesione) e le modalità di pagamento.
                            </p>
                            <p>
                                4. Creazione di un account utente (se necessario): In alcuni casi, potrebbe essere richiesto di creare un
                                account utente sul sito web del circolo. Questo ti permetterà di accedere in futuro per gestire la tua
                                tessera, visualizzare eventi e comunicazioni del circolo e altro ancora. Durante questo processo,
                                potrebbe essere necessario scegliere un nome utente e una password per il tuo account.
                            </p>
                            <p>
                                5. Revisione delle informazioni e conferma: Prima di inviare il modulo di registrazione, assicurati di
                                rivedere attentamente tutte le informazioni fornite per assicurarti che siano corrette e complete.
                                Una volta verificate, potrebbe essere necessario confermare la tua registrazione o il tuo tesseramento
                                facendo clic su un pulsante apposito.
                            </p>
                            <p>
                                6. Pagamento della quota di tesseramento (se richiesto): Se il circolo richiede il pagamento di una
                                quota di tesseramento, sarai probabilmente indirizzato a una pagina di pagamento sicura dove potrai
                                inserire i dettagli della tua carta di credito o altri metodi di pagamento accettati.
                            </p>
                            <p>
                                7. Ricezione della conferma: Dopo aver completato il processo di registrazione o di tesseramento e,
                                se necessario, il pagamento della quota, dovresti ricevere una conferma via email o una pagina di
                                conferma sul sito web del circolo. Questa conferma ti informerà che la tua registrazione è stata
                                completata con successo e potrebbe contenere informazioni aggiuntive, come il numero di tessera o
                                le istruzioni per accedere all'area riservata ai membri del circolo sul sito web.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-surface h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/event.jpg" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Posso versare un contributo per un evento e un corso contemporaneamente?</h3>
                            </div>
                        </div>
                            <div class="card-footer pt-6 pb-8 px-8 border-0">
                                <button class="fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                            </div>
                    </div>
                </div>
            </div>
            <div class="row row-cols-2 gx-4 mt-4">
                <div class="col">
                    <div class="card bg-surface h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/payment.jpg" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Se non posso versare un contributo online o tramite bonifico, come faccio?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-surface h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/data.jpg" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Le mie informazioni dove vengono salvate?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <input type="text" id="root" value="<?php echo ROOT;?>" hidden readonly>
<?php loadPartial('layout/bottom')?>
<script src=<?=ROOT.'app/modules/faq/accordion.js'?>></script>
<?php loadPartial('layout/footer')?>