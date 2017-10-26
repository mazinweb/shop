<?php

function lang($phrase) {
    static $lang = array(
        //dashboard words
        'CATEGORIES' => 'Categories',
        'HOME' => 'Home',
        'ADMIN' => 'Administrator',
        'ITEMS' => 'Items',
        'MEMBERS' => 'Members',
        'STATISTICS' => 'Statistcs',
        'LOGS' => 'Logs',
        'MESSAGE' => 'welcome',
    );
    return $lang[$phrase];
}
