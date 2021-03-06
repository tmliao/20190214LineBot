<?php
  	$json_str = file_get_contents('php://input'); //接收request的body
  	$json_obj = json_decode($json_str); //轉成json格式
  
  	$myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
  	//fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
  	$sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
  	$sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
  	$sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
  	$msg_json = '{
		"type": "template",
		"altText": "this is a carousel template",
		"template": {
		  "type": "carousel",
		  "actions": [],
		  "columns": [
			{
			  "title": "標題",
			  "text": "文字",
			  "actions": [
				{
				  "type": "message",
				  "label": "動作 1",
				  "text": "動作 1"
				}
			  ]
			},
			{
			  "title": "標題",
			  "text": "文字",
			  "actions": [
				{
				  "type": "uri",
				  "label": "動作 1",
				  "uri": "https://tw.yahoo.com/"
				}
			  ]
			}
		  ]
		}
	  }';
  	$response = array (
		"replyToken" => $sender_replyToken,
		"messages" => array (
			json_decode($msg_json)
		)
  	);
			
  	fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  	$header[] = "Content-Type: application/json";
  	$header[] = "Authorization: Bearer RinZuAxd/Mro8TiOyUHjyL6SVy0NhwLQk6h+NKz4WojC2gIIx0Nzcp0waryjPR58967RkawdWLtjSOyGKbF/SGk0A/ZJGxo6VSkeHqzWByPZF0Uo8w6Cw7u0R7AG4tvD5MAxgcbqVf3SHk9YxRvHwwdB04t89/1O/w1cDnyilFU=";
  	$ch = curl_init("https://api.line.me/v2/bot/message/reply");
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
  	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
  	$result = curl_exec($ch);
  	curl_close($ch);
?>
