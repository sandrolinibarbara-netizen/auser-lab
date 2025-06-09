<?php
function get_global_style($title='Auser') {

    echo ' <title>'.$title.'</title><meta charset="utf-8" />
		<meta name="description" content="Backend Auser" />
		<meta name="keywords" content="metronic, bootstrap, bootstrap 5, template, Auser" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Backend Auser" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<meta property="og:site_name" content="Keenthemes | Metronic" />
		<link rel="shortcut icon" href="'.ROOT.'metronic/assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="'.ROOT.'metronic/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<link href="'.ROOT.'metronic/assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="'.ROOT.'metronic/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="'.ROOT.'metronic/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<link href="'.ROOT.'metronic/assets/plugins/custom/cookiealert/cookiealert.bundle.css" rel="stylesheet" type="text/css" />
		<!--<link href="https://www.jquery-az.com/boots/css/bootstrap-colorpicker/bootstrap-colorpicker.css" rel="stylesheet">-->
		<!--<link href="'.ROOT.'metronic/assets/plugins/custom/colorpicker/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />-->
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>';
}