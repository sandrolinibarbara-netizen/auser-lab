<?php
$ondemand = $data[0]['data_inizio'] === '01/01/3000';
$group = $_SESSION[SESSIONROOT]['group'];
?>

<div class="card h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header py-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Dati evento</span>
        </h3>

        <div class="m-0">
            <!--begin::Menu toggle-->
            <?php if($group != 2):?>
                <?php if($data[0]['privato'] == 1):?>
                    <button class="btn btn-light-bg btn-sm" value="<?= $data[0]['id']?>" type="button" data-bs-toggle="modal" data-bs-target="#add-students-modal"><i class="ki-outline ki-plus-square fs-6"></i> Invita studenti</button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex align-items-center">
                Categoria:
                <?php if($data[0]['categoria']) : ?>
                    <div class="w-25px h-25px d-inline-block mx-4 rounded" style="background-color: <?= $data[0]['colore']?>"></div><?= $data[0]['argomento']?>
                <?php else :?>
                <div class="w-25px h-25px d-inline-block mx-4 rounded border border-auser"></div><p class="d-inline-block mb-0"> Nessuna categoria selezionata</p>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                Immagini: <img class="w-150px mx-4 rounded" src="<?=ROOT . 'app/assets/uploaded-files/heros-images/' . $data[0]['immagine']?>"/>
            </li>
            <li class="list-group-item">
                Nome: <?= $data[0]['diretta']?>
            </li>
            <li class="list-group-item">
                Contributo: <?= $data[0]['importo']?>€
            </li>
            <?php if(!$ondemand):?>
                <li class="list-group-item">
                    Data: <?= $data[0]['data_inizio']?>
                </li>
                <li class="list-group-item">
                    Orario di inizio: <?= $data[0]['orario_inizio']?>
                </li>
                <li class="list-group-item">
                    Orario di fine: <?= $data[0]['orario_fine']?>
                </li>
                <li class="list-group-item">
                    Luogo: <?= $data[0]['luogo']?>
                </li>
                <?php if($group != 2):?>
                    <li class="list-group-item">
                        Posti disponibili: <?= $data[0]['posti'] . '/' . $data[0]['max'] ?>
                    </li>
                <?php endif;?>
            <?php endif;?>
            <li class="list-group-item">
                Relatore/i: <?php foreach($data[0]['relatori'] as $key => $speaker): ?>
                <span><?php echo($key == count($data[0]['relatori']) - 1) ? $speaker['fullName'] : $speaker['fullName'] . ', '?></span>
                <?php endforeach;?>
            </li>
            <li class="list-group-item">
                Descrizione: <?= $data[0]['descrizione']?>
            </li>
        </ul>
    </div>
    <!--end: Card Body-->
</div>