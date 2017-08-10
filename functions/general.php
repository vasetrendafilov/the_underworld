<?php
function curent_energy(){
    $user=new User();
    if($user->isLoggedIn()){
      $data=$user->data();
    $energija_db= DB::getInstance()->get('users_prom',array('user_sifra','=',$data->sifra));
     if($energija_db->count()){
      return $energija =$energija_db->first()->energija;
    }
 }
}
function display_level($exp,$permision){
  $level_exp=0;
  $points=1000;
  $level=1;
  while ($exp > $level_exp) {
    $level_exp+=$points*$level;
    $level++;
  }
  if($permision==false){
    return $level_exp;
  }else{
  return $level-1;
  }
}

function simplePre($data){
    echo '<pre>';
    echo $data;
    echo '</pre>';
}

function pre($value){
    echo '<pre>';
    foreach ($value as $key) {
        echo $key."<br>";
    }
    echo '</pre>';
}

function varPre($value){
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}
?>
