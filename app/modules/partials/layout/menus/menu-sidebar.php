<?php
$keys = explode('/', $_SERVER['PHP_SELF']);

?>

<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <?php foreach ($query as $row) : ?>
                <!--begin:Menu item-->
                <?php if(!$row['path']):?>
                        <div class="menu-item menu-sub-indention menu-accordion <?php echo ($row['ordine'] === 3) ? 'mt-12' : ''; ?>" data-kt-menu-trigger="click">
                            <!--begin:Menu link-->
                            <a class="text-decoration-none menu-link <?php echo (array_search($row['chiave'], $keys)) ? 'active' : '';?>">
                                <span class="menu-icon">
                                    <i class="ki-outline fs-2 <?= $row['icona'] ?>"></i>
                                </span>
                                <span class="menu-title"><?= $row['titolo'] ?></span>
                                <span class="menu-arrow"></span>
                            </a>
                            <!--end:Menu link-->
                            <div class="menu-sub menu-sub-accordion pt-3 ps-6">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="<?= ROOT."utenti"?>" class="menu-link text-decoration-none p-0">
                                        <span class="menu-icon">
                                        <i class="ki-outline fs-2 ki-user-tick"></i>
                                    </span>
                                        <span class="text-decoration-none menu-link">Iscritti</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="<?= ROOT."permessi"?>" class="menu-link text-decoration-none p-0">
                                        <span class="menu-icon">
                                        <i class="ki-outline fs-2 ki-key"></i>
                                    </span>
                                        <span class="text-decoration-none menu-link">Permessi</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                <?php endif; ?>
                <?php if($row['ordine'] && $row['path']):?>
                <div class="menu-item <?php echo ($row['ordine'] === 3) ? 'mt-12' : ''; ?>">
                    <!--begin:Menu link-->
                    <a href="<?php if($row['path'] === 'profilo') {
                        echo ROOT.$row['path'].'?user=profile&id='.$_SESSION[SESSIONROOT]['user'];
                    } elseif ($row['path'] === 'home') {
                        echo ROOT.$row['path'].'?show=ecommerce';
                    } else {
                        echo ROOT.$row['path'];
                    } ?>" class="text-decoration-none menu-link <?php echo (array_search($row['chiave'], $keys)) ? 'active' : '';?>">

                        <span class="menu-icon">
                            <i class="ki-outline fs-2 <?= $row['icona'] ?>"></i>
                        </span>
                        <span class="menu-title"><?= $row['titolo'] ?></span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <?php endif;?>
                <!--end:Menu item-->
                <?php endforeach; ?>
                <div class="mt-4">
                    <a href="<?= ROOT . "logout?logout=confirmed"?>" class="w-100 btn btn-secondary"><i class="ki-outline fs-2 ki-exit-left text-black"></i> Logout</a>
                </div>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
</div>

