using System.Collections;
using System.Collections.Generic;

//using UnityEngiusing System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class WebCamController : MonoBehaviour
{

	int width = 640;
	int height = 480;
	int fps = 30;
	Texture2D texture;
	WebCamTexture webcamTexture;
	Color32[] colors = null;
	public static bool camFlag=false;

	public GameObject defaultPS;
	public GameObject mainPS;
	public GameObject effectPS;
	//ピクセルの画素値を保存する配列

	IEnumerator Init ()
	{
		while (true) {
			if (webcamTexture.width > 16 && webcamTexture.height > 16) {
				colors = new Color32[webcamTexture.width * webcamTexture.height];
				texture = new Texture2D (webcamTexture.width, webcamTexture.height, TextureFormat.RGBA32, false);
				GetComponent<Renderer> ().material.mainTexture = texture;
				break;
			}
			yield return null;
		}
	}

	// Use this for initialization
	void Start ()
	{
		WebCamDevice[] devices = WebCamTexture.devices;
		//Debug.Log (devices.Length);
		Debug.Log (devices);
		//【適切なカメラに設定する】
		webcamTexture = new WebCamTexture (devices [2].name, this.width, this.height, this.fps);
		webcamTexture.Play ();

		StartCoroutine (Init ());
	}

	// Update is called once per frame
	void Update ()
	{
		//画像が認識されたとき
		if (colors != null) {
			webcamTexture.GetPixels32 (colors);
			//画像の表示
			texture.SetPixels32 (colors);
			texture.Apply ();
		
			//クリックした時のカメラ画像を取得する
			if (camFlag == true) {
				webcamTexture.GetPixels32 (colors);
				//平均色を求めて、エフェクトの色を設定する
				setAverageColor (webcamTexture);
				camFlag = false;

				effectsControl.shapeNum = Random.Range (0, 4);
				//エフェクトを描画する関数の呼び出し
				defaultPS = GameObject.Find ("defaultparticle");
				mainPS = GameObject.Find ("MainParticle");
				effectPS = GameObject.Find ("effect");

				//DefaultParticleスクリプトの　particlePlay という関数を使用する
				defaultPS.GetComponent<DefaultParticle> ().defalutEffects ();
				mainPS.GetComponent<MainParticle> ().mainEffects ();
				effectPS.GetComponent<effectsControl> ().drawEffects ();

			}
		}
	}


	void serialOn(){
		SerialController.catchSign=true;//色の読み取りを許可する
	}
	int averageH;
	int averageS;
	int averageV;
	//各ピクセルごとのHSVの値を求めてリストに入れる
	public void setAverageColor (WebCamTexture image)
	{
		averageH = 0;
		averageS = 0;
		averageV = 0;
		//色取得範囲を設定する
		int width = image.width / 2;
		int height = image.height / 2;
	
		for (int x = 0; x < width; x++) {
			for (int y = 0; y < height; y++) {
				Color pixelRGB = image.GetPixel (width / 2 + x, height / 2 + y);
				int h = pixelRGB.h ();
				int s = pixelRGB.s ();
				int v = pixelRGB.v ();
				averageH = averageH + h;
				averageS = averageS + s;
				averageV = averageV + v;
			}
		}
		//平均色を求める
		float H = averageH / (width * height);
		float S = averageS / (width * height);
		float V = averageV / (width * height);
		Debug.Log (H + "," + S + "," + V);
		//Color avrColor = ColorHSV.FromHsv ((int)H, (int)S, (int)V);
		//Debug.Log (avrColor.r * 255+","+avrColor.g*255+","+avrColor.b*255);
		//Debug.Log ("average:" + avrColor);
		setEffectColor (H);
	}

	//読み取った色に一番近い色をエフェクトの色に設定する
	public void setEffectColor (float Hue)
	{
		if (Hue < 10f || 330f < Hue && Hue < 360f) {
			Debug.Log ("red");
			effectsControl.colorNum = 0;
		} 
		else if (10f <= Hue && Hue < 35f) {
			Debug.Log ("orange");
			effectsControl.colorNum = 1;
		} 
		else if (35f <= Hue && Hue < 65f) {
			Debug.Log ("yellow");
			effectsControl.colorNum = 2;
		} 
		else if (65f <= Hue && Hue < 95f) {
			Debug.Log ("lightGreen");
			effectsControl.colorNum = 3;
		} 
		else if (95f <= Hue && Hue < 150f) {
			Debug.Log ("green");
			effectsControl.colorNum = 4;
		} 
		else if (150f <= Hue && Hue < 215f) {
			Debug.Log ("lightBlue");
			effectsControl.colorNum = 5;
		} 
		else if (215f <= Hue && Hue < 260f) {
			Debug.Log ("blue");
			effectsControl.colorNum = 6;
		} 
		else if (260f <= Hue && Hue < 290f) {
			Debug.Log ("purple");
			effectsControl.colorNum = 7;
		} 
		else if (290f <= Hue && Hue <= 330f) {
			Debug.Log ("pink");
			effectsControl.colorNum = 8;

		} else {
			Debug.Log ("uuuuuu");
		}
	}
}