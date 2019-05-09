using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class effectsControl : MonoBehaviour
{

	public static int shapeNum;
	public static int colorNum;
	public ParticleSystem effectParticle;
	public Material[] material = new Material[4];
	private AudioSource audioSource;
	public AudioClip[] SE = new AudioClip[4];
	public Color[] clr = new Color[9];
	// Use this for initialization
	void Start ()
	{
		clr [0] = new Color (253 / 255f, 40 / 255f, 60 / 255f);//red
		clr [1] = new Color (255 / 255f, 126 / 255f, 0);//orange
		clr [2] = new Color (255 / 255f, 255 / 255f, 0);//yellow
		clr [3] = new Color (0, 255 / 255f, 0);//lightGreen
		clr [4] = new Color (0, 160 / 255f, 0);//green
		clr [5] = new Color (0, 255 / 255f, 255 / 255f);//lightBlue
		clr [6] = new Color (0, 0, 255 / 255f);//blue
		clr [7] = new Color (200 / 255f, 0, 255 / 255f);//purple
		clr [8] = new Color (255 / 255f, 40 / 255f, 220 / 255f);//pink


		effectParticle = this.GetComponent<ParticleSystem> ();
		effectParticle.Stop ();
		
	}
	
	// Update is called once per frame
	void Update ()
	{
	}

	public void drawEffects ()
	{
		Debug.Log (shapeNum);
		Invoke ("SESelect", 2f);
		Invoke ("EffectSelect", 2.5f);
		Invoke ("serialOn", 10f);
	}

	void EffectSelect ()
	{
		ParticleSystem.MainModule par = effectParticle.GetComponent<ParticleSystem>().main;
		par.startColor = clr [colorNum];

		//effectParticle.startColor = clr [colorNum];
		effectParticle.GetComponent<Renderer> ().sharedMaterial = material [shapeNum];
		effectParticle.Play ();
	}

	void SESelect ()
	{
		audioSource = gameObject.GetComponent <AudioSource> ();
		audioSource.clip = SE [shapeNum];
		audioSource.Play ();
	}
	void serialOn(){
		Debug.Log ("SerialAccept");
		SerialController.catchSign=true;//色の読み取りを許可する
	}
}
