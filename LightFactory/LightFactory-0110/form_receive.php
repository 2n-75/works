<?php
//受け取り処理
$receive = -1;
$colortheme = -1;
$title = 'No title';
if (isset($_GET['lightPattern'])) {
    $receive = $_GET['lightPattern'];
    $formatlight = (string) str_pad($receive, 10, 0, STR_PAD_LEFT);
}
if (isset($_GET['colorPattern'])) {
    $colortheme = $_GET['colorPattern'];
}
if (isset($_GET['title'])) {
    $title = $_GET['title'];
}

if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
}

if (isset($_GET['arduinoLightPattern']) && isset($_GET['arduinoColorPattern'])) {
    $light = $_GET['arduinoLightPattern'];
    $color = $_GET['arduinoColorPattern'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <link rel="stylesheet" type="text/css" href="form_receive.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
<?php
    ////formから文字列を受け取ったならばjsonファイルに書き込む
    if (isset($_GET['arduinoLightPattern'])) {
        $mes = $light.$color;
        $arr = array(
        'data' => $mes,
      );
        $arr = json_encode($arr);
        file_put_contents('data.json', $arr);
    }
    ?>

<div class="create">
    <a href="create.php"><img src="createback.png" width="120" height="80"></a>
</div>

<div class="light">
    <a href="top.html">
    <svg height="1000" width="2000" xmlns="http://www.w3.org/2000/svg">
    <path stroke="#0076ff"
    d="M13.7,68H50.1V78.9H.1V1.3H13.7Z"/>
    <path stroke="#0076ff"
    d="M80.1,78.9H66.5V1.3H80.1Z"/>
    <path stroke="#0076ff"
     d="M148.5,25.8c-1.9-9.8-7.2-15.4-16.1-15.4-11.8,0-19.7,10-19.7,29.8S120.4,70,132.2,70s16.1-8.7,17.3-21.1H131.4V38.5h30.7V79.4h-9l-1.5-11.3c-4,8-11.5,12.3-20.8,12.3-19.3,0-32.4-14.4-32.4-40.1S112.3,0,132.2,0c16.1,0,26.1,9,29.4,22.7Z"/>
    <path stroke="#0076ff" d="M242.2,78.9H228.8v-35H196.5v35H183.1V1.3h13.4V32.9h32.3V1.3h13.4Z"/>
    <path stroke="#0076ff"
    d="M318.2,12.2H295.1V78.9H281.5V12.2H258.6V1.3h59.6Z"/>
    <path stroke="#0076ff"
    d="M33.2,105.5H8.8v13.6H27.3v7H8.8V149H0V98.6H33.2Z"/>
    <path stroke="#0076ff" d="M80.9,149H71.8l-4.3-14.8H52L47.6,149H39.3L54.8,98.6H65.4ZM63,117.5c-1.1-4-1.9-7.7-3-12.4h-.6c-1.1,4.8-2,8.5-3.1,12.4l-2.7,10.3H65.9Z"/>
    <path stroke="#0076ff" d="M120.8,115.1c-1.2-7-4.9-10.7-10.6-10.7-7.5,0-12.7,6.5-12.7,19.4s5.3,19.4,12.7,19.4,9.7-3.4,11.1-10.8l8.3,2.5c-2.7,10.4-9.2,15-19.4,15-13.1,0-21.9-9.4-21.9-26.1s8.9-26.2,21.9-26.2c10.3,0,17.1,5.8,19.2,15.3Z"/>
    <path stroke="#0076ff"
    d="M175,105.7H160V149h-8.8V105.7H136.3V98.6H175Z"/>
    <path stroke="#0076ff" d="M224.8,123.8c0,16.6-8.7,26.2-21.6,26.2s-21.6-9.4-21.6-26.1,8.8-26.2,21.6-26.2S224.8,107.1,224.8,123.8Zm-33.9.1c0,12.9,4.9,19.4,12.3,19.4s12.3-6.5,12.3-19.4-4.9-19.4-12.3-19.4S190.9,110.9,190.9,123.9Z"/>
    <path stroke="#0076ff" d="M252.3,98.6c12.3,0,17.9,4.9,17.9,13.7,0,6.2-3.5,10.9-10,12.8L271.8,149h-9.4l-10.2-22.6h-9.4V149h-8.6V98.6ZM242.7,120h8.8c6.9,0,9.9-2.9,9.9-7.5s-2.5-7.3-9.5-7.3h-9.2Z"/>
    <path stroke="#0076ff" d="M300.1,128.2V149h-8.8V128.2L275,98.6h9.8l10,18.8c.7,1.2,1,1.9,1.6,3.1.6-1.2.9-1.9,1.6-3.1l9.7-18.7h9Z"/>
  </g></svg></a>
  </div>

    <?php
    //データベース追加・削除・表示(降順)
    $pdo = new PDO('sqlite:data.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    if ($receive != -1) {
        $st = $pdo->prepare('INSERT INTO data(title,light,color) VALUES(?,?,?)');
        $st->execute(array($title, $formatlight, $colortheme));
    }
    if (isset($delete)) {
        $pdo->query("DELETE FROM data WHERE id='$delete'");
    }
    $st = $pdo->query('SELECT * FROM data ORDER BY id DESC;');
    $data = $st->fetchAll();
    $listCounter = 0;

    //背景画像描画
    foreach ($data as $line) {
        $top = 150 * $listCounter;
        $listpageTop = 150 * $listCounter + 8;
        //listpage
        echo '<a class="list"><img src="listgage.png" width="1100" height="130" style="position:relative; top:'.$listpageTop.'px; left:110px;"/></a>';
        ++$listCounter;
    }

    //画像form描画
    $listCounter = 0;
    foreach ($data as $line) {
        $top = 150 * $listCounter;

        //connect
        $conectTop = $top + 25;
        echo '<a class="connect list"><form action=form_receive.php method=get name="anotherForm">';
        echo '<input type=hidden name=arduinoLightPattern value="'.$line['light'].'">';
        echo '<input type=hidden name=arduinoColorPattern value="'.$line['color'].'">';
        echo '<input type="image" src="conect.png" width="225" height="60" style="position:absolute; top:'.$conectTop.'px;left:40px; value="-1">';
        echo '</a></form>';

        //delete
        echo '<a class="delete list"><form action=form_receive.php method=get>';
        echo '<input type=hidden name=delete value="'.$line['id'].'">';
        echo '<input type="image" src="delete.png" width="110" height="120" style="position:absolute; top:'.$top.'px;"/></a></form>';

        //title
        $titleTop = $listCounter * 150 + 160;
        echo '<a class="title" style="position:absolute; top:'.$titleTop.'px; left:170px;">'.$line['title'].'</a>';

        //gif
        for ($i = 0; $i < strlen($line['light']); ++$i) {
            $gifTop = $top + 183;
            $left = $i * 75 + 369;
            echo '<a class="gif" style="position:absolute; top:'.$gifTop.'px;left:'.$left.'px;"><img src="'.substr($line['light'], $i, 1).'.gif" width="65" height="60"/></a>';
        }

        //color
        for ($i = 0; $i < strlen($line['color']); ++$i) {
            $colorTop = $top + 172;
            echo '<a class="color" style="position:absolute; top:'.$colorTop.'px;left:1120px;"><img src="'.substr($line['color'], $i, 1).'.png" width="63" height="71"/></a>';
        }
        ++$listCounter;
    }
    ?>

  <script type="text/javascript" src="create.js"></script>
  <script src="anime.min.js"></script>
</body>
</html>
