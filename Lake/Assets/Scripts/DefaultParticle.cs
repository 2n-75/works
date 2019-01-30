
//白いパーティクル
using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class DefaultParticle : MonoBehaviour
{

	public ParticleSystem defaultParticle;
	public Material[] material = new Material[4];
	// Use this for initialization
	void Start ()
	{
		defaultParticle = this.GetComponent<ParticleSystem> ();

		// ここで Particle System を停止する.
		defaultParticle.Stop ();
		
	}
	
	// Update is called once per frame
	void Update ()
	{
		
	}

	public void defalutEffects(){
		Invoke ("particlePlay", 2.5f);
	}
	public void particlePlay ()
	{
		defaultParticle.GetComponent<Renderer> ().sharedMaterial = material [effectsControl.shapeNum];
		defaultParticle.Play ();
	}
}
