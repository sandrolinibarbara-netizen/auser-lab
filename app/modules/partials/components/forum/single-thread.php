<div class="card card-flush h-md-100 my-8">
    <!--begin::Header-->
    <div class="card-header pt-7 d-flex flex-column">
        <!--begin::Title-->
        <h3 class="card-title">
            <span class="card-label fw-bold text-gray-800" id="forum_title"><?= $data[0]['titolo']?></span>
        </h3>
        <h6 class="card-subtitle"><?= $data[0]['descrizione']?></h6>
        <!--end::Title-->
        <!--begin::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <?php foreach($data as $post) :?>
        <div class="card p-4 mb-4">
            <div class="card-header h-30px d-flex flex-row justify-content-end align-items-center gap-4 pb-2 px-2">
                <p class="mb-0"><i class="text-black ki-outline ki-time me-1"></i><?= $post['data_modifica'].', '.$post['orario_modifica']?></p>
                <?php if($_SESSION[SESSIONROOT]['group'] == 1 && $data[0]['type'] == 'post'): ?>
                    <button data-bs-id="post-<?= $post['thread'].'/'.$post['id']?>" value="<?= $post['id']?>" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1"><i class="text-black ki-outline ki-trash fs-6"></i></button>
                <?php endif; ?>
            </div>
            <div class="card-body-forum">
                <div class="row">
                    <div class="col-2 bg-light-bg d-flex flex-column align-items-center p-2 rounded-bottom">
                        <p class="fw-bold mb-1"><?= $post['nome'].' '.$post['cognome'] ?></p>
                        <p class="d-flex align-items-center"><i class="ki-outline <?= $post['icona']?> me-1"></i><?= $post['ruolo']?></p>
                        <img src="<?php echo (explode(':', $post['immagine'])[0] === 'http' || explode(':', $post['immagine'])[0] === 'https' ? $post['immagine'] : ROOT . 'app/assets/uploaded-files/users-images/' . $post['immagine'])?>" alt="<?= $post['nome'].'-'.$post['cognome'].'-avatar' ?>" class="w-100px h-100px rounded-circle" style="object-fit: cover; object-position: top"/>
                    </div>
                    <div class="col py-4 px-8">
                        <p><?= $post['testo']?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(($data[0]['risposte_studenti'] == 0 && $_SESSION[SESSIONROOT]['group'] != 2) || ($data[0]['risposte_studenti'] == 1)) : ?>
            <div class="w-100 text-end">
                <button data-bs-toggle="modal" data-bs-target="#post-modal" class="btn btn-light-bg"><i class="ki-outline ki-plus-square fs-6"></i> Nuova risposta</button>
            </div>
        <?php endif; ?>
    </div>
    <!--end: Card Body-->
</div>
<div class="text-start w-100 px-10 m-auto">
    <a href="<?php
    if($data[0]['type'] == 'post') {
        echo ROOT . 'forum/corso?id=' . explode("=", explode('&', $_SERVER["QUERY_STRING"])[0])[1];
    } else {
        echo ROOT . 'messaggi';
    }
    ?>">&larr; Torna indietro</a>
</div>