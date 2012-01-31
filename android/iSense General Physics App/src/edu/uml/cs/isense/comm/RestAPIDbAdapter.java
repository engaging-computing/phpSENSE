package edu.uml.cs.isense.comm;

import java.util.ArrayList;

import edu.uml.cs.isense.objects.Experiment;
import edu.uml.cs.isense.objects.ExperimentField;
import edu.uml.cs.isense.objects.Person;
import edu.uml.cs.isense.objects.Session;
import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

public class RestAPIDbAdapter {
	public static final String KEY_ROWID = "_id";
	public static final String KEY_EXPERIMENT_ID = "experiment_id";
	public static final String KEY_OWNER_ID = "owner_id";
	public static final String KEY_NAME = "name";
	public static final String KEY_DESCRIPTION = "description";
	public static final String KEY_TIMECREATED = "timecreated";
	public static final String KEY_TIMEMODIFIED = "timemodified";
	public static final String KEY_DEFAULT_READ = "default_read";
	public static final String KEY_DEFAULT_JOIN = "default_join";
	public static final String KEY_FEATURED = "featured";
	public static final String KEY_RATING = "rating";
	public static final String KEY_RATING_VOTES = "votes";
	public static final String KEY_HIDDEN = "hidden";
	public static final String KEY_FIRSTNAME = "firstname";
	public static final String KEY_LASTNAME = "lastname";
	public static final String KEY_PROVIDER_URL = "provider_url";
	public static final String KEY_FIELD_ID = "field_id";
	public static final String KEY_FIELD_NAME = "field_name";
	public static final String KEY_TYPE_ID = "type_id";
	public static final String KEY_TYPE_NAME = "type_name";
	public static final String KEY_UNIT_ABBREVIATION = "unit_abbreviation";
	public static final String KEY_UNIT_ID = "unit_id";
	public static final String KEY_UNIT_NAME = "unit_name";
	public static final String KEY_TAGS = "tags";
	public static final String KEY_SESSION_ID = "session_id";
	public static final String KEY_STREET = "stree";
	public static final String KEY_CITY = "city";
	public static final String KEY_COUNTRY = "country";
	public static final String KEY_LATITUDE = "latitude";
	public static final String KEY_LONGITUDE = "longitude";
	public static final String KEY_DEBUG_DATA = "debug_data";	
	public static final String KEY_USER_ID = "user_id";
	public static final String KEY_CONFIRMED = "confirmed";
	public static final String KEY_EMAIL = "email";
	public static final String KEY_ICQ = "icq";
	public static final String KEY_SKYPE = "skype";
	public static final String KEY_YAHOO = "yahoo";
	public static final String KEY_AIM = "aim";
	public static final String KEY_MSN = "msn";
	public static final String KEY_INSTITUTION = "institution";
	public static final String KEY_DEPARTMENT = "department";
	public static final String KEY_LANGUAGE = "language";
	public static final String KEY_FIRSTACCESS = "firstaccess";
	public static final String KEY_LASTACCESS = "lastaccess";
	public static final String KEY_LASTLOGIN = "lastlogin";
	public static final String KEY_PICTURE = "picture";
	public static final String KEY_URL = "url";
	public static final String KEY_TIMEOBJ = "timeobj";
	public static final String KEY_DATE_DIFF = "date_diff";
	public static final String KEY_EXPERIMENT_COUNT = "experiment_count";
	public static final String KEY_SESSION_COUNT = "session_count";
	
    private static final String TAG = "DataDbAdapter";
    private DatabaseHelper mDbHelper;
    private SQLiteDatabase mDb;
    
    private static final String DATABASE_NAME = "RestAPICache";
    private static final String DATABASE_TABLE_EXPERIMENTS = "experiments";
    private static final String DATABASE_TABLE_EXPERIMENT_IMAGES = "experimentImages";
    private static final String DATABASE_TABLE_EXPERIMENT_VIDEOS = "experimentVideos";
    private static final String DATABASE_TABLE_EXPERIMENT_TAGS = "experimentTags";
    private static final String DATABASE_TABLE_EXPERIMENT_FIELDS = "experimentFields";
    private static final String DATABASE_TABLE_SESSIONS = "sessions";
    private static final String DATABASE_TABLE_PEOPLE = "people";

    private static final int DATABASE_VERSION = 5;
    
    /**
     * Database creation sql statement
     */
    private static final String DATABASE_CREATE_PEOPLE =
    		"CREATE TABLE IF NOT EXISTS " + DATABASE_TABLE_PEOPLE + " ("
    		+ KEY_ROWID + " integer primary key autoincrement, "
    		+ KEY_USER_ID + " integer, "
    		+ KEY_FIRSTNAME + " text not null, "
    		+ KEY_LASTNAME + " text not null, "
    		+ KEY_CONFIRMED + " integer, "
    		+ KEY_EMAIL + " text not null, "
    		+ KEY_ICQ + " text not null, "
    		+ KEY_SKYPE + " text not null, " 
    		+ KEY_YAHOO + " text not null, "
    		+ KEY_AIM + " text not null, "
    		+ KEY_MSN + " text not null, "
    		+ KEY_INSTITUTION + " text not null, "
    		+ KEY_DEPARTMENT + " text not null, "
    		+ KEY_STREET + " text not null, "
    		+ KEY_CITY + " text not null, "
    		+ KEY_COUNTRY + " text not null, "
    		+ KEY_LONGITUDE + " real, "
    		+ KEY_LATITUDE + " real, "
    		+ KEY_LANGUAGE + " text not null, "
    		+ KEY_FIRSTACCESS + " text not null, "
    		+ KEY_LASTACCESS + " text not null, "
    		+ KEY_LASTLOGIN + " text not null, "
    		+ KEY_PICTURE + " text not null, "
    		+ KEY_URL + " text not null, "
    		+ KEY_TIMEOBJ + " text not null, "
    		+ KEY_DATE_DIFF + " text not null, "
    		+ KEY_EXPERIMENT_COUNT + " integer, "
    		+ KEY_SESSION_COUNT + " integer);";
    
    private static final String DATABASE_CREATE_SESSIONS =
    		"CREATE TABLE IF NOT EXISTS " + DATABASE_TABLE_SESSIONS + " ("
    		+ KEY_ROWID + " integer primary key autoincrement, "
    		+ KEY_EXPERIMENT_ID + " integer, "
    		+ KEY_SESSION_ID + " integer, "
    		+ KEY_OWNER_ID + " integer, "
    		+ KEY_NAME + " text not null, "
    		+ KEY_DESCRIPTION + " text not null, "
    		+ KEY_STREET + " text not null, "
    		+ KEY_CITY + " text not null, "
    		+ KEY_COUNTRY + " text not null, "
    		+ KEY_LATITUDE + " real, "
    		+ KEY_LONGITUDE + " real, "
    		+ KEY_TIMECREATED + " text not null, "
    		+ KEY_TIMEMODIFIED + " text not null, " 
    		+ KEY_DEBUG_DATA + " text not null, " 
    		+ KEY_FIRSTNAME + " text not null, "
    		+ KEY_LASTNAME + " text not null);";
    
    
    private static final String DATABASE_CREATE_EXPERIMENTS =
            "CREATE TABLE IF NOT EXISTS " + DATABASE_TABLE_EXPERIMENTS + " (" 
            + KEY_ROWID + " integer primary key autoincrement, "
    		+ KEY_EXPERIMENT_ID + " integer, "
    		+ KEY_OWNER_ID + " integer, "
    		+ KEY_NAME + " text not null, "
    		+ KEY_DESCRIPTION + " text not null, "
    		+ KEY_TIMECREATED + " text not null, "
    		+ KEY_TIMEMODIFIED + " text not null, " 
    		+ KEY_DEFAULT_READ + " integer, "
    		+ KEY_DEFAULT_JOIN + " integer, "
    		+ KEY_FEATURED + " integer, "
    		+ KEY_RATING + " integer, "
    		+ KEY_RATING_VOTES + " integer, "
    		+ KEY_HIDDEN + " integer, "
    		+ KEY_FIRSTNAME + " text not null, "
    		+ KEY_LASTNAME + " text not null, "
    		+ KEY_PROVIDER_URL + " text not null);";
    
    private static final String DATABASE_CREATE_EXPERIMENT_IMAGES =
    		"CREATE TABLE IF NOT EXISTS " + DATABASE_TABLE_EXPERIMENT_IMAGES + " ("
    		+ KEY_ROWID + " integer primary key autoincrement, "
    		+ KEY_EXPERIMENT_ID + " integer, "
    		+ KEY_PROVIDER_URL + " text not null);";
    
    private static final String DATABASE_CREATE_EXPERIMENT_VIDEOS =
    		"CREATE TABLE IF NOT EXISTS " + DATABASE_TABLE_EXPERIMENT_VIDEOS + " ("
    		+ KEY_ROWID + " integer primary key autoincrement, "
    		+ KEY_EXPERIMENT_ID + " integer, "
    		+ KEY_PROVIDER_URL + " text not null);";
    		
    private static final String DATABASE_CREATE_EXPERIMENT_TAGS = 
    		"CREATE TABLE IF NOT EXISTS " + DATABASE_TABLE_EXPERIMENT_TAGS + " ("
    		+ KEY_ROWID + " integer primary key autoincrement, "
    		+ KEY_EXPERIMENT_ID + " integer, "
    		+ KEY_TAGS + " text not null);";
    
    private static final String DATABASE_CREATE_EXPERIMENT_FIELDS =
    		"CREATE TABLE IF NOT EXISTS " + DATABASE_TABLE_EXPERIMENT_FIELDS + " ("
    		+ KEY_ROWID + " integer primary key autoincrement, "
    		+ KEY_EXPERIMENT_ID + " integer, "
    		+ KEY_FIELD_ID + " integer, "
    		+ KEY_FIELD_NAME + " text not null, "
    		+ KEY_TYPE_ID + " integer, "
    		+ KEY_TYPE_NAME + " text not null, "
    		+ KEY_UNIT_ABBREVIATION + " text not null, "
    		+ KEY_UNIT_ID + " integer, "
    		+ KEY_UNIT_NAME + " text not null);";

    private final Context mCtx;

    private static class DatabaseHelper extends SQLiteOpenHelper {        

    	DatabaseHelper(Context context) {
            super(context, DATABASE_NAME, null, DATABASE_VERSION);
        }

        @Override
        public void onCreate(SQLiteDatabase db) {
            db.execSQL(DATABASE_CREATE_EXPERIMENTS);
            db.execSQL(DATABASE_CREATE_EXPERIMENT_IMAGES);
            db.execSQL(DATABASE_CREATE_EXPERIMENT_VIDEOS);
            db.execSQL(DATABASE_CREATE_EXPERIMENT_TAGS);
            db.execSQL(DATABASE_CREATE_EXPERIMENT_FIELDS);
            db.execSQL(DATABASE_CREATE_SESSIONS);
            db.execSQL(DATABASE_CREATE_PEOPLE);
        }

        @Override
        public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
            Log.w(TAG, "Upgrading database from version " + oldVersion + " to "
                    + newVersion + ", which will destroy all old data");
            db.execSQL("DROP TABLE IF EXISTS " + DATABASE_TABLE_EXPERIMENTS);
            db.execSQL("DROP TABLE IF EXISTS " + DATABASE_TABLE_EXPERIMENT_IMAGES);
            db.execSQL("DROP TABLE IF EXISTS " + DATABASE_TABLE_EXPERIMENT_VIDEOS);
            db.execSQL("DROP TABLE IF EXISTS " + DATABASE_TABLE_EXPERIMENT_FIELDS);
            db.execSQL("DROP TABLE IF EXISTS " + DATABASE_TABLE_EXPERIMENT_TAGS);
            db.execSQL("DROP TABLE IF EXISTS " + DATABASE_TABLE_SESSIONS);
            db.execSQL("DROP TABLE IF EXISTS " + DATABASE_TABLE_PEOPLE);


            onCreate(db);
        }
    }
    
    RestAPIDbAdapter(Context ctx) {
        this.mCtx = ctx;
    }

    public RestAPIDbAdapter open() throws SQLException {
        mDbHelper = new DatabaseHelper(mCtx);
        mDb = mDbHelper.getWritableDatabase();
        return this;
    }
    
    public void close() {
        mDbHelper.close();
    }

    public long insertExperimentImages(int exp_id, ArrayList<String> imgList) {
    	String images = "";
    	int length = imgList.size();
    	
    	for (int i = 0; i < length; i++) {
    		images += imgList.get(i) + ",";
    	}
    	
    	ContentValues initialValues = new ContentValues();
    	initialValues.put(KEY_EXPERIMENT_ID, exp_id);
    	initialValues.put(KEY_PROVIDER_URL, images);
    	
    	return mDb.insert(DATABASE_TABLE_EXPERIMENT_IMAGES, null, initialValues);
    }
    
    public boolean deleteExperimentImages(int exp_id) {
    	return mDb.delete(DATABASE_TABLE_EXPERIMENT_IMAGES, KEY_EXPERIMENT_ID + "=" + exp_id, null) > 0;
    }
    
    public Cursor getExperimentImages(int exp_id) {
    	String[] tables = new String[1];
    	tables[0] = KEY_PROVIDER_URL;
    	Cursor mCursor = mDb.query(true, DATABASE_TABLE_EXPERIMENT_IMAGES, tables, KEY_EXPERIMENT_ID + " = " + exp_id, null, null, null, null, null);
    	    	
    	if (mCursor!= null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public long insertExperimentVideos(int exp_id, ArrayList<String> vidList) {
    	String videos = "";
    	int length = vidList.size();
    	
    	for (int i = 0; i < length; i++) {
    		videos += vidList.get(i) + ",";
    	}
    	
    	ContentValues initialValues = new ContentValues();
    	initialValues.put(KEY_EXPERIMENT_ID, exp_id);
    	initialValues.put(KEY_PROVIDER_URL, videos);
    	
    	return mDb.insert(DATABASE_TABLE_EXPERIMENT_VIDEOS, null, initialValues);
    }
    
    public boolean deleteExperimentVideos(int exp_id) {
    	return mDb.delete(DATABASE_TABLE_EXPERIMENT_VIDEOS, KEY_EXPERIMENT_ID + "=" + exp_id, null) > 0;
    }
    
    public Cursor getExperimentVideos(int exp_id) {
    	String[] tables = new String[1];
    	tables[0] = KEY_PROVIDER_URL;
    	Cursor mCursor = mDb.query(true, DATABASE_TABLE_EXPERIMENT_VIDEOS, tables, KEY_EXPERIMENT_ID + " = " + exp_id, null, null, null, null, null);
    	
    	if (mCursor!= null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public long insertExperimentTags(int exp_id, String tags) {
    	ContentValues initialValues = new ContentValues();
    	initialValues.put(KEY_EXPERIMENT_ID, exp_id);
    	initialValues.put(KEY_TAGS, tags);
    	
    	return mDb.insert(DATABASE_TABLE_EXPERIMENT_TAGS, null, initialValues);
    }
    
    public boolean deleteExperimentTags(int exp_id) {
    	return mDb.delete(DATABASE_TABLE_EXPERIMENT_TAGS, KEY_EXPERIMENT_ID + "=" + exp_id, null) > 0;
    }
    
    public Cursor getExperimentTags(int exp_id) {
    	String[] tables = new String[1];
    	tables[0] = KEY_TAGS;
    	Cursor mCursor = mDb.query(true, DATABASE_TABLE_EXPERIMENT_TAGS, tables, KEY_EXPERIMENT_ID + " = " + exp_id, null, null, null, null, null);

    	if (mCursor!= null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public void insertExperimentFields(int exp_id, ArrayList<ExperimentField> fields) {
    	int length = fields.size();
    	
    	for (int i = 0; i < length; i++) {
    		ContentValues initialValues = new ContentValues();
        	initialValues.put(KEY_EXPERIMENT_ID, exp_id);
        	initialValues.put(KEY_FIELD_NAME, fields.get(i).field_name);
        	initialValues.put(KEY_TYPE_NAME, fields.get(i).type_name);
        	initialValues.put(KEY_UNIT_ABBREVIATION, fields.get(i).unit_abbreviation);
        	initialValues.put(KEY_UNIT_NAME, fields.get(i).unit_name);
        	initialValues.put(KEY_FIELD_ID, fields.get(i).field_id);
        	initialValues.put(KEY_TYPE_ID, fields.get(i).type_id);
        	initialValues.put(KEY_UNIT_ID, fields.get(i).unit_id);

        	mDb.insert(DATABASE_TABLE_EXPERIMENT_FIELDS, null, initialValues);
    	}
    }
    
    public boolean deleteExperimentFields(int exp_id) {
    	return mDb.delete(DATABASE_TABLE_EXPERIMENT_FIELDS, KEY_EXPERIMENT_ID + "=" + exp_id, null) > 0;
    }
    
    public Cursor getExperimentFields(int exp_id) {
    	Cursor mCursor = mDb.query(true, DATABASE_TABLE_EXPERIMENT_FIELDS, null, KEY_EXPERIMENT_ID + " = " + exp_id, null, null, null, null, null);
    	
    	if (mCursor!= null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public long insertExperiment(Experiment e) {
    	ContentValues initialValues = new ContentValues();
    	initialValues.put(KEY_EXPERIMENT_ID, e.experiment_id);
    	initialValues.put(KEY_OWNER_ID, e.owner_id);
    	initialValues.put(KEY_NAME, e.name);
    	initialValues.put(KEY_DESCRIPTION, e.description);
    	initialValues.put(KEY_TIMECREATED, e.timecreated);
    	initialValues.put(KEY_TIMEMODIFIED, e.timemodified);
    	initialValues.put(KEY_DEFAULT_READ, e.default_read);
    	initialValues.put(KEY_DEFAULT_JOIN, e.default_join);
    	initialValues.put(KEY_FEATURED, e.featured);
    	initialValues.put(KEY_RATING, e.rating);
    	initialValues.put(KEY_RATING_VOTES, e.rating_votes);
    	initialValues.put(KEY_HIDDEN, e.hidden);
    	initialValues.put(KEY_FIRSTNAME, e.firstname);
    	initialValues.put(KEY_LASTNAME, e.lastname);
    	initialValues.put(KEY_PROVIDER_URL, e.provider_url);
    	
    	return mDb.insert(DATABASE_TABLE_EXPERIMENTS, null, initialValues);
    }

    public long insertExperiments(ArrayList<Experiment> exp) {
    	int length = exp.size();
    	
    	for (int i = 0; i < length; i++)
    		insertExperiment(exp.get(i));
    	
    	return 1;
    }
    
    public boolean deleteExperiment(Experiment exp) {
    	return mDb.delete(DATABASE_TABLE_EXPERIMENTS, KEY_EXPERIMENT_ID + "=" + exp.experiment_id, null) > 0;
    }
    
    public boolean deleteExperiments(ArrayList<Experiment> exp) {
    	int lenght = exp.size();
    	boolean result = false;
    	
    	for (int i = 0; i < lenght; i++)
    		result |= deleteExperiment(exp.get(i));
    	
    	return result;
    }
    
    public Cursor getExperiment(int exp_id) {
    	Cursor mCursor = mDb.query(true, DATABASE_TABLE_EXPERIMENTS, null, KEY_EXPERIMENT_ID + " = " + exp_id, null, null, null, null, null);
    	
    	if (mCursor != null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public Cursor getExperiments(int page, int count) {
    	int offset = (page - 1) * count;
    	
    	Cursor mCursor = mDb.rawQuery("SELECT * FROM " + DATABASE_TABLE_EXPERIMENTS + " ORDER BY " + KEY_EXPERIMENT_ID + " DESC" + " LIMIT " + count + " OFFSET " + offset, null);
    	
    	
    	if (mCursor!= null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public long insertSession(Session s) {
    	ContentValues initialValues = new ContentValues();
    	initialValues.put(KEY_SESSION_ID, s.session_id);
    	initialValues.put(KEY_OWNER_ID, s.owner_id);
    	initialValues.put(KEY_EXPERIMENT_ID, s.experiment_id);
    	initialValues.put(KEY_NAME, s.name);
    	initialValues.put(KEY_DESCRIPTION, s.description);
    	initialValues.put(KEY_STREET, s.street);
    	initialValues.put(KEY_CITY, s.city);
    	initialValues.put(KEY_COUNTRY, s.country);
    	initialValues.put(KEY_LATITUDE, s.latitude);
    	initialValues.put(KEY_LONGITUDE, s.longitude);
    	initialValues.put(KEY_TIMECREATED, s.timecreated);
    	initialValues.put(KEY_TIMEMODIFIED, s.timemodified);
    	initialValues.put(KEY_DEBUG_DATA, s.debug_data);
    	initialValues.put(KEY_FIRSTNAME, s.firstname);
    	initialValues.put(KEY_LASTNAME, s.lastname);
    	
    	return mDb.insert(DATABASE_TABLE_SESSIONS, null, initialValues);
    }

    public long insertSessions(ArrayList<Session> s) {
    	int length = s.size();
    	
    	for (int i = 0; i < length; i++)
    		insertSession(s.get(i));
    	
    	return 1;
    }
    
    public boolean deleteSession(Session s) {
    	return mDb.delete(DATABASE_TABLE_SESSIONS, KEY_SESSION_ID + "=" + s.session_id, null) > 0;
    }
    
    public boolean deleteSessions(ArrayList<Session> s) {
    	int lenght = s.size();
    	boolean result = false;
    	
    	for (int i = 0; i < lenght; i++)
    		result |= deleteSession(s.get(i));
    	
    	return result;
    }
    
    public Cursor getSession(int ses_id) {
    	Cursor mCursor = mDb.query(true, DATABASE_TABLE_SESSIONS, null, KEY_SESSION_ID + " = " + ses_id, null, null, null, null, null);
    	
    	if (mCursor != null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public Cursor getSessions(int exp_id) {
    	Cursor mCursor = mDb.rawQuery("SELECT * FROM " + DATABASE_TABLE_SESSIONS + " WHERE " + KEY_EXPERIMENT_ID + " = " + exp_id, null);
    	
    	if (mCursor!= null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public long insertPerson(Person p) {
    	ContentValues initialValues = new ContentValues();
    	initialValues.put(KEY_AIM, p.aim);
    	initialValues.put(KEY_CITY, p.city);
    	initialValues.put(KEY_COUNTRY, p.country);
    	initialValues.put(KEY_DATE_DIFF, p.date_diff);
    	initialValues.put(KEY_DEPARTMENT, p.department);
    	initialValues.put(KEY_EMAIL, p.email);
    	initialValues.put(KEY_FIRSTACCESS, p.firstaccess);
    	initialValues.put(KEY_FIRSTNAME, p.firstname);
    	initialValues.put(KEY_ICQ, p.icq);
    	initialValues.put(KEY_INSTITUTION, p.institution);
    	initialValues.put(KEY_LANGUAGE, p.langauge);
    	initialValues.put(KEY_LASTACCESS, p.lastaccess);
    	initialValues.put(KEY_LASTLOGIN, p.lastlogin);
    	initialValues.put(KEY_LASTNAME, p.lastname);
    	initialValues.put(KEY_MSN, p.msn);
    	initialValues.put(KEY_PICTURE, p.picture);
    	initialValues.put(KEY_SKYPE, p.skype);
    	initialValues.put(KEY_STREET, p.street);
    	initialValues.put(KEY_TIMEOBJ, p.timeobj);
    	initialValues.put(KEY_URL, p.url);
    	initialValues.put(KEY_YAHOO, p.yahoo);
    	initialValues.put(KEY_CONFIRMED, p.confirmed);
    	initialValues.put(KEY_EXPERIMENT_COUNT, p.experiment_count);
    	initialValues.put(KEY_LATITUDE, p.latititude);
    	initialValues.put(KEY_LONGITUDE, p.longitude);
    	initialValues.put(KEY_SESSION_COUNT, p.session_count);
    	initialValues.put(KEY_USER_ID, p.user_id);

    	return mDb.insert(DATABASE_TABLE_PEOPLE, null, initialValues);
    }

    public long insertPeople(ArrayList<Person> people) {
    	int length = people.size();
    	
    	for (int i = 0; i < length; i++)
    		insertPerson(people.get(i));
    	
    	return 1;
    }
    
    public boolean deletePerson(Person p) {
    	return mDb.delete(DATABASE_TABLE_PEOPLE, KEY_USER_ID + "=" + p.user_id, null) > 0;
    }
    
    public boolean deletePeople(ArrayList<Person> people) {
    	int lenght = people.size();
    	boolean result = false;
    	
    	for (int i = 0; i < lenght; i++)
    		result |= deletePerson(people.get(i));
    	
    	return result;
    }
    
    public Cursor getPerson(int user_id) {
    	Cursor mCursor = mDb.query(true, DATABASE_TABLE_PEOPLE, null, KEY_USER_ID + " = " + user_id, null, null, null, null, null);
    	
    	if (mCursor != null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
    public Cursor getPeople(int page, int count) {
    	int offset = (page - 1) * count;
    	
    	Cursor mCursor = mDb.rawQuery("SELECT * FROM " + DATABASE_TABLE_PEOPLE + " ORDER BY " + KEY_USER_ID + " DESC" + " LIMIT " + count + " OFFSET " + offset, null);
    	
    	if (mCursor!= null) {
    		mCursor.moveToFirst();
    	}
    	return mCursor;
    }
    
}
