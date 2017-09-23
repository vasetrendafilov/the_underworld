<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\Payments;

class AjaxController extends Controller
{
  public function getPayments($request, $response){

    $user = $this->auth->user();
    $payments = Payments::where('user_id', $user->id)->get();
    foreach ($payments as $payment) {
      echo '<div class="row">
      <div class="col-sm-8">'.$payment->description.'</div>
      <div class="col-sm-4">'.$payment->charge.'</div>
     </div>';
    }

  }
}
