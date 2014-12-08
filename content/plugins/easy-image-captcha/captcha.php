<?php

    require_once(  'cool-php-captcha.php'  );

    $eic = new EasyImageCaptcha;

    $eic->width = 90;
    $eic->height = 33;
    $eic->maxWordLength = 4;

    // Image generation
    $eic->CreateImage();
