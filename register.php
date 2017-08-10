<html>
<head>
<?php
  require_once 'core/init.php';
  if(Input::exists()){
      if(Token::check(Input::get('token'))) {
          $validate = new Validation();
          $validation = $validate->check($_POST, array(
              'username' => array(
                  'required' => true,
                  'min' => 2,
                  'max' => 20,
                  'unique' => 'users'
              ),
              'password' => array(
                  'required' => true,
                  'min' => 6,
                  'up/downcase'=>true,
                  'addnumber'=>true,
                  'addsimbol'=>true

              ),
              'password_again' => array(
                  'required' => true,
                  'matches' => 'password'
              ),
              'ime' => array(
                  'required' => true,
                  'min' => 3,
                  'max' => 50
              ),
              'prezime' => array(
                  'required' => true,
                  'min' => 3,
                  'max' => 50
              ),
              'email' => array(
                    'required' => true,
                    'min' =>20,
                    'max' => 50
              ),
              'grad' => array(
                      'required' => true
              ),
              'den' => array(
                      'required' => true
              ),
              'mesec' => array(
                      'required' => true
              ),
              'godina' => array(
                      'required' => true
              ),
              'sex' => array(
                      'required' => true
              ),
              'nacionalnost' => array(
                      'required' => true
              ),
          ));
          if(recaptcha_passed()){
            if ($validation->passed()) {

                $user = new User();
                $salt = Hash::salt(32);
                $name=tidy(Input::get('ime')).' '.tidy(Input::get('prezime'));
                $rodenden=Input::get('godina').'-'.Input::get('mesec').'-'.Input::get('den');
                $random = substr(md5(mt_rand()), 0, 8);
                try{
                    $user->create('users',array(
                        'sifra'     =>$random,
                        'username'  =>escape(Input::get('username')),
                        'password'  => Hash::make(Input::get('password'), $salt),
                        'salt'      => $salt,
                        'name'      => $name,
                        'email'     => Input::get('email'),
                        'grad'      => Input::get('grad'),
                        'rodenden'  => $rodenden,
                        'sex'       => Input::get('sex'),
                        'nacionalnost'=> Input::get('nacionalnost'),
                        'joined'    =>date('Y-m-d H:i:s'),
                        'group'     => 1
                    ));
                    $user->create('users_prom',array(
                        'user_sifra'  => $random,
                        'level'       => 0,
                        'energija'    => 100,
                        'pari'        => 1000,
                        'mok'         => 1,
                        'obrazovanie' => 1,
                        'gold'        =>1

                    ));
                }catch (Exception $e){
                    die($e->getMessage());
                }

                Session::flash('success', 'You have successfully registered.');
                Redirect::to('index.php');
            } else {
                pre($validation->errors());
            }
        }
     }
  }
  ?>
    <style type="text/css">
     #username{
      -webkit-border-radius:5px;
    }
    </style>
</head>
<body>
<form action="" method="post">
    <div class="field">
        <label for="username">Корисничко име</label><br>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off"/>
    </div><br>

    <div class="field">
        <label for="password">Лозинка</label><br>
        <input type="password" name="password" id="password" value="" autocomplete="off"/>
    </div><br>

    <div class="field">
        <label for="password_again">Потврди Лозинка</label><br>
        <input type="password" name="password_again" id="password_again" value="" autocomplete="off"/>
    </div><br>
    <div class="field">
        <label for="name">Емаил Адреса</label><br>
        <input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off"/>
    </div><br>

    <div class="field">
        <label for="name">Име</label><br>
        <input type="text" name="ime" id="ime" value="<?php echo escape(Input::get('ime')); ?>" autocomplete="off"/>
    </div><br>

    <div class="field">
        <label for="name">Презиме</label><br>
        <input type="text" name="prezime" id="prezime" value="<?php echo escape(Input::get('prezime')); ?>" autocomplete="off"/>
    </div><br>

    <div><select name="grad">
          <option selected="selected" value="">Одберете Град</option>
          <option value="VE">Велес</option>
          <option value="DE">Демир Капија</option>
          <option value="KA">Кавадарци</option>
          <option value="NE">Неготино</option>
          <option value="SV">Свети Николе</option>
          <option value="SK">Скопје</option>
          <option value="OH">Охрид</option>
          <option value="GO">Гостивар</option>
          <option value="TE">Тетово</option>
          <option value="KY">Куманово</option>
          <option value="GE">Гевѓелија</option>
          <option value="ST">Штип</option>
          <option value="PE">Пехчево</option>
          <option value="KO">Кочани</option>
          <option value="BE">Берово</option>
          <option value="MK">Македонска Каменица</option>
          <option value="VI">Виница</option>
          <option value="DE">Делчево</option>
          <option value="PR">Пробиштип</option>
          <option value="DB">Дебар</option>
          <option value="KI">Кичево</option>
          <option value="MB">Македонски Брод</option>
          <option value="SR">Струга</option>
          <option value="BO">Богданци</option>
          <option value="VA">Валандово</option>
          <option value="DO">Дојран</option>
          <option value="RA">Радовиш</option>
          <option value="SR">Струмица</option>
          <option value="BT">Битола</option>
          <option value="DX">Демир Хисар</option>
          <option value="KU">Крушево</option>
          <option value="RE">Ресен</option>
          <option value="PR">Прилеп</option>
          <option value="KR">Кратово</option>
          <option value="KP">Крива Паланка</option>
    </select></div><br>
    <select id="den" name="den">
        <option selected="selected" value="">Ден</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
    </select>
    <select id="mesec" name="mesec">
        <option selected="selected" value="">Месец</option>
        <option value="1">Јануари</option>
        <option value="2">Февруари</option>
        <option value="3">Март</option>
        <option value="4">Април</option>
        <option value="5">Мај</option>
        <option value="6">Јуни</option>
        <option value="7">Јули</option>
        <option value="8">Август</option>
        <option value="9">Септември</option>
        <option value="10">Октомври</option>
        <option value="11">Ноември</option>
        <option value="12">Декември</option>
    </select>
    <select id="godina" name="godina">
        <option selected="selected" value="">Година</option><option value="2017">2017</option><option value="2016">2016</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option>
    </select><br><br>
    <select id="sex" name="sex">
        <option selected="selected" value="">Одберете Пол</option>
        <option value="m">Машко</option>
        <option value="z">Женско</option>
    </select>
    <select id="nacionalnost" name="nacionalnost">
       <option selected="selected" value="">Одберете Националност</option>
       <option value="M">Македонец/ка</option>
       <option value="A">Албанец/ка</option>
       <option value="T">Турчин/ка</option>
       <option value="S">Србин/ка</option>
       <option value="R">Ром/ка</option>
       <option value="V">Влав/ка</option>
       <option value="B">Боснијак</option>
       <option value="D">Друго</option>
    </select><br><br>
   <div class="g-recaptcha" data-sitekey="6LeVuSoUAAAAAEvr7p2emCHC_CkHmllSnMuR2fuN"></div>
   <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
    <br><input type="submit" value="Регистрација"/>
</form>
<script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>
