<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\Payments;
use App\Models\People;

class AjaxController extends Controller
{
  public function getPayments($request, $response)
  {
    $description = $request->getParam('description');
    if (isset($description) && !empty($description)){
      $payment = Payments::where('description', trim($description))->first();
      $payment->delete();
      return 'true';
    }
    return 'false';
  }
  public function getPeople($request, $response)
  {
    $money = $request->getParam('money');
    $name = $request->getParam('name');
    if (isset($money) && !empty($money) && isset($name) && !empty($name)){
    $person = People::where('name', trim($name))->first();
    $person->update(['balance' => $person->balance + $money ]);
    return 'true';
    }
    return 'false';
  }
  public function getPeopleDelete($request, $response)
  {
    $name = $request->getParam('name');
    if (isset($name) && !empty($name)){
    $person = People::where('name', trim($name))->first();
    $person->delete();
    return 'true';
    }
    return 'false';
  }
  public function getPeopleAdd($request, $response)
  {
    $name = $request->getParam('name');
    if (isset($name) && !empty($name)){
    $user = $this->auth->user();
    $user->update([
      'people' => $user->people + 1
    ]);
    $user->addPerson()->create([
      'name' => trim($name),
      'balance' => 0
    ]);
    return 'true';
    }
    return 'false';
  }
  public function getPeopleSearch($request, $response)
  {
    $key = $request->getParam('key');
    if (isset($key) && !empty($key)){
      $people = People::where('name','LIKE', $key.'%')->get();
        foreach ($people as $person){
          $html = "<div class='card bg-light '>
            <div class='card-body'>
              <button type='button' class='close' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
              <h4 class='card-title'> ".$person->name."</h4> ";
          if (($this->auth->expense() - $person->balance) <= 0){
              $html.= "<div class='card-text' >Wallet:</div>
                <div class='card-text'> ".$person->balance - $this->auth->expense()." </div>";
              }else{
                $html.="
                <div class='card-text' >Wallet:</div>
                <div class='card-text'> ".$person->balance." </div>
                <div class='card-text' >Treba da doplati:</div>
                <div class='card-text'>". ($this->auth->expense() - $person->balance) ." </div>
                <div class='col-lg-6' style='padding-left:0px;'>
                  <div class='input-group'>
                    <input type='text' class='form-control inputMoney' placeholder='Add Money' aria-label='Add Money'>
                    <span class='input-group-btn'>
                    <button class='btn btn-secondary addMoney' type='button'>Add</button>
                    </span>
                  </div>
                </div>";
             }
             $html .=" </div> </div>";

        echo $html;
      }
    }
  }
}
