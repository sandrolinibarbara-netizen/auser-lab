<form class="flex-column card w-75 my-10 mx-auto" id="recovery-form">
  <div class="card">
      <div class="card-header">
          <h3 class="p-7 mb-0">Inserisci l'indirizzo email con cui ti sei registrato</h3>
      </div>
      <div class="fv-row card-body mx-7">
          <label for="email-recovery" class="form-label">Indirizzo email</label>
          <input id="email-recovery" name="email" class="form-control form-control-solid" type="email" />
      </div>
      <div class="card-footer d-flex justify-content-end align-items-center gap-4">
          <div id="email-alert" class="d-none">
              <div class="alert alert-danger d-flex align-items-center p-5 w-100 mb-0">
                  <i class="ki-duotone ki-shield-cross fs-2hx text-gray-600 me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                  <div class="d-flex flex-column align-items-start">
                      <span class="text-start text-gray-600">L'indirizzo email che hai inserito non è registrato</span>
                  </div>
              </div>
          </div>
          <div id="name-error-message" class="text-danger px-12 pb-4"></div>
          <div class="">
              <button type="submit" value="<?php echo($_GET['type'] == 1) ? '1' : '2'?>" id="recovery-submit" class="btn btn-primary"><?php echo($_GET['type'] == 1) ? 'Cambia password' : 'Manda un\'altra email di conferma'?></button>
          </div>
      </div>
  </div>
</form>