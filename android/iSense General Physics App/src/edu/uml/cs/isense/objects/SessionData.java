package edu.uml.cs.isense.objects;

import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONObject;

public class SessionData {
	public JSONObject RawJSON = null;
	public JSONArray DataJSON = null;
	public JSONArray FieldsJSON = null;
	public JSONArray MetaDataJSON = null;
	public ArrayList<ArrayList<String>> fieldData = null;	
}