<?php

function get_quiz1_choice($k = null) {
    $choice = array(
        '1' => 'เอลิเซอร์',
        '2' => 'พลาสติกสีฟ้า',
        '3' => 'พลาสติกอื่น ๆ',
        '4' => 'สแตนเลส',
        '5' => 'ไฟเบอร์กลาส',
        '6' => 'ซีเมนต์',
        '7' => 'ไม่แน่ใจ'
    );
    if ($k != null)
        return $choice[$k];
    else
        return $choice;
}

function get_quiz2_choice($k = null) {
    $choice = array(
        '1' => '0-5 ปี',
        '2' => '6-10 ปี',
        '3' => '11-15 ปี',
        '4' => '16-20 ปี',
        '5' => 'มากกว่า 20 ปี'
    );

    if ($k != null)
        return $choice[$k];
    else
        return $choice;
}

function get_quiz3_choice($k = null) {
    $choice = array(
        '1' => 'กลางแจ้ง',
        '2' => 'ในร่ม',
        '3' => 'ใต้ดิน'
    );

    if ($k != null)
        return $choice[$k];
    else
        return $choice;
}

function get_quizIsHave($k = null) {
    $choice = array(
        '1' => 'มี',
        '0' => 'ไม่มี'
    );

    if ($k != null)
        return $choice[$k];
    else
        return $choice;
}

function getAgeText($k = null) {

    $choice = array(
        '0' => 'อายุ',
        '1' => 'ต่ำกว่า 18 ปี',
        '2' => '18 – 24',
        '3' => '25 – 34',
        '4' => '35 – 44',
        '5' => '45 – 54',
        '6' => '55 – 64',
        '7' => '65 ปี ขึ้นไป',
    );
    if ($k != null)
        return $choice[$k];
    else
        return $choice;
}
