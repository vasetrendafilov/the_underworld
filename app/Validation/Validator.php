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
          'uniqueEmail' => 'Тој емаил е превземен'
        ],
        'username' =>[
          'uniqueUsername' => 'Тоа корисничко име е превземено'
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
    $user = $this->container->auth->user();
    return password_verify($value, $user->password);
  }
}
