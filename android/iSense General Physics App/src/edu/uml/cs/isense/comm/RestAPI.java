package edu.uml.cs.isense.comm;

import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import edu.uml.cs.isense.objects.Experiment;
import edu.uml.cs.isense.objects.ExperimentField;
import edu.uml.cs.isense.objects.Item;
import edu.uml.cs.isense.objects.Person;
import edu.uml.cs.isense.objects.Session;
import edu.uml.cs.isense.objects.SessionData;

import android.content.Context;
import android.database.Cursor;
import android.net.ConnectivityManager;
import android.util.Log;

/**
 * This class handles all the communications with the API provided by the website.  ALl functions are blocking and self caching.
 */

public class RestAPI {
	@SuppressWarnings("unused")
		private static final String LOG_TAG = "RestAPI";
	
	private static RestAPI instance = null;
	private String username = null;
	private static String session_key = null;
	private final String base_url = "http://isensedev.cs.uml.edu/ws/api.php";
    private final String charEncoding = "iso-8859-1";
	private ConnectivityManager connectivityManager;
	private RestAPIDbAdapter mDbHelper;
	private int uid;
	private JSONArray dataCache;
	
	protected RestAPI() {

    }
	
	protected RestAPI(ConnectivityManager cm, Context c) {
		mDbHelper = new RestAPIDbAdapter(c);
		connectivityManager = cm;
		dataCache = new JSONArray();
	}
	
	/**
	 * Get the one instance of the RestAPI class
	 * 
	 * @return RestAPI
	 */
	public static RestAPI getInstance() {
		if (instance == null) {
			instance = new RestAPI();
		}
		
		return instance;
	}
	
	/**
	 * 
	 * 
	 * @param cm  ContextManager
	 * @param c	  Context
	 * @return RestAPI
	 */
	public static RestAPI getInstance(ConnectivityManager cm, Context c) {
		if (instance == null) {
			instance = new RestAPI(cm, c);
		}
		
		return instance;
	}
	
	public String getSessionKey() {
		return session_key;
	}
	
	public int getUID() {
		return uid;
	}
	
	public boolean isLoggedIn() {
		return (session_key != null && session_key != "null" && session_key != "");
	}
	
	public String getLoggedInUsername() {
		return username;
	}
	
	public void logout() {
		session_key = null;
		username = null;
		uid = 0;
	}
	
	private static byte[] getBytesFromFile(File file) throws IOException {
	    InputStream is = new FileInputStream(file);

	    // Get the size of the file
	    long length = file.length();

	    // You cannot create an array using a long type.
	    // It needs to be an int type.
	    // Before converting to an int type, check
	    // to ensure that file is not larger than Integer.MAX_VALUE.
	    if (length > Integer.MAX_VALUE) {
	        // File is too large
	    }

	    // Create the byte array to hold the data
	    byte[] bytes = new byte[(int)length];

	    // Read in the bytes
	    int offset = 0;
	    int numRead = 0;
	    while (offset < bytes.length
	           && (numRead=is.read(bytes, offset, bytes.length-offset)) >= 0) {
	        offset += numRead;
	    }

	    // Ensure all the bytes have been read in
	    if (offset < bytes.length) {
	        throw new IOException("Could not completely read file "+file.getName());
	    }

	    // Close the input stream and return bytes
	    is.close();
	    return bytes;
	}
	
	public Boolean uploadPictureToSession(File image, String eid, int sid, String img_name, String img_desc) {
		//String target = "?method=uploadImageToSession&session_key=" + session_key + "&sid=" + sid + "&img_name=" + img_name + "&img_desc=" + img_desc;
		
		try {
			byte[] data = getBytesFromFile(image);
			
			String lineEnd = "\r\n";
			String twoHyphens = "--";
			String boundary = "*****";
			
			URL connectURL = new URL(this.base_url);
			HttpURLConnection conn = (HttpURLConnection) connectURL.openConnection();
			conn.setDoInput(true);
			conn.setDoOutput(true);
			conn.setUseCaches(false);
			conn.setRequestMethod("POST");
	
			conn.setRequestProperty("Connection", "Keep-Alive");
			conn.setRequestProperty("Content-Type", "multipart/form-data, boundary=" + boundary);
	
			DataOutputStream dos = new DataOutputStream(conn.getOutputStream());
	
			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"method\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes("uploadImageToSession");
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"session_key\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(session_key);
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"eid\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(eid + "");
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();
			
			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"sid\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(sid + "");
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"img_name\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(img_name.replace(" ", "+"));
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"img_description\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(img_desc.replace(" ", "+"));
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			dos.writeBytes(twoHyphens + boundary + lineEnd);
			// write content header
			dos.writeBytes("Content-Disposition: form-data; name=\"image\"; filename=\"" + image.getName() + "\"");
			dos.writeBytes(lineEnd);
			dos.writeBytes("Content-Type: image/jpeg" + lineEnd);
			dos.writeBytes(lineEnd);
	
			// create a buffer of maximum size
	
			dos.write(data, 0, data.length);
	
			// send multipart form data necesssary after file data...
	
			dos.writeBytes(lineEnd);
			dos.writeBytes(twoHyphens + boundary + twoHyphens + lineEnd);
	
			// close streams
			dos.flush();
			dos.close();
		
			try {
				DataInputStream inStream = new DataInputStream(conn.getInputStream());
				String str;

				while ((str = inStream.readLine()) != null) {
					Log.d("rapi", "Server Response" + str);
				}
				inStream.close();
				return true;
			} catch (IOException ioex) {
				Log.e("rapi", "error: " + ioex.getMessage(), ioex);
				return false;
			}
			
		} catch (Exception e) {
			return false;
		}
		
	}
	
	public Boolean uploadPicture(File image, String eid, String img_name, String img_desc) {
		//String target = "?method=uploadImageToExperiment&session_key=" + session_key + "&eid=" + eid + "&img_name=" + img_name + "&img_desc=" + img_desc;
		
		try {
			byte[] data = getBytesFromFile(image);
			
			String lineEnd = "\r\n";
			String twoHyphens = "--";
			String boundary = "*****";
			
			URL connectURL = new URL(this.base_url);
			HttpURLConnection conn = (HttpURLConnection) connectURL.openConnection();
			conn.setDoInput(true);
			conn.setDoOutput(true);
			conn.setUseCaches(false);
			conn.setRequestMethod("POST");
	
			conn.setRequestProperty("Connection", "Keep-Alive");
			conn.setRequestProperty("Content-Type", "multipart/form-data, boundary=" + boundary);
	
			DataOutputStream dos = new DataOutputStream(conn.getOutputStream());
	
			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"method\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes("uploadImageToExperiment");
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"session_key\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(session_key);
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"eid\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(eid + "");
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"img_name\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(img_name.replace(" ", "+"));
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			// submit header
			dos.writeBytes(twoHyphens + boundary + lineEnd);
			dos.writeBytes("Content-Disposition: form-data; name=\"img_description\"" + lineEnd);
			dos.writeBytes(lineEnd);
			// insert submit
			dos.writeBytes(img_desc.replace(" ", "+"));
			// submit closer
			dos.writeBytes(lineEnd);
			dos.flush();

			dos.writeBytes(twoHyphens + boundary + lineEnd);
			// write content header
			dos.writeBytes("Content-Disposition: form-data; name=\"image\"; filename=\"" + image.getName() + "\"");
			dos.writeBytes(lineEnd);
			dos.writeBytes("Content-Type: image/jpeg" + lineEnd);
			dos.writeBytes(lineEnd);
	
			// create a buffer of maximum size
	
			dos.write(data, 0, data.length);
	
			// send multipart form data necesssary after file data...
	
			dos.writeBytes(lineEnd);
			dos.writeBytes(twoHyphens + boundary + twoHyphens + lineEnd);
	
			// close streams
			dos.flush();
			dos.close();
		
			try {
				DataInputStream inStream = new DataInputStream(conn.getInputStream());
				String str;

				while ((str = inStream.readLine()) != null) {
					Log.d("rapi", "Server Response" + str);
				}
				inStream.close();
				return true;
			} catch (IOException ioex) {
				Log.e("rapi", "error: " + ioex.getMessage(), ioex);
				return false;
			}
			
		} catch (Exception e) {
			return false;
		}
		
	}
	
	public Boolean login(String username, String password) {
		String url = "method=login&username=" + URLEncoder.encode(username) + "&password=" + URLEncoder.encode(password);
		
/*		if(!(connectivityManager != null)) Log.e(null, "FAIL 1: No connectivity manager");
		if(!(connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected())) Log.e(null, "FAIL 2: No wifi");
		if(!(connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected())) Log.e(null, "FAIL 3: No 3G");
	*/	
		if (connectivityManager != null && ( connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected())) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				session_key = o.getJSONObject("data").getString("session");
				uid = o.getJSONObject("data").getInt("uid");
				
				if (isLoggedIn()) {
					this.username = username;
					return true;
				}
				
			} catch (MalformedURLException e) {
				e.printStackTrace();
				return false;
			} catch (IOException e) {
				e.printStackTrace();
				return false;
			} catch (Exception e) {
				e.printStackTrace();
				return false;
			}
			
			return true;
		}
		return false;
	}
	
	public Experiment getExperiment(int id) {
		String url = "method=getExperiment&experiment=" + id;
		Experiment e = new Experiment();
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);	
				JSONObject obj = o.getJSONObject("data");
									
				e.experiment_id = obj.getInt("experiment_id");
				e.owner_id = obj.getInt("owner_id");
				e.name = obj.getString("name");
				e.description = obj.getString("description");
				e.timecreated = obj.getString("timecreated");
				e.timemodified = obj.getString("timemodified");
				e.default_read = obj.getInt("default_read");
				e.default_join = obj.getInt("default_join");
				e.featured = obj.getInt("featured");
				e.rating = obj.getInt("rating");
				e.rating_votes = obj.getInt("rating_votes");
				e.hidden = obj.getInt("hidden");
				e.firstname = obj.getString("firstname");
				e.lastname = obj.getString("lastname");
						
				mDbHelper.open();
				mDbHelper.deleteExperiment(e);
				mDbHelper.insertExperiment(e);
				mDbHelper.close();
				
			} catch (MalformedURLException ee) {
				ee.printStackTrace();
				return null;
			} catch (IOException ee) {
				ee.printStackTrace();
				return null;
			} catch (Exception ee) {
				ee.printStackTrace();
				return null;
			}
		} else {
			mDbHelper.open();
			Cursor c = mDbHelper.getExperiment(id);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return e;
			
			e.experiment_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_EXPERIMENT_ID));
			e.owner_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_OWNER_ID));
			e.name = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_NAME));
			e.description = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_DESCRIPTION));
			e.timecreated = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TIMECREATED));
			e.timemodified = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TIMEMODIFIED));
			e.default_read = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_DEFAULT_READ));
			e.default_join = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_DEFAULT_JOIN));
			e.featured = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_FEATURED));
			e.rating = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_RATING));
			e.rating_votes = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_RATING_VOTES));
			e.hidden = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_HIDDEN));
			e.firstname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_FIRSTNAME));
			e.lastname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_LASTNAME));
			e.provider_url = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_PROVIDER_URL));
		}
		
		return e;
		
	}
	
	public ArrayList<SessionData> sessiondata(String sessions) {
		String url = "method=sessiondata&sessions=" + sessions;
		String dataString;
		ArrayList<SessionData> ses = new ArrayList<SessionData>();
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				dataString = makeRequest(url);
				
				JSONObject o = new JSONObject(dataString);
				JSONArray data = o.getJSONArray("data");
				
				int length = data.length();
				
				for (int i = 0; i < length; i++) {
					SessionData temp = new SessionData();
					JSONObject current = data.getJSONObject(i);
					temp.RawJSON = current;
					temp.DataJSON = current.getJSONArray("data");
					temp.MetaDataJSON = current.getJSONArray("meta");
					temp.FieldsJSON = current.getJSONArray("fields");
					
					int fieldCount = temp.FieldsJSON.length();
					
					temp.fieldData = new ArrayList<ArrayList<String>>();
					
					for (int j = 0; j < fieldCount; j++) {
						ArrayList<String> tempList = new ArrayList<String>();
						int dataLength = temp.DataJSON.length();
						for (int z = 0; z < dataLength; z++) {
							tempList.add(temp.DataJSON.getJSONArray(z).getString(j));
						}
						temp.fieldData.add(tempList);
					}
					
					ses.add(temp);
				}
				
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		return ses;
	}
	
	public ArrayList<Person> getPeople(int page, int limit, String action, String query) {
		String url = "method=getPeople&page=" + page + "&count=" + limit + "&action=" + action + "&query=" + query;
		ArrayList<Person> pList = new ArrayList<Person>();
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONArray a = o.getJSONArray("data");
							
				int length = a.length();
				
				if (action.toLowerCase().compareTo("browse") == 0) mDbHelper.open();

				for (int i = 0; i < length; i++) {
					try {
						JSONObject obj = a.getJSONObject(i);
						Person p = new Person();
						
						p.user_id = obj.getInt("user_id");
						p.firstname = obj.getString("firstname");
						p.lastname = obj.getString("lastname");
						p.confirmed = obj.getInt("confirmed");
						p.email = obj.getString("email");
						p.icq = obj.getString("icq");
						p.skype = obj.getString("skype");
						p.yahoo = obj.getString("yahoo");
						p.aim = obj.getString("aim");
						p.msn = obj.getString("msn");
						p.institution = obj.getString("institution");
						p.department = obj.getString("department");
						p.street = obj.getString("street");
						p.city = obj.getString("city");
						p.country = obj.getString("country");
						p.longitude = obj.getDouble("longitude");
						p.latititude = obj.getDouble("latitude");
						p.langauge = obj.getString("language");
						p.firstaccess = obj.getString("firstaccess");
						p.lastaccess = obj.getString("lastaccess");
						p.lastlogin = obj.getString("lastlogin");
						p.picture = obj.getString("picture");
						p.url = obj.getString("url");
						//	p.timeobj = obj.getString("timeobj");
						//	p.date_diff = obj.getString("date_diff");
						p.experiment_count = obj.getInt("experiment_count");
						p.session_count = obj.getInt("session_count");
		
					    pList.add(p);
					    if (action.toLowerCase().compareTo("browse") == 0) {
							mDbHelper.deletePerson(p);
							mDbHelper.insertPerson(p);
						}
					} catch (JSONException e) {
						e.printStackTrace();

						continue;
					}
				}
				if (action.toLowerCase().compareTo("browse") == 0) mDbHelper.close();			
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		} else if (action.toLowerCase().compareTo("browse") == 0) {
			mDbHelper.open();
			Cursor c = mDbHelper.getPeople(page, limit);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return pList;

			while(!c.isAfterLast()) {
				
				Person p = new Person();
				
				p.user_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_USER_ID));
				p.firstname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_FIRSTNAME));
				p.lastname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_LASTNAME));
				p.confirmed = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_CONFIRMED));
				p.email = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_EMAIL));
				p.icq = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_ICQ));
				p.skype = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_SKYPE));
				p.yahoo = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_YAHOO));
				p.aim = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_AIM));
				p.msn = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_MSN));
				p.institution = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_INSTITUTION));
				p.department = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_DEPARTMENT));
				p.street = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_STREET));
				p.city = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_CITY));
				p.country = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_COUNTRY));
				p.longitude = c.getDouble(c.getColumnIndex(RestAPIDbAdapter.KEY_LONGITUDE));
				p.latititude = c.getDouble(c.getColumnIndex(RestAPIDbAdapter.KEY_LATITUDE));
				p.langauge = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_LANGUAGE));
				p.firstaccess =c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_FIRSTACCESS));
				p.lastaccess = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_LASTACCESS));
				p.lastlogin = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_LASTLOGIN));
				p.picture = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_PICTURE));
				p.url = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_URL));
	//			p.timeobj = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TIMEOBJ));
	//			p.date_diff = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_DATE_DIFF));
				p.experiment_count = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_EXPERIMENT_COUNT));
				p.session_count = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_SESSION_COUNT));
			
				pList.add(p);
				
				if (c.isLast()) break;
				c.moveToNext();
			}
		}	

		return pList;
	}
	
	public Item getProfile(int user_id) {
		String url = "method=getUserProfile&user=" + user_id;
		Item i = new Item();
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONObject a = o.getJSONObject("data");
				JSONArray experiments;
				try {
					experiments = a.getJSONArray("experiments");
				} catch (JSONException e) {
					experiments = new JSONArray();
				}
				
				JSONArray sessions;
				try {
					sessions = a.getJSONArray("sessions");
				} catch (JSONException e) {
					sessions = new JSONArray();
				}
				//JSONArray images = a.getJSONArray("images");
				
				int length = experiments.length();
				
				for (int j = 0; j < length; j++) {
					JSONObject obj = experiments.getJSONObject(j);
					Experiment e = new Experiment();
									
					e.experiment_id = obj.getInt("experiment_id");
				    e.owner_id = obj.getInt("owner_id");
				    e.name = obj.getString("name");
				    e.description = obj.getString("description");
				    e.timecreated = obj.getString("timecreated");
				    e.timemodified = obj.getString("timemodified");
				    e.hidden = obj.getInt("hidden");
				    //e.session_count = obj.getInt("session_count");
				    
				    i.e.add(e);
				}
				
				length = sessions.length();
				
				for (int j = 0; j < length; j++) {
					JSONObject obj = sessions.getJSONObject(j);
					Session s = new Session();
						
					s.session_id = obj.getInt("session_id");
					s.experiment_id = obj.getInt("experiment_id");
					s.name = obj.getString("name");
					s.description = obj.getString("description");
					s.latitude = obj.getLong("latitude");
					s.longitude = obj.getLong("longitude");
					s.timecreated = obj.getString("timeobj");
					s.timemodified = obj.getString("timemodified");
					
				    i.s.add(s);
				}
				
							
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		return i;
	}
	
	public ArrayList<Experiment> getExperiments(int page, int limit, String action, String query) {
		String url = "method=getExperiments&page=" + page + "&limit=" + limit + "&action=" + action + "&query=" + query;
		
		ArrayList<Experiment> expList = new ArrayList<Experiment>();
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
			
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONArray a = o.getJSONArray("data");
						
				int length = a.length();
				if (action.toLowerCase().compareTo("browse") == 0) mDbHelper.open();
				for (int i = 0; i < length; i++) {
					try {
						JSONObject current = a.getJSONObject(i);
						JSONObject obj = current.getJSONObject("meta");
						Experiment e = new Experiment();
								
						e.experiment_id = obj.getInt("experiment_id");
						e.owner_id = obj.getInt("owner_id");
						e.name = obj.getString("name");
						e.description = obj.getString("description");
						e.timecreated = obj.getString("timecreated");
						e.timemodified = obj.getString("timemodified");
						e.default_read = obj.getInt("default_read");
						e.default_join = obj.getInt("default_join");
						e.featured = obj.getInt("featured");
						e.rating = obj.getInt("rating");
						e.rating_votes = obj.getInt("rating_votes");
						e.hidden = obj.getInt("hidden");
						e.firstname = obj.getString("owner_firstname");
						e.lastname = obj.getString("owner_lastname");
						e.provider_url = obj.getString("provider_url");
				
						expList.add(e);
						if (action.toLowerCase().compareTo("browse") == 0) {
							mDbHelper.deleteExperiment(e);
							mDbHelper.insertExperiment(e);
						}
					} catch (JSONException e) {
						e.printStackTrace();

						continue;
					}
				}
				if (action.toLowerCase().compareTo("browse") == 0) mDbHelper.close();
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}	
		} else if (action.toLowerCase().compareTo("browse") == 0){
			mDbHelper.open();
			Cursor c = mDbHelper.getExperiments(page, limit);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return expList;

			while(!c.isAfterLast()) {
				
				Experiment e = new Experiment();
				
				e.experiment_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_EXPERIMENT_ID));
				e.owner_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_OWNER_ID));
				e.name = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_NAME));
				e.description = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_DESCRIPTION));
				e.timecreated = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TIMECREATED));
				e.timemodified = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TIMEMODIFIED));
				e.default_read = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_DEFAULT_READ));
				e.default_join = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_DEFAULT_JOIN));
				e.featured = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_FEATURED));
				e.rating = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_RATING));
				e.rating_votes = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_RATING_VOTES));
				e.hidden = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_HIDDEN));
				e.firstname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_FIRSTNAME));
				e.lastname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_LASTNAME));
				e.provider_url = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_PROVIDER_URL));
			
				expList.add(e);
				
				if (c.isLast()) break;
				c.moveToNext();
			}
		}
		return expList;
	}
	
	public ArrayList<String> getExperimentImages(int id) {
		ArrayList<String> imgList = new ArrayList<String>();
		String url = "method=getExperimentImages&experiment=" + id;
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONArray a = o.getJSONArray("data");
							
				int length = a.length();
				mDbHelper.open();
				for (int i = 0; i < length; i++) {
					JSONObject obj = a.getJSONObject(i);
					
					imgList.add(obj.getString("provider_url"));
				}
				
				mDbHelper.deleteExperimentImages(id);
				mDbHelper.insertExperimentImages(id, imgList);
				mDbHelper.close();
							
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		} else {
			mDbHelper.open();
			Cursor c = mDbHelper.getExperimentImages(id);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return imgList;
			
			String images = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_PROVIDER_URL));
			
			String img[] = images.split(",");
			
			for(int i = 0; i < img.length; i++) {
				imgList.add(img[i]);
			}
			
		}
		
		return imgList;
	}
	
	public ArrayList<String> getExperimentVideos(int id) {
		ArrayList<String> vidList = new ArrayList<String>();
		String url = "method=getExperimentVideos&experiment=" + id;
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONArray a = o.getJSONArray("data");
							
				int length = a.length();
				mDbHelper.open();
				for (int i = 0; i < length; i++) {
					JSONObject obj = a.getJSONObject(i);
					
					vidList.add(obj.getString("provider_url"));
				}
				mDbHelper.deleteExperimentVideos(id);
				mDbHelper.insertExperimentVideos(id, vidList);
				mDbHelper.close();
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		} else {
			mDbHelper.open();
			Cursor c = mDbHelper.getExperimentVideos(id);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return vidList;
			
			String videos = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_PROVIDER_URL));
			
			String vid[] = videos.split(",");
			
			for(int i = 0; i < vid.length; i++) {
				vidList.add(vid[i]);
			}
		}
		return vidList;
	}
	
	public String getExperimentTags(int id) {
		String tags = "";
		String url = "method=getExperimentTags&experiment=" + id;
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONArray a = o.getJSONArray("data");
							
				int length = a.length();
				
				mDbHelper.open();
				for (int i = 0; i < length; i++) {
					JSONObject obj = a.getJSONObject(i);
					
					tags += obj.getString("tag") + ", ";
				}
				
				tags = tags.substring(0, tags.lastIndexOf(","));
				mDbHelper.deleteExperimentTags(id);
				mDbHelper.insertExperimentTags(id, tags);
				mDbHelper.close();
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		} else {
			mDbHelper.open();
			Cursor c = mDbHelper.getExperimentTags(id);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return tags;
			
			tags = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TAGS));
		}
		
		return tags;
	}
	
	public ArrayList<ExperimentField> getExperimentFields(int id) {
		String url = "method=getExperimentFields&experiment=" + id;
		ArrayList<ExperimentField> fields = new ArrayList<ExperimentField>();
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONArray a = o.getJSONArray("data");
							
				int length = a.length();
				
				mDbHelper.open();
				for (int i = 0; i < length; i++) {
					JSONObject obj = a.getJSONObject(i);
					ExperimentField f = new ExperimentField();
					
					f.field_id = obj.getInt("field_id");
					f.field_name = obj.getString("field_name");
					f.type_id = obj.getInt("type_id");
					f.type_name = obj.getString("type_name");
					f.unit_abbreviation = obj.getString("unit_abbreviation");
					f.unit_id = obj.getInt("unit_id");
					f.unit_name = obj.getString("unit_name");
					
					fields.add(f);
				}
				mDbHelper.deleteExperimentFields(id);
				mDbHelper.insertExperimentFields(id, fields);
				mDbHelper.close();
	
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		} else {
			mDbHelper.open();
			Cursor c = mDbHelper.getExperimentFields(id);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return fields;
			
			while (!c.isAfterLast()) {
				ExperimentField f = new ExperimentField();
				
				f.field_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_FIELD_ID));
				f.field_name = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_FIELD_NAME));
				f.type_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_TYPE_ID));
				f.type_name = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TYPE_ID));
				f.unit_abbreviation = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_UNIT_ABBREVIATION));
				f.unit_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_UNIT_ID));
				f.unit_name = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_UNIT_NAME));
				
				fields.add(f);
				
				c.moveToNext();
			}
		}
		
		return fields;
	}
	
	public ArrayList<Session> getSessions(int id) {
		ArrayList<Session> sesList = new ArrayList<Session>();
		String url = "method=getSessions&experiment=" + id;
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONArray a = o.getJSONArray("data");
							
				int length = a.length();
				
				mDbHelper.open();
				for (int i = 0; i < length; i++) {
					JSONObject obj = a.getJSONObject(i);
					Session s = new Session();
						
					s.session_id = obj.getInt("session_id");
					s.owner_id = obj.getInt("owner_id");
					s.experiment_id = id;
					s.name = obj.getString("name");
					s.description = obj.getString("description");
					s.street = obj.getString("street");
					s.city = obj.getString("city");
					s.country = obj.getString("country");
					s.latitude = obj.getDouble("latitude");
					s.longitude = obj.getDouble("longitude");
					s.timecreated = obj.getString("timecreated");
					s.timemodified = obj.getString("timemodified");
					s.debug_data = obj.getString("debug_data");
					s.firstname = obj.getString("firstname");
					s.lastname = obj.getString("lastname");
					
				    sesList.add(s);
				    mDbHelper.deleteSession(s);
					mDbHelper.insertSession(s);
				}
				mDbHelper.close();
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		} else {
			mDbHelper.open();
			Cursor c = mDbHelper.getSessions(id);
			mDbHelper.close();
			
			if (c == null || c.getCount() == 0) return sesList;

			while(!c.isAfterLast()) {
				
				Session s = new Session();
				
				s.session_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_SESSION_ID));
				s.owner_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_OWNER_ID));
				s.experiment_id = c.getInt(c.getColumnIndex(RestAPIDbAdapter.KEY_EXPERIMENT_ID));
				s.name = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_NAME));
				s.description = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_DESCRIPTION));
				s.street = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_STREET));
				s.city = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_CITY));
				s.country = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_COUNTRY));
				s.latitude = c.getDouble(c.getColumnIndex(RestAPIDbAdapter.KEY_LATITUDE));
				s.longitude = c.getDouble(c.getColumnIndex(RestAPIDbAdapter.KEY_LONGITUDE));
				s.timecreated = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TIMECREATED));
				s.timemodified = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_TIMEMODIFIED));
				s.debug_data = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_DEBUG_DATA));
				s.firstname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_FIRSTNAME));
				s.lastname = c.getString(c.getColumnIndex(RestAPIDbAdapter.KEY_LASTNAME));
				
			    sesList.add(s);
				
				if (c.isLast()) break;
				c.moveToNext();
			}
		}
		
		
		return sesList;
	}
	
	public int createSession(String eid, String name, String description, String street, String city, String country)  {
		int sid = -1;
		String url = "method=createSession&session_key=" + session_key + "&eid=" + eid + "&name=" + name + "&description=" + description + "&street=" + street + "&city=" + city + "&country=" + country;
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				// Parse JSON Result
				JSONObject o = new JSONObject(data);
				JSONObject obj = o.getJSONObject("data");
													
				sid = obj.getInt("sessionId");	
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		} 
		
		return sid;
	}
	
	public boolean putSessionData(int sid, String eid, JSONArray dataJSON) {
		String url = "method=putSessionData&session_key=" + session_key + "&sid=" + sid + "&eid=" + eid + "&data=" + dataJSON.toString();
		boolean ret = false;
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				if (data.compareTo("{}") != 0) ret = true;
				
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		
		return ret;
	}
	
	/** Method has been updated from original for the purpose of this app only: 
	 *  in String url = ..., changed dataJSON.tostring(); from dataCache.toString(); */
	public boolean updateSessionData(int sid, String eid, JSONArray dataJSON) {
		dataCache.put(dataJSON);
		String url = "method=updateSessionData&session_key=" + session_key + "&sid=" + sid + "&eid=" + eid + "&data=" + dataJSON.toString();
		boolean ret = false;
		
		if (connectivityManager != null && connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnected() || connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnected()) {
			try {
				String data = makeRequest(url);
				
				if (data.compareTo("{}") != 0) {
					dataCache = new JSONArray();
					ret = true;
				}
				
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		
		return ret;
	}
	
	public String getMyIp(){
        String url = "method=whatsMyIp";
        try {
            String data = makeRequest(url);
            JSONObject o = new JSONObject(data);
            JSONObject obj = o.getJSONObject("data");
            return obj.getString("msg");
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
	
	public String makeRequest(String target) throws Exception {
		
		String output = "{}";
		
		String data = target.replace(" ", "+");
		
		HttpURLConnection conn = (HttpURLConnection) new URL(this.base_url).openConnection();
	    conn.setDoOutput(true);
	    conn.setRequestMethod("POST");
	    conn.setRequestProperty("Content-Length", Integer.toString(data.length()));
	    conn.getOutputStream().write(data.getBytes(charEncoding));
	    conn.connect();
	    conn.getResponseCode();
		
		// Get the status code of the HTTP Request so we can figure out what to do next
		int status = conn.getResponseCode();
		
		switch(status) {
								
			case 200:
				Log.d("rapi", "Successful request");
			
				// Build Reader and StringBuilder to output to string
				BufferedReader br = new BufferedReader(new InputStreamReader(conn.getInputStream()));
				StringBuilder sb = new StringBuilder();
				String line;
			
				// Loop through response to build JSON String
				while((line = br.readLine()) != null) {
					sb.append(line + "\n");
				}
			
				// Set output from response
				output = sb.toString();				
				break;
			
			case 404:
				// Handle 404 page not found
				Log.d("rapi", "Could not find URL!");
				break;
			
			default:
				// Catch all for all other HTTP response codes
				Log.d("rapi", "Returned unhandled error code: " + status);
				break;
		}
		
		return output;
	}
	
}

