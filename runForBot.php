<?php

//***************************
//*********以下皆為函數******
//***************************

//**************************************
//***對訊息內容做判斷，並指定回應內容***
//**************************************
function doType($type,$message_data){
  switch ($type){
    case "text":
      $message_data = doText($message_data);
      break;
    case "image":
      $message_data = "很抱歉我有職務在身無法單獨回應您的圖片。";
      break;
    case "video":
      $message_data = "Sorry~我有職務在身無法單獨回應您的影片。";
      break;
    case "audio":
      $message_data = "抱歉！目前忙碌中無法回應您的語音。";
      break;
    case "sticker":
      $message_data = "貼圖不錯唷~";
      break;
    case "location":
      $message_data = "收到!";
      break;
  }
  return $message_data;
}

//**********************
//***函數：type是text***
//**********************

function doText($message_data_text){
  switch($message_data_text){
    case "cmd_GetInto":
      $message_data_text = "cmd_GetInto";
      break;
    case "cmd_GetOut":
      $message_data_text = "安全抵達，收到！";
      break;
    case "cmd_Emergency":
      $message_data_text = "通知緊急聯絡人!!";
      break;
    case "cmd_Cancel":
      $message_data_text = "cmd_Cancel";
      break;
    case "cmd_Cancel_Yes":
      $message_data_text = "已取消搭乘";
      break;
    case "cmd_Cancel_No":
      $message_data_text = "請繼續使用";
      break;
    case "cmd_GetInto_Yes":
      $message_data_text = "確定搭乘。\n";
      $message_data_text .= "請提供以下資訊\n(請按照格式輸入)\n";
      $message_data_text .= "車牌!預計幾分鐘到達";
      break;
    case "cmd_GetInto_No":
      $message_data_text = "沒要搭乘。";
      break;
  }
  return $message_data_text;
}


//***************
//***bot的回應***
//***************

function doPostData($reply_token,$message_data){
  switch($message_data){
    case "cmd_GetInto":
      $post_data = [
      "replyToken" => $reply_token,
      "messages" => [
                      [
        //傳送型態及內容
                    "type"=> "template",
              "altText"=> "this is a confirm template",
              "template"=> [
                  "type"=> "confirm",
                  "text"=> "是否啟用安全搭乘?",
                  "actions"=> [
                      [
                        "type"=> "message",
                        "label"=> "是",
                        "text"=> "cmd_GetInto_Yes"
                      ],
                      [
                        "type"=> "message",
                        "label"=> "否",
                        "text"=> "cmd_GetInto_No"
                      ]
                  ]
              ]
          ]
        ]
      ];
      return $post_data;
      break;
    case "cmd_Cancel":
      $post_data = [
      "replyToken" => $reply_token,
      "messages" => [
                      [
        //傳送型態及內容
                    "type"=> "template",
              "altText"=> "this is a confirm template",
              "template"=> [
                  "type"=> "confirm",
                  "text"=> "確定取消搭乘?",
                  "actions"=> [
                      [
                        "type"=> "message",
                        "label"=> "是",
                        "text"=> "cmd_Cancel_Yes"
                      ],
                      [
                        "type"=> "message",
                        "label"=> "否",
                        "text"=> "cmd_Cancel_No"
                      ]
                  ]
              ]
          ]
        ]
      ];
      return $post_data;
      break;
    default:
      $post_data = [
      "replyToken" => $reply_token,
      "messages" => [
                      [
        //傳送型態及內容
        "type" => "text",
        "text" => $message_data
          ]
        ]
      ];
      return $post_data;
      
    
  }
  
}

//*********************
//***bot發訊息及關閉***
//*********************

function doBotPost($post_data,$access_token,$file){
  $ch = curl_init("https://api.line.me/v2/bot/message/reply");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Authorization: Bearer '.$access_token
      //'Authorization: Bearer '. TOKEN
  ));
  $result = curl_exec($ch);
  fwrite($file, $result."\n");  
  fclose($file);
  curl_close($ch); 
}

?>
