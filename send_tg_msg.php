<?php

function send_message_TG($message)
{
  if (!IS_ALTER_ACTIVE) return;

  $botToken = BOT_TOKEN;

  $url = "https://api.telegram.org/bot$botToken/sendMessage";

  $data = [
    'chat_id' => CHAT_ID,
    'text' => $message
  ];
  $options = [
    "http" => [
      "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
      "method"  => "POST",
      "content" => http_build_query($data),
    ],
  ];
  $context  = stream_context_create($options);
  file_get_contents($url, false, $context);
}
