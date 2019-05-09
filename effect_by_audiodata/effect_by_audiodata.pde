
//sin波と円がランダムに出る
//enterで切り替え

// Minim を使用する準備です
import ddf.minim.analysis.*;
import ddf.minim.*;
Minim minim;

AudioPlayer player;

FFT fft;
int _rectColor;
int count=0;
boolean flag=false;
boolean breakFlg = false;

void setup() {
  size(800, 600);
  //fullScreen();
  minim= new Minim(this);

  player = minim.loadFile("data/SAKYU.wav", 128);
  player.play();

  fft= new FFT(player.bufferSize(), player.sampleRate());
  if ( player == null ) {
    println( "loadFile() error" );
    breakFlg = true;
  } 
  if ( breakFlg == false && 
    player.hasControl( Controller.GAIN ) == false ) {
    println( "gain is not supported" );
    breakFlg = true;
  }
}
void draw() {
  frameRate(30);
  smooth();
  //background(0);
  fill(#FFFAFF, 100);
  rect(0, 0, width, height);
  fft.forward(player.mix);//FFT 実行

  //ここに描画するものを書く
  if (flag==true) {
    e_colorSelect();
    e_display();
    sin_display();
  } else {
    sin_display();
  }
}
void keyPressed() {
  if (keyCode==ENTER) {
    if (flag==true) {
      flag=false;
    } else {
      flag=true;
    }
  }
  float  myGain;

  //現在のゲイン値を取得する
  myGain = player.getGain();

  if ( key == 'u' ) {
    if (  myGain <= 5.0 ) {
      //MAXまでゲインを上げる
      myGain = myGain +1;
    }
  } else if ( key == 'd' ) {
    if ( myGain >= -79.0 ) {
      //MINまでゲインを下げる
      myGain = myGain -1;
    }
  }
  player.setGain( myGain );
}
//音量＝半径の円がランダムな位置に描画される
void e_colorSelect() {
  color [] rectColor= {
    color(#FAFF00), color(#00FFF9), color(#FF003C), color(#00FF5B)
  }; 
  if (frameCount%14==0) {
    _rectColor=int(random(rectColor.length));
  }
  fill(rectColor[_rectColor], 150);
}
void e_display() {
  int specSize =fft.specSize();
  int [] x = new int [specSize];
  int [] y = new int [specSize];
  if (frameCount%14==0) {
    for (int i=0; i<specSize; i++) {    
      x[i]=(int)random(50, width-50);
      y[i]=(int)random(50, height-50);
      noStroke();
      ellipse(x[i], y[i], fft.getBand(i)*25, fft.getBand(i)*25);
    }

    /*fill(0, 200);
     for (int i=0; i<specSize; i++) {
     x[i]=(int)random(width);
     y[i]=(int)random(height);
     noStroke();
     ellipse(x[i], y[i], fft.getBand(i)*25, fft.getBand(i)*25);
     }*/
  }
}

//左右ステレオの波形が描画される
void sin_display() {
  beginShape();
  stroke(#00F9FF, 150);
  fill(#00F9FF, 150);
  for (int  i  =  0; i  <  player.bufferSize(); i++) {
    float  x=map(i, 0, player.bufferSize(), 0, width-1);
    vertex(x, (height/2)  +  player.left.get(i)*50);
  }
  endShape();
  beginShape();
  stroke(#FF003C, 150);
  fill(#FF003C, 150);
  for (int  i  =  0; i  <  player.bufferSize(); i++) {
    float  x=map(i, 0, player.bufferSize(), 0, width-1);
    vertex(x, (height/2)  +  player.right.get(i)*50);
  }
  endShape();
}

void stop() {
  minim.stop();
  super.stop();
}