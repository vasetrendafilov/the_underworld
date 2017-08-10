<html>
<head>
<?php
require_once 'core/init.php';
      $user=new User();
      if($user->isLoggedIn()){
        $data = $user->data();
        if($user->find_prom($data->sifra)){
        $data_prom=$user->data_prom();
        }
        if(Session::exists('success')){
            echo Session::flash('success');
        }




  }else{
    Redirect::to('login.php');
  }

?>
<style>
#meni{
  position: absolute;
  top: 55px;
  left: 20px;
  width: 1300px;
  height: 550px;
  border: 2px solid red;
  border-radius: 10px;
  }
</style>

</head>
<body>
<div id="meni">
  <div id="div_odjava">
    <button>Одјава</button>
    </a>
  </div>
  <div id="div_shop">
    <a href="http://localhost/vase/Drzava.mk/shop.php">
    <button>Shop</button>
    </a>
  </div>
  <div id="div_banka">
    <a href="http://localhost/vase/Drzava.mk/banka.php">
    <button>Banka</button>
    </a>
  </div>
  <div id="div_sobranie">
    <a href="http://localhost/vase/Drzava.mk/sobranie.php">
    <button>Sobranie</button>
    </a>

</div>


</body>
</html>
