using System.Collections;
using System.Collections.Generic;
using System.Threading;
using System;
using System.IO.Ports;
using UnityEngine;
using UniRx;

public class SerialController: MonoBehaviour {

	public string portName;
	public int baurate;

	SerialPort serial;
	bool isLoop=true;
	public static bool catchSign = true;
	public GameObject cam;
	void Start () 
	{
		this.serial = new SerialPort (portName, baurate, Parity.None, 8, StopBits.One);
		Debug.Log ("start");
		try
		{
			this.serial.Open();
			Scheduler.ThreadPool.Schedule (() => ReadData ()).AddTo(this);
		} 
		catch(Exception e)
		{
			Debug.Log ("can not open serial port");
		}
	}

	public void ReadData()
	{
		while (this.isLoop) {
			string message = this.serial.ReadLine ();
			int value = int.Parse (message);
			if (value==1) {
				if (catchSign == false) {
					Debug.Log ("wait");
				} else {
					WebCamController.camFlag = true;
					catchSign = false;
				}
			}
			/*if (catchSign == false) {
				string message = this.serial.ReadLine ();
				int value = int.Parse (message);
				//arduinoから値を受け取ったら色認識をする
				if (value == 1) {
					Debug.Log ("wait");
				}
			} else {
				string message = this.serial.ReadLine ();
				int value = int.Parse (message);

				//arduinoから値を受け取ったら色認識をする
				if (value == 1) {
					WebCamController.camFlag = true;
					catchSign = false;
				}
			}*/
		}
	}
	void OnDestroy()
	{
		this.isLoop = false;
		this.serial.Close ();
	}
}