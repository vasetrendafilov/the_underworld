<?php
namespace App\Validation;

use Violin\Violin;
use App\Models\User;

class Validator extends Violin
{
  protected $container;

  public function __construct($container)
  {
      $this->container = $container;
      $this->addFieldMessages([
        'email' =>[
          'uniqueEmail' => 'That email i already taken'
        ],
        'username' =>[
          'uniqueUsername' => 'That username i already taken'
        ]
      ]);
      $this->addRuleMessages([
        'checkPassword' => 'That dosent match you`re curent password'
      ]);
  }
  public function validate_uniqueEmail($value, $input , $args)
  {
    $user = User::where('email',$value);
    return ! (bool) $user->count();
  }
  public function validate_uniqueUsername($value, $input , $args)
  {
    $user = User::where('username',$value);
    return ! (bool) $user->count();
  }
  public function validate_checkPassword($value, $input , $args)
  {
    $user = User::where('id', $_SESSION['user'])->first();
    if(password_verify($value, $user->password)){
      return true;
    }else{
      return false;
    }
  }

}
