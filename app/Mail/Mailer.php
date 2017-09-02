<?php

namespace App\Mail;

class Mailer
{
  protected $mailer;
  public function __construct($mailer)
  {
    $this->mailer = $mailer;
  }
  public function send($email, $subject, $body)
  {
    $this->mailer->addAddress($email);
    $this->mailer->Subject = $subject;
    $this->mailer->Body    = $body;
    $this->mailer->send();
  }
}
