/*
   jSONファイルの内容をGETして、
   取得したデータをもとにLEDテープを光らせるプログラム
   参考にしたサンプル：BasicHTTPClient.ino
*/

/*ライブラリインクルード*/
#include <Adafruit_NeoPixel.h>
#include <Arduino.h>
#include <Wire.h>k
#include <ArduinoJson.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>

#define USE_SERIAL Serial

/*wifi関係*/
ESP8266WiFiMulti WiFiMulti;
/* SSID
   パスワード
   accessURL
   の3つは適宜変更してください。
*/
char ssid[] = "";
char password[] = "";
String accessURL = "http://192.168.100.101:8000/LightFactory-0110/data.json"; //data.jsonがある場所のURL
String data = "";
int pattern[11];

/*LED関係*/
#define MAX_VAL (150)
#define LED_COUNT ( 60 )//LEDテープのLEDの数
#define DIGITAL_PIN ( 13 )//出力ピンの番号
int LED3 = LED_COUNT / 3;//LEDの長さの3分の1
int c;//光の強さを変化させるときに使う
Adafruit_NeoPixel led = Adafruit_NeoPixel( LED_COUNT, DIGITAL_PIN , NEO_GRB + NEO_KHZ800);
//色関係
int colorThema;
int colorNum;
int R, G, B;

/*jsonをGETして、受け取ったデータを配列にいれる*/
//文字列の分割
int split(String data, char delimiter, String *dst) {
  int index = 0;
  int arraySize = (sizeof(data) / sizeof((data)[0]));
  int datalength = data.length();
  for (int i = 0; i < datalength; i++) {
    char tmp = data.charAt(i);
    if ( tmp == delimiter ) {
      index++;
      if ( index > (arraySize - 1)) return -1;
    }
    else dst[index] += tmp;
  }
  return (index + 1);
}
//受信した文字列から光るパターンを配列に入れる
void data_to_array(String _data) {
  String cmds[4] = {"\0"};//分割された文字列を格納する配列
  _data.trim();
  //分割数 = 分割処理(文字列, 区切り文字, 配列)
  int index = split(_data, '"', cmds);
  String data = cmds[3];//数字列のみを取り出す
  Serial.println(data);
  String buffer = "";
  buffer.concat(data);
  char charArray[buffer.length() + 1];
  buffer.toCharArray(charArray, sizeof(charArray));
  for (int i = 0; i < sizeof(charArray); i++) {
    pattern[i] = charArray[i] - '0';//char型をint型に変換し、配列にいれる
    Serial.println(pattern[i]);
  }
  colorThema = pattern[10];
  Serial.println(colorThema);
}

/*LEDの光パターン*/
//色を決める
uint32_t clr(int colorThema, int colorNum) {
  switch (colorThema) {
    case 0:
      switch (colorNum) {
        case 0:
          R = 255; G = 237; B = 20;
          break;
        case 1:
          R = 14; G = 232; B = 115;
          break;
        case 2:
          R = 58; G = 80; B = 255;
          break;
        case 3:
          R = 255; G = 0; B = 103;
          break;
        case 4:
          R = 255; G = 161; B = 0;
          break;
      }
      break;
    case 1:
      switch (colorNum) {
        case 0:
          R = 255; G = 13; B = 90;
          break;
        case 1:
          R = 232; G = 34; B = 0;
          break;
        case 2:
          R = 255; G = 98; B = 0;
          break;
        case 3:
          R = 255; G = 156; B = 1;
          break;
        case 4:
          R = 255; G = 205; B = 36;
          break;
      }
      break;
    case 2:
      switch (colorNum) {
        case 0:
          R = 0; G = 255; B = 7;
          break;
        case 1:
          R = 0; G = 232; B = 135;
          break;
        case 2:
          R = 0; G = 222; B = 255;
          break;
        case 3:
          R = 1; G = 90; B = 255;
          break;
        case 4:
          R = 42; G = 0; B = 255;
          break;
      }
      break;
  }
  return led.Color(R, G, B);
}
//LEDの光り方10通り
void lighting(int _num) {
  switch (_num) {
    case 0:
      /*全部つける*/
      for (int i = 0; i < LED_COUNT; i++) {
        led.setPixelColor(i, clr(colorThema, colorNum));
      }
      led.show();
      delay(3000);
      break;
    case 1:
      /*だんだん明るくなる*/
      c = 0;
      for (int t = 0; t < 300; t++) {
        for (int i = 0; i < LED_COUNT; i++) {
          led.setPixelColor(i, led.Color(R + c, G + c, B + c));
        }
        led.show();
        c += 1;
        if (c > 255) {
          c = 255;
        }
        delay(10);
      }
      break;
    case 2:
      /*だんだん暗くなる*/
      c = 150;
      for (int t = 0; t < 300; t++) {
        for (int i = 0; i < LED_COUNT; i++) {
          led.setPixelColor(i, led.Color(R + c, G + c, B + c));
        }
        led.show();
        c -= 1;
        if (c < 0) {
          c = 0;
        }
        delay(10);
      }
      break;
    case 3:
      /*レインボーで光る*/
      for (int i = 0; i < LED_COUNT; i++) {
        led.setPixelColor(i, rotateColor((((i) * 256 / LED_COUNT) ) & 255));
      }
      led.show();
      delay(3000);
      break;
    case 4:
      /*1秒間隔で点滅*/
      for (int t = 0; t < 3; t++) {
        //点灯
        for (int i = 0; i < LED_COUNT; i++) {
          led.setPixelColor(i, clr(colorThema, colorNum));
        }
        led.show();
        delay(500);
        //消灯
        for (int i = 0; i < LED_COUNT; i++) {
          led.setPixelColor(i, led.Color(0, 0, 0));
        }
        led.show();
        delay(500);
      }
      break;
    case 5:
      /* 端から1個ずつ順番に光る*/
      for (int i = 0 ; i < LED_COUNT ; i++ )
      {
        led.setPixelColor(i , led.Color(0, 0, 0));
      }
      for (int i = 0 ; i < LED_COUNT ; i++ )
      {
        led.setPixelColor(i - 1, led.Color(0, 0, 0));
        led.setPixelColor(i, clr(colorThema, colorNum));
        led.show();
        delay(50);
      }
      break;
    case 6:
      /*2色で光る*/
      for (int i = 0 ; i < LED_COUNT / 2 ; i++ )
      {
        led.setPixelColor(i, clr(colorThema, colorNum));
      }
      led.show();
      colorNum = random(5);
      for (int i = LED_COUNT / 2; i < LED_COUNT; i++) {
        led.setPixelColor(i, clr(colorThema, colorNum));
      }
      led.show();
      delay(3000);
      break;
    case 7:
      /*外側だけ光る*/
      for (int i = 0 ; i < LED_COUNT / 2 ; i++ )
      {
        led.setPixelColor(i, led.Color(0, 0, 0));
      }

      for (int i = LED_COUNT / 2; i < LED_COUNT; i++) {
        led.setPixelColor(i, clr(colorThema, colorNum));
      }
      led.show();
      delay(3000);
      break;
    case 8:
      /*前半3分の1 と後ろ3分の1が光る*/
      for (int i = 0; i < LED_COUNT; i++) {
        led.setPixelColor(i, led.Color(0, 0, 0));
      }
      led.show();

      for (int i = 0; i < LED3; i++) {
        led.setPixelColor(i, clr(colorThema, colorNum));
      }
      for (int i = LED3 * 2; i < LED_COUNT; i++) {
        led.setPixelColor(i, clr(colorThema, colorNum));
      }
      led.show();
      delay(3000);
      break;
    case 9:
      /*真ん中3分の1だけ光る*/
      for (int i = 0; i < LED_COUNT; i++) {
        led.setPixelColor(i, led.Color(0, 0, 0));
      }
      led.show();

      for (int i = LED3; i < LED3 * 2; i++) {
        led.setPixelColor(i, clr(colorThema, colorNum));
      }
      led.show();
      delay(3000);
      break;
  }
}
//レインボーに光るときの色を決める関数
uint32_t rotateColor(byte WheelPos) {

  if (WheelPos < 85) {
    return led.Color((WheelPos * 3) * MAX_VAL / 255, (255 - WheelPos * 3) * MAX_VAL / 255, 0);
  } else if (WheelPos < 170) {
    WheelPos -= 85;
    return led.Color((255 - WheelPos * 3) * MAX_VAL / 255, 0, (WheelPos * 3) * MAX_VAL / 255);
  } else {
    WheelPos -= 170;
    return led.Color(0, (WheelPos * 3) * MAX_VAL / 255, (255 - WheelPos * 3) * MAX_VAL / 255);
  }
}
//受け取ったデータに応じてLEDを光らせる
void light() {
  for (int i = 0; i < 10; i++) {
    colorNum = random(5);
    Serial.println("hoge");

    Serial.println(colorNum);
    lighting(pattern[i]);
    delay(1);
  }
}
//30秒に1回jsonの中身をGETする
void json_get() {
  // wait for WiFi connection
  if ((WiFiMulti.run() == WL_CONNECTED)) {
    HTTPClient http;
    USE_SERIAL.print("[HTTP] begin...\n");
    http.begin(accessURL); //HTTP
    USE_SERIAL.print("[HTTP] GET...\n");
    // start connection and send HTTP header
    int httpCode = http.GET();
    // httpCode will be negative on error
    if (httpCode > 0) {
      // HTTP header has been send and Server response header has been handled
      USE_SERIAL.printf("[HTTP] GET... code: %d\n", httpCode);

      // file found at server
      if (httpCode == HTTP_CODE_OK) {
        String line = http.getString();
        USE_SERIAL.println(line);
        data_to_array(line);
      }
    } else {
      USE_SERIAL.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }
  delay(1000);
}
void setup() {
  //初期化処理
  USE_SERIAL.begin(115200);
  // USE_SERIAL.setDebugOutput(true);
  USE_SERIAL.println();
  USE_SERIAL.println();
  USE_SERIAL.println();

  for (uint8_t t = 4; t > 0; t--) {
    USE_SERIAL.printf("[SETUP] WAIT %d...\n", t);
    USE_SERIAL.flush();
    delay(1000);
  }
  WiFiMulti.addAP(ssid, password);
  led.begin();
  led.show();
}
//jSONファイルをGETし、30秒間LEDを点灯させるの繰り返し
void loop() {
  json_get();
  light();
}
