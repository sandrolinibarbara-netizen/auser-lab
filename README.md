#   Piattaforma Auser Lab

## Panoramica
Auser Lab è una piattaforma web PHP pensata per
* creare e gestire corsi ed eventi live o registrati, lezioni e relativi materiali didattici
* rendere queste iniziative acquistabili tramite e-commerce
* mettere a disposizione i materiali acquistati

I moduli lato amministrazione consentono di creare corsi ed eventi, monitorare le iscrizioni, generare registri e attestati, creare materiali per le lezioni, e gestire community e forum; i moduli lato utente forniscono pagine per l'acquisto e la fruizione dei contenuti.

## Stack tecnologico

### Backend
* PHP 7.4
* Librerie esterne PHP:
  * Medoo, per la configurazione e l'accesso al database
  * libreria client per PHP di Google Storage, per l'upload e la rimozione di video di grandi dimensioni
  * Stripe SDK, per la gestione dei pagamenti
  * PHPMailer, per la gestione dell'invio di email
  * PhpSpreadsheet, per la generazione di spreadsheet
  * TCPDF, per la generazione di pdf
  * php-qrcode per la generazione di QR code

### Frontend
* Componenti UI del toolkit Metronic (`metronic/`) con bundle JS/CSS caricati in base alla pagina. Le liste dei bundle sono elencate nei file delle pagine omonime in `app/modules/partials/layout/scripts`.
* Librerie esterne Javascript:
  * video.js, impiegata principalmente per la possibilità di poter aggiungere a un video caricato dei marker a cui associare materiali. **La libreria non riceve aggiornamenti e patch di sicurezza da diversi anni, ma al momento pare essere l'unica soluzione disponibile.** 

## Struttura del progetto

```
    app
     |-- assets
            |-- certificates
            |-- documents
            |-- images
            |-- qr-codes
            |-- svgs
            |-- uploaded-files
            |-- videos
     |-- classes
            |-- include.php
            |-- [... file .php contenenti le classi] 
     |-- config
            |-- config_inc.php
     |-- constants
            |-- auser-prova-681b1e691b41.json
            |-- include.php
            |-- system.php
     |-- controllers
            |-- temp_chunks
            |-- include.php
            |-- [... file .php contenenti i controller] 
     |-- modules
            |-- [... cartelle contenenti le pagine]
            |-- partials
                    |-- components
                            |-- [... cartelle contenenti i componenti delle pagine omonime e delle modali]
                    |-- layout
                            |-- menus
                                    |-- [... file .php contenenti i parziali che compongono i menu]
                            |-- scripts
                                    |-- [... file .php contenenti gli script delle pagine omonime]
                            |-- [... file .php contenenti i parziali che compongono la struttura base delle pagine]
            |-- [... cartelle contenenti le pagine]
     |-- courseGeneral.js
     |-- eventGeneral.js
     |-- helpers.js
     |-- helpers.php
     |-- lectureGeneral.js
     |-- lessonGeneral.js
     |-- loader.css
     |-- pollGeneral.js
     |-- scripts.php
     |-- style.php
     |-- surveyGeneral.js
    db-calls
     |-- login
            |-- function.php
     |-- [... cartelle di backup con le query in MySQL]
    metronic
     |-- [... cartelle del tema di Metronic]
    tools
     |-- [... cartelle e file per il download del tema di Metronic]
    .htaccess
    composer.json
    composer.lock
    composer.phar
    index.php
    package.json
    package-lock.json
```    

### app / assets
Contiene le cartelle per il salvataggio di file e immagini di dimensioni contenute e che non vengono caricati nel bucket di Google Cloud Storage.

### app / classes
Contiene i file .php con le classi, che possono essere raggruppate in quattro categorie:

1. Classi per la gestione degli accessi, la validazione della sessione e la configurazione del database

>* BaseModel.php
>* Database.php
>* Login.php

2. Classi che rappresentano le entità delle tabelle del database, estendendo `BaseModel`

>* Category.php
>* Course.php
>* Group.php
>* LectureNote.php
>* Lesson.php
>* Marker.php
>* Page.php
>* Poll.php
>* Section.php
>* Speaker.php
>* Sponsor.php
>* Survey.php
>* Thread.php
>* User.php

3. Classi che raggruppano simili tipologie di azioni o entità. Anche queste estendono `BaseModel`

>* Creation.php
>* Ecommerce.php
>* FutureEvents.php
>* GeneralGetter.php

4. Classi per l'implementazione delle librerie esterne PHP. Le librerie di Stripe e TCPDF, nonché le librerie Javascript, sono invece utilizzate direttamente dove necessarie, senza passare per estensioni intermedie delle classi parent

>* Database.php
>* Email.php
>* QR.php
>* Sheet.php
>* Storage.php

### app / config
Contiene il file `config_inc.php`, che avvia la sessione e gestisce eventuali redirect al momento dell'arrivo su una pagina in caso di token scaduto o utente non autenticato.

### app / constants
Contiene i file con le costanti globali, alcune chiavi e i dati per la configurazione di database e server mail.
#### app / constants / auser-prova-681b1e691b41.json
Non versionato, contiene le informazioni e la key del service account di Google Cloud Storage, necessario per l'autenticazione delle chiamate alla API di Google Cloud Storage per le operazioni di upload ed eliminazione dei file dal bucket.\
Il bucket **auser-zoom-meetings** (all'interno del progetto **auser-prova**) è destinato ai video delle video-lezioni e alle registrazioni delle call su Zoom.
È **pubblico**, per cui chiunque possieda gli url dei video può visualizzarli.\
_TODO: implementare la restituzione di url firmati e rendere il bucket non pubblico._
#### app / constants / system.php
File che definisce i parametri per l'ambiente locale e di produzione: connessione MySQL, URL di base, directory di upload, durata sessione/carrello, credenziali email SMTP e chiave SDK Zoom.
### app/constants/include.php 
File che mappa i nomi delle tabelle utilizzati dai modelli Medoo, semplificando le query e centralizzando le modifiche allo schema.

### app / controllers
Lontani dall'avere l'aspetto di controller normali, sono un compromesso tra il progetto su cui Auser Lab è basato e la necessità di un livello aggiuntivo di sicurezza e organizzazione del codice.\
All'interno di ogni controller viene determinata la classe di cui creare un'istanza e invocare una funzione, per poi ritornare alla view il suo esito (siano essi dati, il successo della chiamata o il suo fallimento). La determinazione della classe di cui creare un'istanza dipende dalla soddisfazione di alcune condizioni:
* nel caso al controller si sia reindirizzati dal file `.htaccess`, all'interno del controller viene effettuato un check sui query parameters dell'url contenuti in `$_GET`
```
if(isset($_GET['update']) && $_GET['update'] == 'category') {

    require_once '../config/config_inc.php';
    
    $category = new Category($_GET['id']);
    $parsed = $category->get();
    
    loadView('categories', ['parsed' => $parsed], '/update-category.php');
    
}
```
* nel caso al controller si sia indirizzati da una chiamata ajax, all'interno del controller viene effettuato un check sul contenuto di `$_POST`
```
if(isset($_POST['action']) && $_POST['action'] === 'getAssociatedCategories') {

    require_once '../config/config_inc.php';

    $category = new Category($_POST['id']);
    $result = $category->getAssociatedEvents();

    echo json_encode($result);
    
}
```
#### app / controllers / temp_chunks
Cartella per il salvataggio temporaneo dei diversi chunks in cui viene suddiviso un video prima di essere caricato su Google Cloud Storage.

### app / modules
Cartella contenente le views. A ogni sottocartella corrisponde una pagina, con l'eccezione di `partials`, in cui sono salvati i componenti che compongono le diverse pagine.
#### app / modules / partials / components
La maggior parte delle cartelle contiene i componenti delle pagine omonime, mentre un numero minore è dedicato alle modali.
#### app / modules / partials / layout
Contiene i file con i parziali che compongono la struttura base della pagina e la lista degli script da importare nelle pagine omonime.

### app / [entity]General.js
Dal momento che ogni tipo di iniziativa e ogni tipo di materiale prevede sia un'interfaccia di creazione sia un'interfaccia di modifica dei dati già inseriti, in questi file (che sono uno per iniziativa/materiale) sono riunite le funzioni comuni a entrambe le interfacce. Le funzioni specifiche di ogni interfaccia sono invece nelle relative cartelle in `app/modules/partials/components`.

### app / helpers.js
File con funzioni per la gestione dei tooltip e degli input per le opzioni video nell'interfaccia di creazione di lezioni ed eventi.

### app / helpers.php
File con funzioni di crittografia, utilities per la formattazione delle date e il caricamento di views e parziali.

### db-calls / login / function.php
Funzione di login, lasciata nella posizione della precedente organizzazione del progetto.

### metronic e tools
Codice sorgente del template Metronic e toolchain front-end (Gulp/Webpack) con script di build, watch e sviluppo locale

### .htaccess
File per la gestione del routing. A seconda dell'url, il reindirizzamento avviene verso una pagine o verso un controller.

### index.php
File di avvio del bootstrap che forza l'inclusione della configurazione di sessione.

[//]: # (Da controllare)

## Architettura runtime
1. **Bootstrap** – Ogni richiesta passa da `index.php`, che abilita error reporting e include `config_inc.php`. Quest'ultimo verifica la sessione, istanzia `Database` (quindi Medoo) e reindirizza gli utenti non autenticati al modulo di login.
2. **Routing controller** – I file in `app/controllers/` rispondono a parametri `GET`/`POST` (tipicamente `action`) per orchestrare le operazioni sui modelli e restituire output JSON o caricare viste dedicate. Ad esempio `CourseController.php` gestisce la pubblicazione di bozze, la creazione di lezioni, la generazione del registro e l'esportazione in Excel.
3. **Caricamento viste** – Gli helper `loadPartial`, `loadView` e `loadSubView` permettono di comporre l'interfaccia suddividendo layout Metronic, componenti riutilizzabili e viste contestuali. Ciò facilita la separazione tra template e logica di business.
4. **Endpoint AJAX** – Gli script in `db-calls/` alimentano DataTables e widget front-end: estraggono parametri di filtraggio/paginazione, costruiscono query dinamiche e restituiscono JSON già formattato (icone incluse).

## Funzionalità principali
### Autenticazione e gestione sessioni
* Il login utilizza credenziali cifrate (`cryptStr`) salvate nel database e convalida token con scadenza configurabile (`SESSIONDURATION`). In caso di successo vengono aggiornati i timestamp della sessione e salvate informazioni aggiuntive (es. scadenza tessera).【F:app/classes/Database.php†L69-L106】
* La pagina `app/modules/login/login.php` offre il form di accesso Metronic e link al recupero password/registrazione. Gli script front-end associati gestiscono la validazione client-side.【F:app/modules/login/login.php†L1-L87】

### Gestione corsi ed eventi
* La classe `Course` centralizza registri presenze, bozze e versioni e-commerce dei corsi, oltre a forum, duplicazione e abilitazione di studenti privati.【F:app/classes/Course.php†L1-L199】
* Le viste in `app/modules/courses-events/` forniscono dashboard per creare nuovi corsi/eventi, visualizzare bozze e accedere alle tabelle DataTables, alimentate dagli endpoint `db-calls`.【F:app/modules/courses-events/index.php†L1-L75】【F:db-calls/DONE-courses/DONE-function-courses.php†L1-L115】
* Gli eventi live sono modellati da `Lesson`, che offre recupero dei dati per bozze, gestione sponsor/materiali, disponibilità posti e versione e-commerce (inclusa verifica tesseramento e modalità fruizione).【F:app/classes/Lesson.php†L1-L200】

### Dirette, learning experience e interazione
* `LiveController` espone endpoint per caricare pagine di streaming, inviare materiali live (dispense, sondaggi, survey) e raccogliere risposte o marker temporali, oltre a inoltrare domande via email ai docenti.【F:app/controllers/LiveController.php†L1-L67】
* La classe `Lesson` gestisce la trasformazione di eventi in offerte e-commerce, calcolando durata, modalità (remoto/presenza), disponibilità posti e verifica iscrizione dell'utente.【F:app/classes/Lesson.php†L69-L160】

### Materiali, registri e attestati
* `Course::getRegister` aggrega presenze per generare tabelle DataTables e offre pulsanti azione contestuali (modifica, commenti, consegna elaborati).【F:app/classes/Course.php†L22-L109】
* Il modulo `app/modules/certificates/pdf.php` usa TCPDF per produrre attestati personalizzati con layout grafico e dati del corso/partecipante.【F:app/modules/certificates/pdf.php†L1-L110】

### Sondaggi e quiz
* `Poll` e `Survey` consentono di creare, duplicare, pubblicare e distribuire questionari live e asincroni, con supporto a diverse tipologie di domanda e raccolta risposte. Durante una diretta, gli utenti possono inviare risposte che vengono marcate come completate.【F:app/classes/Poll.php†L1-L189】【F:app/classes/Survey.php†L1-L155】【F:app/controllers/LiveController.php†L17-L48】

### Comunicazioni e notifiche
* La classe `Email` estende PHPMailer, configurandosi tramite le costanti SMTP e fornendo metodi per inviare email puntuali (es. domande a docenti) o massive (inviti a corsi/eventi).【F:app/classes/Email.php†L1-L124】【F:app/constants/system.php†L53-L71】

### E-commerce, carrello e pagamenti
* `Ecommerce` gestisce catalogo corsi/eventi pubblici e on-demand, compone card informative (prezzo, insegnanti, modalità), mantiene il carrello in sessione con timer e aggiorna iscrizioni temporanee/finali per corsi gratuiti.【F:app/classes/Ecommerce.php†L1-L302】
* La vista `app/modules/ecommerce/index.php` mostra il catalogo filtrabile lato utente e attiva gli script JavaScript di ricerca e caricamento asincrono.【F:app/modules/ecommerce/index.php†L1-L66】
* Il checkout Stripe crea sessioni di pagamento dalla selezione nel carrello, differenziando corsi ed eventi e reindirizzando alle pagine di successo o annullamento.【F:app/modules/stripe/checkout.php†L1-L47】

## Buone pratiche e manutenzione
* **Sicurezza** – Non committare credenziali reali: sostituire i valori in `app/constants/system.php` con variabili d'ambiente o file esclusi dal VCS. Valutare l'uso di HTTPS e aggiornare le chiavi AES/IV se si modifica la logica di cifratura.【F:app/constants/system.php†L3-L75】【F:app/helpers.php†L51-L70】
* **Pulizia del carrello** – Il carrello è basato su sessione e viene ripulito quando scade il timer (`CARTDURATION`) sia lato front-end (inizializzazione in `app/modules/ecommerce/index.php`) sia lato server (`Ecommerce::removeTemp`). Pianificare cron job che invochino periodicamente il metodo `removeTemp` per liberare prenotazioni scadute.【F:app/modules/ecommerce/index.php†L1-L33】【F:app/classes/Ecommerce.php†L218-L302】
* **Estensione delle viste** – Utilizzare gli helper `loadPartial`/`loadView` per mantenere riutilizzabili layout e componenti, seguendo la struttura Metronic già presente. Evitare inclusioni dirette fuori da questi metodi per preservare l'organizzazione esistente.【F:app/helpers.php†L180-L200】
* **Nuovi endpoint** – Seguire la convenzione degli script `db-calls` (validazione input, costruzione query parametrizzate, restituzione di JSON `draw`/`recordsTotal`) per mantenere coerente l'integrazione con DataTables.【F:db-calls/DONE-courses/DONE-function-courses.php†L1-L115】

## Risorse aggiuntive
* **Strumenti di sviluppo** – i task Gulp disponibili (`npm run build`, `npm run watch`, `npm run localhost`) sono definiti in `tools/gulpfile.js` e relativi moduli. Utilizzarli per compilare asset e avviare un server di sviluppo con ricarica automatica.【F:tools/gulpfile.js†L1-L31】
* **Template Metronic** – consultare la directory `metronic/` per esempi di componenti, pagine e configurazioni già pronte che possono essere riutilizzate o adattate.【1d80d8†L1-L3】

Questa documentazione fornisce una visione complessiva del progetto e delle sue principali responsabilità. Per funzionalità non coperte (es. forum, gestione sponsor, materiali avanzati) consultare i rispettivi modelli/controller in `app/classes` e `app/controllers`, seguendo le convenzioni illustrate.