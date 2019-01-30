
//いつも出てるやつ
//色が変化する

using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class MainParticle : MonoBehaviour
{
	public ParticleSystem mainParticle;
	public Color[] clr = new Color[9];
	public AudioSource audioSource;
	// Use this for initialization
	void Start ()
	{
		clr [0] = new Color (253 / 255f, 150 / 255f, 180 / 255f);//red
		clr [1] = new Color (255 / 255f, 200 / 255f, 100 / 255f);//orange
		clr [2] = new Color (255 / 255f, 255 / 255f, 130 / 255f);//yellow
		clr [3] = new Color (130 / 255f, 255 / 255f, 130 / 255f);//lightGreen
		clr [4] = new Color (120 / 255f, 220 / 255f, 120 / 255f);//green
		clr [5] = new Color (130 / 255f, 255 / 255f, 255 / 255f);//lightBlue
		clr [6] = new Color (90 / 255f, 116 / 255f, 255 / 255f);//blue
		clr [7] = new Color (200 / 255f, 160 / 255f, 255 / 255f);//purple
		clr [8] = new Color (255 / 255f, 180 / 255f, 245 / 255f);//pink

	}
	
	// Update is called once per frame
	void Update ()
	{

	}

	public void mainEffects ()
	{
		audioPlay();//魔法がかかる音
		Invoke ("colorSelect", 1.5f);
		Invoke ("defaultColor", 5f);
	}

	void audioPlay ()
	{
		audioSource.gameObject.GetComponent<AudioSource> ();
		audioSource.Play ();
	}

	void colorSelect ()
	{
		ParticleSystem.MainModule par = mainParticle.GetComponent<ParticleSystem>().main;
		par.startColor = clr [effectsControl.colorNum];

		mainParticle.Play ();
	}

	void defaultColor ()
	{
		
		ParticleSystem.MainModule par = mainParticle.GetComponent<ParticleSystem>().main;
		par.startColor = new Color (212 / 255f, 255 / 255f, 255 / 255f);
	}
}
