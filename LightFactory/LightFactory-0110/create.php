<!-- 
2-4-26 2620160515 竹永正輝
-->

<?php
//formから受け取っているかチェック
if(isset($_GET["arduinoLightPattern"]) && isset($_GET["arduinoColorPattern"])) {
  $light=$_GET["arduinoLightPattern"];
  $color=$_GET["arduinoColorPattern"];
  }
?>

<!DOCTYPE HTML>
<html>

<head>
  <title>CREATE</title>
  <meta charset="utf-8">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/1.1.3/anime.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <link rel="stylesheet" type="text/css" href="create.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>
  <?php
  //formから文字列を受け取ったならばjsonファイルに書き込み
  if(isset($_GET["arduinoLightPattern"])) {
    $mes=$light.$color;
    $arr = array(
      "data" => $mes
    );
    $arr = json_encode($arr);
    file_put_contents("data.json" , $arr);
    }
  ?>
  <script type="text/javascript">

  //書き込まれた内容が適しているかどうか判定
  //Listに登録
  function setvalue() {
      if(counter<10) return false;
      if(setColor==false) return false;
      var sendLightPattern=document.getElementById("sendLightPattern");
      document.myform.lightPattern.value=sendLightPattern.innerHTML;
      var sendColorPattern=document.getElementById("sendColorPattern");
      document.myform.colorPattern.value=sendColorPattern.innerHTML;
      //alert("送られる内容は"+document.myform.lightPattern.value+"色"+document.myform.colorPattern.value+"タイトル"+document.myform.title.value+"になります");
    }

  //自身にjsonファイルに書き込む文字列送信
    function setArduino() {
      if(counter<10) return false;
      if(setColor==false) return false;
      var sendLightPattern=document.getElementById("sendLightPattern");
      document.anotherForm.arduinoLightPattern.value=sendLightPattern.innerHTML;
      var sendColorPattern=document.getElementById("sendColorPattern");
      document.anotherForm.arduinoColorPattern.value=sendColorPattern.innerHTML;
      //alert("送られる内容は"+document.anotherForm.arduinoLightPattern.value+"色"+document.anotherForm.arduinoColorPattern.value+"になります");
    }

   //パターンを押すたびに記録していく関数(jQuery)
      var counter=0;
      var setColor=false;
   $(function() {
      //Light Pattern追加
      $(".pattern").click(function () {
          if(counter>=10) return;
          var src=$(this).children('img').attr('src');
          var leftpos=counter*90+134;
          $(displayLightPattern).append('<img src="'+src+'"width="79" height="79" style="position:absolute; top:449px; left:'+leftpos+'px">');
          $(sendLightPattern).append(src.substr(0,1));
          counter++;
      });
      //Light Pattern消去
      $(".b1").click(function () {
        counter=0;
          $(sendLightPattern).empty();
          $(displayLightPattern).empty();
      });
      //Colortheme追加
      $(".colortheme").click(function () {
        setColor=true;
      var imgsrc=$(this).children('img').attr('src');
      $(displayColorPattern).html('<img src="'+imgsrc+'"width="79" height="79">');
        $(sendColorPattern).html(imgsrc.substr(0,1));
       });
      //Colortheme消去
      $(".b2").click(function () {
        setColor=false;
        $(displayColorPattern).empty();
        $(sendColorPattern).empty();
      });
   });
  </script>


  <div class="image">
    <a class="background"><img src="create.gif" width="1366" height="768" /></a>

    <a class="lp1 pattern"><img src="0.gif" width="120" height="50" /></a>
    <a class="lp2 pattern"><img src="1.gif" width="120" height="50" /></a>
    <a class="lp3 pattern"><img src="2.gif" width="120" height="50" /></a>
    <a class="lp4 pattern"><img src="3.gif" width="120" height="50" /></a>
    <a class="lp5 pattern"><img src="4.gif" width="120" height="50" /></a>
    <a class="lp6 pattern"><img src="5.gif" width="120" height="50" /></a>
    <a class="lp7 pattern"><img src="6.gif" width="120" height="50" /></a>
    <a class="lp8 pattern"><img src="7.gif" width="120" height="50" /></a>
    <a class="lp9 pattern"><img src="8.gif" width="120" height="50" /></a>
    <a class="lp10 pattern"><img src="9.gif" width="120" height="50" /></a>

    <a class="b1"><img src="ボタン左.png" width="55" height="80" /></a>
    <a class="b2"><img src="ボタン右.png" width="50" height="75" /></a>


    <div class="Arduino">
    <form action="create.php" method="get" onsubmit="return setArduino()" name="anotherForm">
      <input type="hidden" name="arduinoLightPattern" value="00" >
      <input type="hidden" name="arduinoColorPattern" value="00" >
      <input type="image" src="connectボタン.png" value="Arduinoに送信">
      </form>
     </div>


    <div class="list">
     <input type="image" src="LIST.png" width="110" height="140" onclick="location.href='form_receive.php'"value="リストへ">
    </div>


    <div class="register">
      <form action="form_receive.php" method="get" onsubmit="return setvalue()" name="myform">
      <input type="text" name="title" value="" style="font-size:15px;" size="15"><br>
      <input type="hidden" name="lightPattern" value="00" >
      <input type="hidden" name="colorPattern" value="00" >
      <input type="image" src="registerボタン.png" width="210" height="48" value="登録して一覧を見る">
      </form>
    </div>


    <div class="timeline">
      <div id="displayLightPattern"></div>
    </div>
    <div hidden id="sendLightPattern"></div>


    <div class="color">
      <li class="colortheme"><img src="0.png" width="60" height="130" style="position:absolute; top:210px; left:960px;" /></li>
      <li class="colortheme"><img src="1.png" width="60" height="130" style="position:absolute; top:210px; left: 1055px;" /></li>
      <li class="colortheme"><img src="2.png" width="60" height="130" style="position:absolute; top:210px; left: 1150px;" /></li>
    </div>
  </div>


  <div class="timelineColor">
   <div id="displayColorPattern"></div>
  </div>
  <div hidden id="sendColorPattern"></div>


  <script type="text/javascript" src="create.js"></script>
  <script src="anime.min.js"></script>
</body>


<div class="light">
  <a href="top.html">
  <svg height="1000"  width="2000" xmlns="http://www.w3.org/2000/svg">
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


</html>
