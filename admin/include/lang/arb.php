<?php

function lang($word)
{

    static $allWord = array(

    // Home page

    "Control Admin" => "صفحة الادمن",
    "Categories" => "الاقسام",
    "Edit Profil" => "تعديل الملف",
    "Sttings" => "اعدادات",
    "logout" => "تسجيل خروج"

    );

    return $allWord[$word];
}
;
