<form class="flex-column card w-75 my-10 mx-auto" id="recovery-password-form">
    <div class="card">
        <div class="card-header">
            <h3 class="p-7 mb-0">Inserisci la nuova password</h3>
        </div>
        <div class="fv-row card-body mx-7">
            <label for="password-recovery" class="form-label">Password</label>
            <input id="password-recovery" name="password" class="form-control form-control-solid" type="password" />
        </div>
        <div class="card-footer d-flex justify-content-end align-items-center gap-4">
            <div id="name-error-message" class="text-danger px-12 pb-4"></div>
            <div class="">
                <button type="submit" id="recovery-submit" class="btn btn-primary">Cambia password</button>
            </div>
        </div>
    </div>
</form><?php
