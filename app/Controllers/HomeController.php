<?php

namespace App\Controllers;
use App\Models\People;

class HomeController extends Controller
{
  public function getPeople($request, $response)
  {
    $people = People::where('user_id', $this->auth->user()->id)->get();

    return $this->view->render($response, 'home.twig',[
      'people' => $people
    ]);
  }
  public function postPeople($request, $response)
  {
    $name = $request->getParam('name');

    $user = $this->auth->user();
    $user->update([
      'people' => $user->people + 1
    ]);
    $user->addPerson()->create([
      'name' => $name,
      'balance' => 0
    ]);
    $this->flash->addMessage('info','You added a person');
    return $response->withRedirect($this->router->pathFor('home'));

  }

}
