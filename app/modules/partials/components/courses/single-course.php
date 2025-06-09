<?php
$group = $_SESSION[SESSIONROOT]['group'];
?>
<div class="card h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header py-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Dati corso</span>
        </h3>

        <div class="m-0">
            <?php if($group != 2):?>
            <!--begin::Menu toggle-->
                <a href="<?= ROOT . 'update-course?update=course&id=' . $_GET['id'] . '&type=1'?>" class="btn btn-light-bg btn-sm"><i class="ki-outline ki-plus-square fs-6"></i> Modifica i dati del corso</a>
                <?php if($data[0]['privato'] == 1):?>
                    <button class="btn btn-light-bg btn-sm" value="<?= $data[0]['id']?>" type="button" data-bs-toggle="modal" data-bs-target="#add-students-modal"><i class="ki-outline ki-plus-square fs-6"></i> Invita studenti</button>
                <?php endif; ?>
            <?php endif;?>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex align-items-center">
                Categoria:
                <?php if($data[0]['colore']) : ?>
                    <div class="w-25px h-25px d-inline-block mx-4 rounded" style="background-color: <?= $data[0]['colore']?>"></div><?= $data[0]['argomento']?>
                <?php else :?>
                <div class="w-25px h-25px d-inline-block mx-4 rounded border border-auser"></div><p class="d-inline-block mb-0"> Nessuna categoria selezionata</p>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                Immagini: <img class="w-150px mx-4 rounded" src="<?=ROOT . 'app/assets/uploaded-files/heros-images/' . $data[0]['immagine']?>"/>
            </li>
            <li class="list-group-item">
                Link video:
                <?php if($data[0]['video']): ?>
                    <a href="<?=$data[0]['video']?>"><?=$data[0]['video']?></a>
                <?php else :?>
                    <p class="d-inline-block mb-0"> Nessun video selezionato</p>
                <?php endif;?>
            </li>
            <li class="list-group-item">
                Nome: <?= $data[0]['corso']?>
            </li>
            <li class="list-group-item">
                Numero di lezioni: <?= $data[0]['lezioni']?>
            </li>
            <li class="list-group-item">
                Contributo: <?= $data[0]['importo']?>€
            </li>
            <?php if($data[0]['data_inizio'] != '01/01/3000'): ?>
                <li class="list-group-item">
                    Data di inizio: <?= $data[0]['data_inizio']?>
                </li>
                <li class="list-group-item">
                    Data di fine: <?= $data[0]['data_fine']?>
                </li>
                <?php if($group != 2):?>
                    <li class="list-group-item">
                        N° min. studenti: <?= $data[0]['min']?>
                    </li>
                    <li class="list-group-item">
                        N° max. studenti: <?= $data[0]['max']?>
                    </li>
                <?php endif;?>
            <?php endif;?>
            <li class="list-group-item">
                Insegnante/i: <?= implode(', ', $data[0]['insegnanti']);?>
            </li>
            <li class="list-group-item">
                Descrizione: <?= $data[0]['descrizione']?>
            </li>
        </ul>
    </div>
    <!--end: Card Body-->
</div>