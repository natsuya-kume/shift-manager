<?php
$message=include_once("./getShiftData.php");
echo $message;
// HTTPヘッダを設定
$config =parse_ini_file("config.ini",false);
$headers = [
	'Authorization: Bearer ' . $config['channel_token'],
	'Content-Type: application/json; charset=utf-8',
];

// POSTデータを設定してJSONにエンコード
$post = [
	'to' => $config['user_id'],
	'messages' => [
		[
			'type' => 'text',
			'text' => $message,
		],
	],
];
$post = json_encode($post);

// HTTPリクエストを設定
$ch = curl_init($config['request_url']);
$options = [
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_HTTPHEADER => $headers,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_BINARYTRANSFER => true,
	CURLOPT_HEADER => true,
	CURLOPT_POSTFIELDS => $post,
];
curl_setopt_array($ch, $options);

// 実行
$result = curl_exec($ch);

// エラーチェック
$errno = curl_errno($ch);
if ($errno) {
	return;
}

// HTTPステータスを取得
$info = curl_getinfo($ch);
$httpStatus = $info['http_code'];

$responseHeaderSize = $info['header_size'];
$body = substr($result, $responseHeaderSize);

// 200 だったら OK
echo $httpStatus . ' ' . $body;
