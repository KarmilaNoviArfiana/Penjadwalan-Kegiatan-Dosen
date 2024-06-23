<?php

namespace App\Helpers;

class WaSender
{
  public static $host = "http://localhost:3000";

  public static function send($phone, $message)
  {
    $ch = curl_init(self::$host . "/send");
    # Setup request to send json via POST.
    $payload = json_encode([
      "phone" => $phone,
      "message" => $message
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    // dd($result);
    curl_close($ch);
  }
}
