<div class="bg-auser d-flex" style="height: 33vh">
    <div class="w-100 mx-20">
        <div class="py-4 d-flex gap-8 align-items-center mt-8 justify-content-between" style="height: fit-content">
            <div class="d-flex flex-column">
                <a href="<?=ROOT?>home?show=ecommerce" class="text-decoration-none">
                    <img alt="auser-logo" src="<?= ROOT?>app/assets/svgs/Auser Unipop Cremona Image_white.webp" class="w-250px app-sidebar-logo-default" />
                </a>
                <a href="https://www.auserunipopcremona.com"
                   target="_blank"
                   class="btn btn-auser border border-white text-white text-hover-white position-relative better-hover text-center"
                   onmouseenter="showTooltip({id: 'redirect'})" onmouseleave="hideTooltip({id: 'redirect'})"
                >
                    www.auserunipopcremona.com
                    <span id="tooltip-redirect" class="d-none rounded text-auser py-2 px-3 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 20%); white-space: nowrap">Torna su Auser Cremona</span>
                </a>

            </div>
            <div class="d-flex gap-12 me-16">
                <div class="d-flex flex-column">
                    <a href="<?=ROOT.'home?show=ecommerce'?>" class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover-footer">Corsi ed eventi</a>
                    <a href="<?=ROOT.'home?show=ondemand'?>" class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover-footer">On-demand</a>
                    <a class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover-footer">Tutorial</a>
                </div>
                <div class="d-flex flex-column">
                    <a href="<?=ROOT.'dashboard?id='.$_SESSION[SESSIONROOT]['user']?>" class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover-footer">Area riservata</a>
                    <a class="btn btn-auser border border-auser text-white d-flex align-items-center text-hover-white position-relative better-hover-footer">Privacy policy</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 pb-5 gap-4 w-100 d-flex justify-content-center">
    <a href="https://www.iubenda.com/privacy-policy/19079142" class="position-absolute iubenda-white iubenda-noiframe iubenda-embed" title="Privacy Policy ">Privacy Policy</a>
    <a href="https://www.iubenda.com/privacy-policy/19079142/cookie-policy" class="position-absolute iubenda-white iubenda-noiframe iubenda-embed" title="Cookie Policy ">Cookie Policy</a>
</div>