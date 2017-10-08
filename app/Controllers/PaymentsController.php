<?php

namespace App\Controllers;
use App\Models\Payments;

class PaymentsController extends Controller
{
  public function getPayments($request, $response)
  {
    $payments = Payments::where('user_id', $this->auth->user()->id)->orderBy('id', 'desc')->get();
    $this->auth->paymentsCheck();
    return $this->view->render($response, 'payments.twig',[
     'payments' => $payments,
     'people' => $this->auth->user()->people
    ]);
  }
  public function postPayments($request, $response)
  {
    $description = $request->getParam('description');
    $charge = $request->getParam('charge');
    $v = $this->Validator->validate([
      'description' => [$description,'required|min(10)'],
      'charge' => [$charge,'required|int']
    ]);
    if ($v->passes()){
      $user = $this->auth->user();
      $user->addPayment()->create([
        'description' => $description,
        'charge' => $charge,
        'done' => false
      ]);
        $this->flash->addMessage('info','Додадовте нова задача');
      return $response->withRedirect($this->router->pathFor('home'));
    }
  }
}
