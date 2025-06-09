<div id="new-lecture-note">
    <form method="post" class="card w-75 my-8 mx-auto" id="new-lecture-note-info">
            <div class="card-header d-flex align-items-center justify-content-between p-7" id="new-lecture-note-header">
                <h3 class="mb-0">Informazioni generali</h3>
            </div>
                <div id="new-lecture-note-body" class="card-body">
                    <label class="form-label" for="lecture-note-title">Inserisci il titolo della dispensa <span><em>(obbligatorio)</em></span></label>
                    <input id="lecture-note-title" name="lecture-note-title" class='form-control form-control-solid'/>

                    <label class="form-label mt-4" for="lecture-note-description">Inserisci la descrizione della dispensa</label>
                    <textarea id="lecture-note-description" name="lecture-note-description" class='form-control form-control-solid'></textarea>
                </div>
                <div class="card-footer">
                    <div class="w-100 d-flex gap-4 justify-content-end pe-7">
                        <button class="btn btn-primary" type="submit" id="lecture-note-info-saveButton">Salva</button>
                    </div>
                </div>
    </form>
    <div id="new-lecture-note-questions">
    </div>
</div>
<input id="last-lecture-note-added" value="" hidden/>
<div class="card w-75 mx-auto mb-8" id="new-lecture-note-buttons">
    <div class=" card-body w-100 d-flex gap-4 justify-content-end">
        <button class="btn btn-secondary" type="button" id="lecture-note-saveButton" value="2">Salva bozza</button>
        <button class="btn btn-primary" type="button" id="lecture-note-publishButton" value="1">Pubblica</button>
    </div>
</div>
<div class="text-start w-75 px-7 m-auto">
    <a href="<?=ROOT . 'materiali'?>">&larr; Torna indietro</a>
</div>