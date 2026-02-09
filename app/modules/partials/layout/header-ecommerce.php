<div class="bg-auser d-flex justify-content-center align-items-center">
    <div class="w-100 mx-10 d-flex justify-content-between align-items-center">
        <div class="py-4 d-flex gap-4 align-items-center">
            <a href="<?=ROOT?>home?show=ecommerce">
                <img alt="auser-logo" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.svg" class="h-75px app-sidebar-logo-default" />
            </a>
<!--            <a-->
<!--                    onmouseenter="showTooltip({id: 'redirect'})" onmouseleave="hideTooltip({id: 'redirect'})"-->
<!--                    class="btn btn-auser border border-auser d-flex align-items-center text-hover-white position-relative better-hover p-4"-->
<!--                    href="https://www.ausercomprensoriodicremona.it/"-->
<!--                    target="_blank"-->
<!--            ><img class="h-25px" src="--><?php //= ROOT?><!--app/assets/svgs/Auser_logo_white.webp" alt="auser-logo"/>-->
<!--                <span id="tooltip-redirect" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 20%); white-space: nowrap">Torna su Auser Cremona</span>-->
<!--            </a>-->
            <a href="<?=ROOT.'home?show=ecommerce'?>" class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover px-6 py-4">Corsi ed eventi LIVE</a>
            <a href="<?=ROOT.'home?show=ondemand'?>" class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover px-6 py-4">Corsi ed eventi ON DEMAND</a>
            <a class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover px-6 py-4">Tutorial</a>
        </div>
        <div class="d-flex gap-4">


            <a class="btn btn-surface text-auser d-flex align-items-center text-hover-white better-hover px-6 py-4" href="<?=ROOT.'dashboard?id='.$_SESSION[SESSIONROOT]['user']?>" style="border: #D9D9D9 solid 1px">
                <i class="ki-outline ki-user text-auser"></i>
                Area riservata
            </a>
            <?php if(isset($_SESSION[SESSIONROOT]['user'])):?>
            <a class="btn btn-surface text-auser d-flex align-items-center text-hover-white position-relative better-hover px-6 py-4" href="<?=ROOT.'checkout?cart='.$_SESSION[SESSIONROOT]['user']?>" style="border: #D9D9D9 solid 1px">
                <i class="ki-outline ki-handcart text-auser fs-2 p-0"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-success">
                    <?php
                        if(isset($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']])) {
                            echo (count($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]) > 0) ? count($_SESSION[SESSIONROOT]['cart'][$_SESSION[SESSIONROOT]['user']]) : 0;
                        } else {
                            echo 0;
                        }
                    ?>
                </span>
            </a>
            <?php else : ?>
                <a class="btn btn-surface text-auser d-flex align-items-center text-hover-white better-hover px-6 py-4" href="<?=ROOT.'iscrizione'?>" style="border: #D9D9D9 solid 1px">
                    <i class="ki-outline ki-archive-tick text-auser"></i>
                    Iscriviti
                </a>
            <?php endif; ?>
<!--            <a-->
<!--                    onmouseenter="showTooltip({id: 'redirect'})" onmouseleave="hideTooltip({id: 'redirect'})"-->
<!--                    class="btn btn-surface border border-surface d-flex align-items-center text-hover-white position-relative better-hover p-4"-->
<!--                    href="https://www.ausercomprensoriodicremona.it/"-->
<!--                    target="_blank"-->
<!--            ><img class="h-25px" src="--><?php //= ROOT?><!--app/assets/svgs/Auser_logo.webp" alt="auser-logo"/>-->
<!--                <span id="tooltip-redirect" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-75%, 20%); white-space: nowrap">Torna su Auser Cremona</span>-->
<!--            </a>-->
        </div>
    </div>
</div>
