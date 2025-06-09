<?php

loadPartial('layout/head');
loadPartial('layout/page');
//loadPartial('header');
loadPartial('layout/page-wrapper', ['query' => $query]);
