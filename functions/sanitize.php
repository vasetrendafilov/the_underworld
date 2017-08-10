<?php

function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}
function tidy($string){
  $bukvi = str_split($string);
  $zbor=strtoupper($bukvi[0]);
  array_shift($bukvi);
   foreach ($bukvi as $bukva) {
     $zbor.=strtolower($bukva);
   }
  return $zbor;
}

function decode_g($value){
   $gradovi = array(
    'VE' => 'Велес',
    'KA' => 'Кавадарци',
    'DE' => 'Демир Капија',
    'NE' => 'Неготино',
    'SV' => 'Свети Николе',
    'SK' => 'Скопје',
    'OH' => 'Охрид',
    'GO' => 'Гостивар',
    'TE' => 'Тетово',
    'KY' => 'Куманово',
    'GE' => 'Гевѓелија',
    'ST' => 'Штип',
    'PE' => 'Пехчево',
    'KO' => 'Кочани',
    'BE' => 'Берово',
    'MK' => 'Македонска Каменица',
    'VI' => 'Виница',
    'DE' => 'Делчево',
    'PR' => 'Пробиштип',
    'DB' => 'Дебар',
    'KI' => 'Кичево',
    'MB' => 'Македонски Брод',
    'SR' => 'Струга',
    'BO' => 'Богданци',
    'VA' => 'Валандово',
    'DO' => 'Дојран',
    'RA' => 'Радовиш',
    'SR' => 'Струмица',
    'BT' => 'Битола',
    'DX' => 'Демир Хисар',
    'KU' => 'Крушево',
    'RE' => 'Ресен',
    'PR' => 'Прилеп',
    'KR' => 'Кратово',
    'KP' => 'Крива Паланка'
   );
   return $gradovi[$value];
}

function decode_n($value){
   $nacionalnosti = array(
    'M' => 'Македонец',
    'A' => 'Албанец',
    'T' => 'Турчин',
    'S' => 'Србин',
    'R' => 'Ром',
    'V' => 'Влав',
    'B' => 'Боснијак',
    'D' => 'Друго'
   );
   return $nacionalnosti[$value];
 }

function recaptcha_passed(){
   $secret = '6LeVuSoUAAAAACG7LW0ii_PAQBpM9yokuqFC6PdX';
   $response = $_POST['g-recaptcha-response'];
   $remoteip = $_SERVER['REMOTE_ADDR'];
   $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");
   $result = json_decode($url, TRUE);
  return $result['success'];
}
?>
