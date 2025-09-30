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
All'interno di ogni controller viene determinata la classe di cui creare un'istanza e invocare una funzione. La determinazione della classe di cui creare un'istanza dipende dalla soddisfazione di alcune condizioni:
* nel caso al controller si sia reindirizzati dal file `.htaccess`, all'interno del controller viene effettuato un check sui query parameters dell'url contenuti in `$_GET`. In questi casi, vengono recuperati dati utilizzati per caricare le view relative
```
if(isset($_GET['update']) && $_GET['update'] == 'category') {

    require_once '../config/config_inc.php';
    
    $category = new Category($_GET['id']);
    $parsed = $category->get();
    
    loadView('categories', ['parsed' => $parsed], '/update-category.php');
    
}
```
* nel caso al controller si sia indirizzati da una chiamata ajax, all'interno del controller viene effettuato un check sul contenuto di `$_POST`, in particolare sulla proprietà `action`. In questi casi, vengono recuperati dati che poi vengono restituiti sotto forma di JSON al componente Javascript che ha effettuato la chiamata ajax
```
if(isset($_POST['action']) && $_POST['action'] === 'getAssociatedCategories') {

    require_once '../config/config_inc.php';

    $category = new Category($_POST['id']);
    $result = $category->getAssociatedEvents();

    echo json_encode($result);
    
}
```
Le funzioni invocate hanno due compiti principali, che possono essere anche svolti entrambi:
- modificare tabelle del database (creazione, modifica ed eliminazione di righe)
- recuperare informazioni dal database (incluse righe appena modificate)
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

## Architettura runtime
1. **Bootstrap** – Ogni richiesta passa da `index.php`, che abilita l'error reporting.
2. **Routing** – Il routing è gestito da `.htaccess`, che a seconda dell'url effettua un reindirizzamento verso una pagina o verso un controller. Nel caso la pagina sia privata, viene incluso `config_inc.php`, che verifica la sessione e reindirizza gli utenti non autenticati al modulo di login. Nel caso la pagina sia pubblica, viene incluso solamente `vendor/autoload.php`.
3. **Endpoint** – Le chiamate agli endpoint, in un primo momento radunate nella cartella `db-calls`, sono ora smistati nelle diverse classi.
4. **Caricamento views** – Per facilitare la separazione dei componenti UI dalla logica, si è implementato un sistema di elementi componibili e riutilizzabili. Gli helper `loadPartial` e `loadView` caricano le diverse parti del layout oltre ai componenti specifici delle diverse pagine.

## Funzionalità principali
### Autenticazione, gestione sessioni e permessi
* Il login utilizza credenziali cifrate tramite la funzione `cryptStr` in `helpers.php`, e convalida token con scadenza configurabile (`SESSIONDURATION`). In caso di successo vengono aggiornati i timestamp della sessione e salvate informazioni aggiuntive (es. scadenza tessera).
* Nella pagina `app/modules/login/login.php` si trova il componente Metronic della form di accesso e il link al recupero password/registrazione. Gli script front-end associati gestiscono la validazione client-side.
* Sono stati creati tre ruoli, a cui sono associati diversi permessi: admin, insegnante e studente.
### Visualizzazione e gestione di corsi ed eventi
La visualizzazione di corsi ed eventi avviene nelle sezioni `Dashboard`, con calendario e tabelle delle prossime iniziative, e `Corsi ed eventi`. A parte il calendario, uguale per tutti i tipi di utenti, le altre tabelle mostrano, per studenti e insegnanti, solamente le iniziative a cui questi sono iscritti, mentre agli admin mostrano tutte le iniziative pubblicate.
La gestione di corsi ed eventi è **riservata a insegnanti e admin**.
* La classe `Course` prevede:
  * il recupero dei dati dei corsi, sia in versione integrale che in versione ridotta, per la visualizzazione nell'ecommerce e nella dashboard;
  * il recupero dei dati dei corsi, sia in bozza che già pubblicati, per la loro modifica;
  * la modifica, duplicazione ed eliminazione di corsi pubblici e privati, in diretta od on demand;
  * la creazione delle lezioni associate a quel corso e il recupero della lista delle bozze di quelle non ancora pubblicate;
  * l'invito degli studenti ai corsi privati;
  * la creazione dei registri presenze basati sulle presenze degli studenti iscritti a un determinato corso e sul numero totale di lezioni previste per quel corso. Non esiste una classe Register.php;
  * la creazione dei forum associati ai corsi;
  * la creazione dei thread associati ai forum dei corsi.
* La classe `Lesson` gestisce le dirette. **Sono dirette sono sia le lezioni dei corsi, sia gli eventi**. La classe prevede: 
  * il recupero dei dati delle dirette, sia in versione integrale che in versione ridotta, per la visualizzazione nell'ecommerce (solo per gli eventi) e nella dashboard;
  * il recupero delle dirette, sia in bozza che già pubblicate, per la modifica;
  * la modifica ed eliminazione delle dirette;
  * il recupero e la modifica della lista di materiali (quiz e dispense), relatori e partner associati alle dirette;
  * il recupero della lista di marker assegnati all'eventuale video associato a una diretta.

### Materiali, compiti e sondaggi
- È stata effettuata una divisione tra materiali (dispense, quiz e sondaggi che gli studenti devono da compilare durante le dirette) e compiti (dispense e quiz da consultare e compilare tra le dirette).
- Nel caso in cui la lezione preveda la visualizzazione di un video caricato, a questo possono essere aggiunti dei marker con associati quiz da compilare o dispense da consultare.
- Agli studenti che partecipano in presenza viene data la possibilità di visualizzare e compilare i quiz associati a una lezione tramite inquadramento di un QR code. Per compilare i quiz dovranno comunque effettuare l'accesso al loro account.
- Materiali, compiti e sondaggi possono essere aggiunti da insegnanti e admin durante la creazione delle lezioni.

### Gestione permessi e utenti
- Gli admin possono gestire gli accessi alle diverse pagine della piattaforma (`Utenti > Permessi`);
- Gli admin inoltre possono effettuare tutte le operazione di gestione utente dalla pagina `Utenti > Iscritti`, in particolare:
  - confermare o invalidare l'avvenuto pagamento delle quote di iscrizione ai corsi e agli eventi tramite bonifico;
  - raccogliere e visualizzare le liberatorie e i documenti necessari al tesseramento;
  - confermare o invalidare i tesseramenti;
  - visualizzare gli storici dei tesseramenti e dei pagamenti delle quote di iscrizione ai corsi e agli eventi.

### E-commerce
- Le pagine dell'ecommerce sono pubbliche.
- Per poter acquistare un corso o partecipare a un evento, anche nel caso questi siano gratuiti e non richiedano tesseramento, **all'utente è richiesto di essere iscritto alla piattaforma**.