<?php

$url = 'https://c10724352.web.cddbp.net/webapi/xml/1.0/';

$artist = $_POST['artist_name'];

//xmlを動的に生成する

$rootNode = new SimpleXMLElement( "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><QUERIES></QUERIES>" );

// ノードの追加
$itemNode = $rootNode->addChild('AUTH');
$itemNode->addChild( 'CLIENT', '10724352-6D077E37B9CB5A58E6E73AB266FC8451' );
$itemNode->addChild( 'USER', '260456452498726945-F39906C432417E9C13DEAD74D9AED8EF' );
 
$itemNode = $rootNode->addChild('QUERY');
$itemNode->addAttribute('CMD', 'ALBUM_SEARCH');

$textNode = $itemNode->addChild( 'TEXT', $artist );
$textNode->addAttribute('TYPE','ARTIST');

$optionNode = $itemNode->addChild('OPTION');
$optionNode->addChild('PARAMETER','SELECT_EXTENDED');
$optionNode->addChild('VALUE','COVER,ARTIST_IMAGE,ARTIST_OET,MOOD');

$optionNode = $itemNode->addChild('OPTION');
$optionNode->addChild('PARAMETER','SELECT_DETAIL');
$optionNode->addChild('VALUE','GENRE:3LEVEL,MOOD:2LEVEL,TEMPO:3LEVEL,ARTIST_ORIGIN:4LEVEL,ARTIST_ERA:2LEVEL,ARTIST_TYPE:2LEVEL');

$xml = $rootNode->asXML();


// xmlをphpで送る

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

$result = curl_exec($ch);

//response xmlを読み込んで配列に入れる

$obj = simplexml_load_string($result);

$album = $obj->RESPONSE->ALBUM;
foreach($album as $a){
$title[] = $a->TITLE;
$cover_url[] = $a->URL;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
  <title>Document</title>
  <link rel="stylesheet" type="text/css" href="css/base.css">
</head>
<body class="result pages">
  <header>
  <h1><img src="img/logo.png"></h1>
  </header>
  <p>アーティスト"<?php echo $artist ?>"の検索結果</p>
  <ul>
 <!--   <li class="on">
      <img src="http://lorempixel.com/200/200/fashion">
      <div class="share"><img src="img/icon_share.png"></div>
    </li>-->
    <?php
    $m = "";
    for($i=0; $i<10; $i++){ 
      $m = $m."<li><img src=\"". $cover_url[$i] ."\" alt=\"\"><p>". $title[$i] ."</p></li>";
    }
    echo $m;
    ?>
  </ul>
  <p><a href="search.php">戻る</a></p>
  <?php 
  //var_dump($result);
  //var_dump($cover_url);
  //var_dump($obj);
  ?>
</body>
</html>