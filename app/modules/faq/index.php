<?php
require_once '../../config/config_inc.php';
$query = $_SESSION[SESSIONROOT]['pages'];

?>

<?php loadPartial('layout/top', ['query' => $query])?>

    <div id="kt_app_content" class="mb-12 app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row row-cols-2 gx-4">
                <div class="col">
                    <div class="card bg-surface min-h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/typing.webp" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>A cosa posso accedere senza tesserarmi?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="accordion-button fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                        <div class="accordion-text px-8 overflow-hidden" style="max-height: 0; transition: max-height 1s;">
                            <div>
                                <p>Senza tesseramento puoi accedere liberamente a tutti i contenuti gratuiti disponibili nella
                                    piattaforma. Potrai trovare disponibili eventi ed iniziative promosse da Auser Unipop, dei
                                    documentari dedicati alla realtà del nostro territorio e la presentazione di corsi ed attività in
                                    programma. Questi ultimi materiali in particolare sono pensati per offrire un primo sguardo alle
                                    nostre attività e sono consultabili senza alcun bisogno di procedere al tesseramento.</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-surface min-h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/writing.webp" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>A cosa posso accedere con il tesseramento?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="accordion-button fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                        <div class="accordion-text px-8 overflow-hidden" style="max-height: 0; transition: max-height 1s;">
                            <div>
                                <p>Con il tesseramento ad Auser Unipop avrai accesso a contenuti riservati ai soli soci. Troverai corsi,
                                    eventi, iniziative, gruppi di lettura e molto altro. In alcuni casi, per contenuti o attività particolari,
                                    verrà richiesto anche un contributo di partecipazione oltre al tesseramento.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-cols-2 gx-4 mt-4">
                <div class="col">
                    <div class="card bg-surface min-h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/course.webp" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Come faccio a tesserarmi?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="accordion-button fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                        <div class="accordion-text px-8 overflow-hidden" style="max-height: 0; transition: max-height 1s;">
                            <div>
                                <p>Per tesserarti, segui questi passaggi:</p>
                                <ul class="list-inline">
                                    <li class="ps-4 pb-1 pt-2">1. Visita il sito di <a href="https://www.auserunipopcremona.it/modulistica/">Auser Unipop</a>.</li>
                                    <li class="ps-4 pb-1">2. Compila il modulo con i tuoi dati personali.</li>
                                    <li class="ps-4 pb-1">3. Segui le istruzioni per il pagamento della quota.</li>
                                    <li class="ps-4 pb-1">4. Invia alla nostra mail <a href="mailto:unipop.cremona@auser.lombardia.it">unipop.cremona@auser.lombardia.it</a> il modulo compilato e la contabile
                                        del versamento della quota associativa.</li>
                                    <li class="ps-4">5. Riceverai una conferma via email con i dettagli della tua registrazione.</li>
                                </ul>

                                <p>
                                    In alternativa, puoi anche tesserarti di persona venendo negli Uffici di Auser Unipop, dove potrai
                                    versare la quota di partecipazione sia in contanti che con la carta.
                                    La nostra segreteria si trova presso la palazzina dell’ex portineria di Cremona Solidale in Via
                                    Brescia, 207 - Cremona, con i seguenti orari di apertura:</p>
                                <ul>
                                    <li>lunedì, martedì e giovedì 10.00/13.00 - 14.00/18.00</li>
                                    <li>mercoledì 10.00/16.00 (orario continuato)</li>
                                    <li>venerdì 10.00/13.00</li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-surface min-h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/event.webp" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Posso versare un contributo per un corso e un evento insieme?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="accordion-button fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                        <div class="accordion-text px-8 overflow-hidden" style="max-height: 0; transition: max-height 1s;">
                            <div>
                                <p>Sì, è possibile e anche consigliato se sai già a quali attività vuoi partecipare.
                                    Se, ad esempio, ti sei iscritto a un corso e vuoi anche partecipare a un evento in programma, o
                                    altri corsi, puoi effettuare un unico pagamento che copra entrambe le quote. In questo modo
                                    risparmi tempo e rendi più semplice la gestione sia per te che per la segreteria.
                                    Ti basterà indicare chiaramente a cosa si riferisce il versamento (corso, evento, o entrambi) al
                                    momento del pagamento, sia che tu lo faccia online, con bonifico o di persona.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-cols-2 gx-4 mt-4">
                <div class="col">
                    <div class="card bg-surface min-h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/payment.webp" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Se non posso versare un contributo online o tramite bonifico, come posso fare?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="accordion-button fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                        <div class="accordion-text px-8 overflow-hidden" style="max-height: 0; transition: max-height 1s;">
                            <div>
                                <p>Se non hai la possibilità di effettuare il pagamento online o tramite bonifico bancario, puoi versare il
                                    contributo direttamente presso la nostra segreteria.
                                    Accettiamo pagamenti sia in contanti che con carta (bancomat o carta di credito).</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-surface min-h-300px">
                        <div class="card-body pb-0">
                            <img class="h-125px w-100 object-fit-cover rounded" src="<?=ROOT?>app/assets/images/data.webp" alt=""/>
                            <div class="w-100 card-title pt-8">
                                <h3>Dove vengono salvate le mie informazioni personali?</h3>
                            </div>
                        </div>
                        <div class="card-footer pt-6 pb-8 px-8 border-0">
                            <button class="accordion-button fw-bold bg-surface border-0 text-decoration-underline" style="color: #a5a5a5" href="/">Leggi di più</button>
                        </div>
                        <div class="accordion-text px-8 overflow-hidden" style="max-height: 0; transition: max-height 1s;">
                            <div>
                                <p>I tuoi dati vengono raccolti solo per finalità legate alla tua registrazione, tesseramento o
                                    partecipazione alle nostre attività.
                                    Una volta inseriti, vengono archiviati all'interno di sistemi digitali protetti, accessibili solo da
                                    personale autorizzato.
                                    In particolare:</p>
                                <ul>
                                    <li>Utilizziamo piattaforme sicure per la gestione dei dati, con accessi controllati.</li>
                                    <li>Le informazioni non vengono in alcun modo cedute o vendute a terzi.</li>
                                    <li>I dati sono trattati nel rispetto del Regolamento Generale sulla Protezione dei Dati
                                    (GDPR - Reg. UE 2016/679).</li>
                                    <li>Puoi in qualsiasi momento richiedere l’accesso, la modifica o la cancellazione dei tuoi dati
                                    personali.</li>
                                </ul>
                                    <p>In poche parole, ci impegniamo a proteggere la tua privacy e ad usare i tuoi dati solo per garantirti i
                                        servizi richiesti in modo trasparente e sicuro.</p>

                            </div>
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