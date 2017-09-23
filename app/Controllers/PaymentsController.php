<?php

namespace App\Controllers;
use App\Models\Payments;

class PaymentsController extends Controller
{
  public function getPayments($request, $response)
  {
    $payments = Payments::where('user_id', $this->auth->user()->id)->orderBy('created_at', 'desc')->get();
    return $this->view->render($response, 'payments.twig',[
     'payments' => $payments
    ]);
  }
  public function postPayments($request, $response)
  {
    $description = $request->getParam('description');
    $charge = $request->getParam('charge');
    $v = $this->Validator->validate([
      'description' => [$description,'required|max(20)'],
      'charge' => [$charge,'required|int']
    ]);
    if ($v->passes()){
      $user = $this->auth->user();
      $user->addPayment()->create([
        'description' => $description,
        'charge' => $charge * $user->people
      ]);
        $this->flash->addMessage('info','You added another payment');
      return $response->withRedirect($this->router->pathFor('payments'));
    }
  }
}
