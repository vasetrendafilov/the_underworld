<html>
<head>
<?php
require_once 'core/init.php';
      $user=new User();
      if($user->isLoggedIn()){
          if(Input::exists()){
          if(Token::check(Input::get('token'))){
          $data=$user->data();
          $validation = new Validation();
          $validation->check($_POST, array(
              'energija'  => array('required' => 'true')
          ));
          if($validation->passed()){
            $energija=Input::get('energija');
            if($energija>100){
              Redirect::to(404);
            }

             try{
                 $user->update_prom($data->sifra,array(
                     'energija'  => $energija
                 ));
             }catch (Exception $e){
                 die($e->getMessage());
             }
             Session::flash('success', 'Information Updated Successfully');
             Redirect::to('index.php');
          }else{
              pre($validation->errors());
          }
        }
      }
  }else{
    Redirect::to('index.php');
  }

?>
<script type="text/javascript">
  function slider_change(value){
    document.getElementById('energija').innerHTML=value;
  }
</script>
<style type="text/css">
   #div_energija{
     border:2px solid red;
     position:absolute;
     width:150px;
     top:20px;
     left:10%;
   }
  #submit{
    position:absolute;
    width:150px;
    top:200px;
    left:10%;
  }
</style>
</head>
<body>
<form action="" method="post">
     <div id="div_energija">
    <label for="energija">Nadopolni energija:</label><br>
    <input name="energija" type="range" min="<?php require_once 'core/init.php';echo curent_energy();?>" max="100" value="<?php require_once 'core/init.php';echo curent_energy();?>" step="5" onchange="slider_change(this.value);"><br>
    Energija =<span id="energija"></span>
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate();?>"/>
    <input id="submit" type="submit" value="go">
</form>
</body>
</html>
