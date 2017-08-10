<?php
require_once 'core/init.php';
if(isset($_GET['e_l'])){
  if($_GET['e_l']==1){
  echo (curent_energy()/100)*100;

}else if($_GET['e_l']==2){
      $user = new User();
      if($user->isLoggedIn()){
        $data = $user->data();
          if($user->find_prom($data->sifra)){
            $data_prom=$user->data_prom();
          }//zimanje na site potrebni promenlivi
          $conf=(display_level($data_prom->level,false))-(display_level($data_prom->level,true)*1000);
          $curent_exp=($data_prom->level)-$conf;
          $granica=display_level($data_prom->level,false)-$conf;
          echo ($curent_exp/$granica)*100;//level_exp transformiran vo vo procenti
      }
  }
}
?>
