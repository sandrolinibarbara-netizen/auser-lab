<form class="flex-column card w-75 my-7 mx-auto" data-kt-stepper-element="content" id="new-user-form">
    <div class="card-header py-7">
        <h2 class="mb-0"><?php echo(isset($_SESSION[SESSIONROOT]['user']) && $_SESSION[SESSIONROOT]['group'] == 1) ? 'Inserisci i dati dell\'utente' : 'Inserisci i tuoi dati'?></h2>
    </div>
    <div class="card-body">
        <!--begin::Input group-->
        <div class="row mb-10">
            <h3 class="mb-4">Immagine</h3>
            <div id="sponsor-pic" class="d-flex flex-column w-100">
                <div class="w-100 text-start">
                    <p class="form-label">Scegli un'immagine</p>
                </div>
                <div class="image-input image-input-empty bg-light-bg w-100 h-150px d-flex justify-content-center align-items-center" data-kt-image-input="true">
                    <label title="Scegli un'immagine" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="dismiss" class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                        <input id="picInput" name="pic" type="file" accept=".png, .jpg, .jpeg"/>
                    </label>
                    <img id="pic" src="" class="h-75"/>
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="row mb-6">
            <h3 class="mb-4">Dati</h3>
            <div class="fv-row row mb-6">
                <div class="col-12">
                    <label class="form-label ms-2 mb-2" for="username">Username <span><em>(obbligatorio)</em></span></label>
                    <input class="form-control form-control-solid" name="username" id="username" required/>
                </div>
            </div>
            <div class="fv-row row mb-6">
                <div class="col-12">
                    <label class="form-label ms-2 mb-2" for="password">Password <span><em>(obbligatorio)</em></span></label>
                    <input class="form-control form-control-solid" type="password" name="password" id="password" required/>
                </div>
            </div>
            <div class="fv-row col-6">
                <label class="form-label ms-2 mb-2" for="nome">Nome <span><em>(obbligatorio)</em></span></label>
                <input class="form-control form-control-solid" type="text" name="nome" id="nome" required/>
            </div>
            <div class="fv-row col-6">
                <label class="form-label ms-2 mb-2" for="cognome">Cognome <span><em>(obbligatorio)</em></span></label>
                <input class="form-control form-control-solid" type="text" name="cognome" id="cognome" required/>
            </div>
        </div>
        <div class="row mb-6">
            <div class="fv-row col-6">
                <label class="form-label ms-2 mb-2" for="birth">Data di nascita <span><em>(obbligatorio)</em></span></label>
                <input class="form-control form-control-solid" type="text" name="birth" id="birth"/>
            </div>
            <div class="fv-row col-6">
                <label class="form-label ms-2 mb-2" for="email">Indirizzo email <span><em>(obbligatorio)</em></span></label>
                <input class="form-control form-control-solid" name="email" id="email" required/>
            </div>
        </div>
        <div class="row mb-6">
            <div class="fv-row col-6">
                <label class="form-label ms-2 mb-2" for="phone">Telefono</label>
                <input class="form-control form-control-solid" type="text" name="phone" id="phone"/>
            </div>
            <div class="fv-row col-6">
                <label class="form-label ms-2 mb-2" for="address">Indirizzo</label>
                <input class="form-control form-control-solid" name="address" id="address"/>
            </div>
        </div>
        <div class="row mb-6">
            <label class="form-label ms-2 mb-2" for="job">Situazione attuale</label>
            <select class="form-select form-select-solid" data-hide-search="true" id="job" name="job" data-control="select2" data-placeholder="Selezione un'opzione">
                <option></option>
                <option value="1">Studente/studentessa</option>
                <option value="2">Lavoratore/lavoratrice</option>
                <option value="3">Inoccupato/inoccupata</option>
                <option value="4">Pensionato/pensionata</option>
            </select>
        </div>

        <div class="fv-row row mb-6 form-check form-check-custom form-check-solid">
            <?php if(isset($_SESSION[SESSIONROOT]['user']) && $_SESSION[SESSIONROOT]['group'] == 1) :?>
                <h4 class="mb-6">L'utente è maggiorenne? <span><em>(obbligatorio)</em></span></h4>
            <?php else :?>
                <h4 class="mb-6">Sei maggiorenne? <span><em>(obbligatorio)</em></span></h4>
            <?php endif;?>
            <div class="col-3">
                <label class="form-label mx-2" for="underage-yes">Sì</label>
                <input class="form-check-input" type="radio" value='0' name="underage" id="underage-yes" required/>
            </div>
            <div class="col-3">
                <label class="form-label mx-2" for="underage-no">No</label>
                <input class="form-check-input" type="radio" value='1' name="underage" id="underage-no" required/>
            </div>
        </div>

        <div class="fv-row row mb-6">
            <div class="col-12">
                <?php if(isset($_SESSION[SESSIONROOT]['user']) && $_SESSION[SESSIONROOT]['group'] == 1) :?>
                    <h4><label class="form-label ms-2 mb-2 fw-bold fs-4" for="card">Se l'utente è già tesserato Auser, inserisci il suo numero di tessera</label></h4>
                <?php else :?>
                    <h4><label class="form-label ms-2 mb-2 fw-bold fs-4" for="card">Se sei già tesserato Auser, inserisci il tuo numero di tessera</label></h4>
                <?php endif;?>
                <input class="form-control form-control-solid" name="card" id="card" />
            </div>
        </div>

        <?php if(isset($_SESSION[SESSIONROOT]['user']) && $_SESSION[SESSIONROOT]['group'] == 1) :?>
        <div class="fv-row row mb-6 form-check form-check-custom form-check-solid">
            <h4 class="mb-6">Qual è il ruolo dell'utente? <span><em>(obbligatorio)</em></span></h4>
            <div class="col-4">
                <label class="form-label mx-2" for="role-admin">Amministratore</label>
                <input class="form-check-input" type="radio" value='1' name="role" id="role-admin" required/>
            </div>
            <div class="col-3">
                <label class="form-label mx-2" for="role-teacher">Insegnante</label>
                <input class="form-check-input" type="radio" value='3' name="role" id="role-teacher" required/>
            </div>
            <div class="col-3">
                <label class="form-label mx-2" for="role-student">Studente</label>
                <input class="form-check-input" type="radio" value='2' name="role" id="role-student" required/>
            </div>
        </div>
        <?php endif;?>

        <!--end::Input group-->
    </div>
    <div class="card-footer d-flex justify-content-end align-items-center gap-4">
        <div id="email-alert" class="d-none">
            <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div class="d-flex flex-column align-items-start">
                    <span class="text-start text-gray-600">Questo indirizzo email è già stato registrato</span>
                </div>
            </div>
        </div>
        <div id="date-alert" class="d-none">
            <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div class="d-flex flex-column align-items-start">
                    <span class="text-start text-gray-600">La data di nascita non può essere coincidente o successiva alla data di oggi.</span>
                </div>
            </div>
        </div>
        <div id="age-mismatch-alert" class="d-none">
            <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div class="d-flex flex-column align-items-start">
                    <span class="text-start text-gray-600">La data di nascita non coincide con la dichiarazione di maggiore età.</span>
                </div>
            </div>
        </div>
        <div id="username-alert" class="d-none">
            <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div class="d-flex flex-column align-items-start">
                    <span class="text-start text-gray-600">Questo username è già stato registrato</span>
                </div>
            </div>
        </div>
        <div id="name-error-message" class="text-danger px-12 pb-4"></div>
        <div class="">
            <button id="submit-button" type="submit" value="1" class="btn btn-primary" data-kt-stepper-action="submit"><?php echo(isset($_SESSION[SESSIONROOT]['user']) && $_SESSION[SESSIONROOT]['group'] == 1) ? 'Salva' : 'Registrati'?></button>
        </div>
    </div>
</form>
<div class="text-start w-75 px-10 m-auto">
    <a href="<?=ROOT . 'utenti'?>">&larr; Torna indietro</a>
</div>
