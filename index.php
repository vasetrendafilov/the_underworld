
<html>
<head>

<style>
  #energy_bar{
  position: absolute;
  top: 10;
  left: 63%;
  width: 200px;
  height: 15px;
  border: 2px solid red;
  border-radius:10px;
  }
 #progres_bar{
  position: absolute;
  width: 0px;
  height: 15px;
  background-color: blue;
  border-radius:10px;
 }

  #level_bar{
  position: absolute;
  top: 10;
  left: 80%;
  width: 200px;
  height: 15px;
  border: 2px solid red;
  border-radius:10px;
 }
  #level_progres_bar{
  position: absolute;
  width: 0px;
  height: 15px;
  background-color: green;
  border-radius:10px;
  }
  #pari{
    position: absolute;
    top: 7;
    left: 57%;
    width: 40px;
    height: 35px;
    padding: 3px;
    padding-left: 8px;
    border: 2px solid red;
    border-radius:10px;
  }
  #gold{
    position: absolute;
    top: 7;
    left: 52%;
    width: 40px;
    height: 35px;
    padding: 3px;
    padding-left: 8px;
    border: 2px solid red;
    border-radius:10px;
  }
  #mok{
    position: absolute;
    top: 7;
    left: 47%;
    width: 40px;
    height: 35px;
    padding: 3px;
    padding-left: 8px;
    border: 2px solid red;
    border-radius:10px;
  }
#meni{
  position: absolute;
  top: 55px;
  left: 20px;
  width: 1300px;
  height: 550px;
  border: 2px solid red;
  border-radius: 10px;
  }
  #div_odjava{
    position: absolute;
    top: 500px;
    left: 1200px;
  }
  #div_shop{
    position: absolute;
    top: 300px;
    left: 700px;
  }
  #div_banka{
    position: absolute;
    top: 500px;
    left: 300px;
  }
  #div_rabota{
    position: absolute;
    top: 400px;
    left: 500px;
  }
  #div_obrazovanie{
    position: absolute;
    top: 400px;
    left: 600px;
}
</style>

<script>
  function doFirst() {
      bar_energija=document.getElementById("progres_bar");
      bar_level=document.getElementById("level_progres_bar");
        update_bar=setInterval(getEnergy,1000);
      getLevel();
  }
  function getEnergy() {
    if(window.XMLHttpRequest) {
       xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange = function() {
     if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         bar_energija.style.width = xmlhttp.responseText+'%';
     }
    }
    xmlhttp.open('GET', 'level_energy.php?e_l=1' , true);
    xmlhttp.send();
  }
  function getLevel() {
    if(window.XMLHttpRequest) {
       xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange = function() {
     if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         bar_level.style.width = xmlhttp.responseText+'%';
     }
    }
    xmlhttp.open('GET', 'level_energy.php?e_l=2' , true);
    xmlhttp.send();
  }

  window.addEventListener('load',doFirst,false);

</script>

<?php
  require_once 'core/init.php';
  if(Session::exists('success')){
      echo Session::flash('success');
  }
  $user = new User();
  if($user->isLoggedIn()){
  $data = $user->data();
  if($user->find_prom($data->sifra)){
  $data_prom=$user->data_prom();
  }//zimanje na site potrebni promenlivi
  $level=display_level($data_prom->level,true);
  //echo $level;

   }else{
    Redirect::to('login.php');
   }?>
</head>
<body>
    <div id="energy_bar"> <div id="progres_bar"></div> </div>
    <div id="level_bar"> <div id="level_progres_bar"></div> </div>
    <div id="pari"><label for="pari">Pari:</label><br><?php echo $data_prom->pari; ?></div>
    <div id="mok"><label for="mok">Mok:</label><br><?php echo $data_prom->mok; ?></div>
    <div id="gold"><label for="gold">Zlato:</label><br><?php echo $data_prom->gold; ?></div>
<div id="meni">
    <div id="div_odjava">
      <a href="http://localhost/vase/Drzava.mk/logout.php">
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
    <div id="div_rabota">
      <a href="http://localhost/vase/Drzava.mk/rabota.php">
      <button>Rabota</button>
      </a>
    </div>
    <div id="div_vlada">
      <a href="http://localhost/vase/Drzava.mk/vlada.php">
      <button>Vlada</button>
      </a>
    </div>
    <div id="div_shtab">
      <a href="http://localhost/vase/Drzava.mk/shtab.php">
      <button>Stab</button>
      </a>
    </div>
    <div id="div_obrazovanie">
      <a href="http://localhost/vase/Drzava.mk/obrazovanie.php">
      <button>Obrazovanie</button>
      </a>
    </div>
    <div id="div_informacisko_biro">
      <a href="http://localhost/vase/Drzava.mk/informacisko_biro.php">
      <button>Informacisko_Biro</button>
      </a>
    </div>
    <div id="div_pomosh">
      <a href="http://localhost/vase/Drzava.mk/pomosh.php">
      <button>Pomosh</button>
      </a>
    </div>
    <div id="div_glasachko_Mesto">
      <a href="http://localhost/vase/Drzava.mk/glasachko_Mesto.php">
      <button>Glasachko_Mesto</button>
      </a>
    </div>


</div>

</body>
</html>
