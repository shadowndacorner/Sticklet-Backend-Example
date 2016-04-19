using UnityEngine;
using System.IO;
using System.Net;
using System.Text;
using System.Collections;

public class LoginInfo : MonoBehaviour {
	public bool IgnoreCheck=false;
	void Start () {
		if (!IgnoreCheck && !UserInfo.Offline)
		{
			UserInfo.Check();
			//Debug.Log (UserInfo.VerifyLogin(UserInfo.UserID, UserInfo.UKey));
		}
	}
	void OnGUI()
	{
		if (!IgnoreCheck)
		{
			switch(UserInfo.Offline)
			{
			case(true):
				GUI.contentColor = Color.red;
				GUI.Label (new Rect(Screen.width - 150, 20, 150 + UserInfo.UserName.Length * 3, 20), "Playing in offline mode");
				break;
			case(false):
				GUI.contentColor = Color.white;
				GUI.Label (new Rect(Screen.width - 150 - UserInfo.UserName.Length * 3, 20, 150 + UserInfo.UserName.Length * 3, 20), "Logged in as " + UserInfo.UserName);
				break;
			}
		}
	}

}

public static class UserInfo
{
	static string LoginPage = "http://shadowndacorner.com/sticklet/login/login.php";
	static string VerifyPage = "http://shadowndacorner.com/sticklet/login/verify_user.php";
	static string RegisterPage = "http://shadowndacorner.com/sticklet/login/register.php";

	public static void Reset()
	{
		UserName = "";
		Password = "";
		UKey = "";
		UserID = "";
		Offline=false;
	}

	public static string UserName = "";
	public static string Password = "";
	public static string UKey = "";
	public static string UserID = "";

	public static bool Offline;

	public static bool VerifyLogin(string uid, string key)
	{
		if (Offline)
			return true;
		Debug.Log ("Verifying user " + uid + ": " + key);
		using(WebClient client = new WebClient())
		{
			System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
			reqparm.Add("userid", uid);
			reqparm.Add("key", key);
			byte[] responsebytes = client.UploadValues(VerifyPage, "POST", reqparm);
			string HtmlResult = Encoding.UTF8.GetString(responsebytes);
			Debug.Log(HtmlResult);
			if (HtmlResult=="0")
			{
				return true;
			}
		}
		return false;
	}

	public static bool TryLogin(string uname, string pword)
	{
		if (Offline)
			return true;
		using(WebClient client = new WebClient())
		{
			System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
			reqparm.Add("username", uname);
			reqparm.Add("pass", pword);
			byte[] responsebytes = client.UploadValues(LoginPage, "POST", reqparm);
			string HtmlResult = Encoding.UTF8.GetString(responsebytes);
			if (HtmlResult=="false")
			{
				return false;
			}
			else
			{
				string[] s = HtmlResult.Split(new string[] { " /|\\ " }, System.StringSplitOptions.None);
				UserName = uname;
				Password = pword;
				UserID = s[0].Trim ();
				UKey = s[1].Trim ();
				return true;
			}
		}
		return false;
	}

	public static int TryRegister(string uname, string pword, string email)
	{
		using(WebClient client = new WebClient())
		{
			System.Collections.Specialized.NameValueCollection reqparm = new System.Collections.Specialized.NameValueCollection();
			reqparm.Add("username", uname);
			reqparm.Add("pass", pword);
			reqparm.Add("em", email);
			byte[] responsebytes = client.UploadValues(RegisterPage, "POST", reqparm);
			string HtmlResult = Encoding.UTF8.GetString(responsebytes);
			Debug.Log (HtmlResult);
			return System.Convert.ToInt32 (HtmlResult);
		}
		return -2;
	}
	
	public static void Check()
	{
		if (UserID == "")
		{
			Startup.Clean ();
			Network.Disconnect ();
			Multiplayer.Reset ();
			Application.LoadLevel (0);
		}
	}
}