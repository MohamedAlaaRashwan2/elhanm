<?php

function lang($word)
{

    static $allWord = array(

    // Home page

    "صفحة الادمن" => "Control Admin",
    "الاقسام" => "Categories",
    "تعديل الملف" => "Edit Profil",
    "اعدادات" => "Sttings",
    "تسجيل خروج" => "logout"

    );

    return $allWord[$word];
}
;
