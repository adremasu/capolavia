<?php
  $apiToken = "6583450275:AAH3CVOPr7YRTJAn_weHxaQbD_CZ6GWX5z4";
  $data = [
      'chat_id' => '155860140',
      'text' => 'Hello from PHP!'
  ];
  $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
                                 http_build_query($data) );
?>
