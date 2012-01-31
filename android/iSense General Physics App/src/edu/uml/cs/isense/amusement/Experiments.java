package edu.uml.cs.isense.amusement;

import java.util.ArrayList;

import edu.uml.cs.isense.amusement.R;

import edu.uml.cs.isense.comm.RestAPI;
import edu.uml.cs.isense.objects.Experiment;

import android.app.Activity;
//import android.app.Dialog;
import android.app.ListActivity;
import android.content.Context;
//import android.content.DialogInterface;
import android.content.Intent;
/*import android.content.SharedPreferences;
import android.content.DialogInterface.OnDismissListener;
import android.content.SharedPreferences.Editor;*/
import android.os.Bundle;
/*import android.os.Handler;
import android.os.Message;
import android.preference.PreferenceManager;*/
import android.text.Editable;
import android.text.TextWatcher;
/*import android.view.Menu;
import android.view.MenuItem;*/
import android.view.View;
import android.widget.EditText;
import android.widget.ListView;

public class Experiments extends ListActivity {
	private ExperimentAdapter m_adapter; 
	@SuppressWarnings("unused")
		private RestAPI rapi; 
	@SuppressWarnings("unused")
		private Context mContext; 
	@SuppressWarnings("unused")
		private boolean finish = false;
	private ArrayList<Experiment> m_experiments;
	
	@Override
	public Object onRetainNonConfigurationInstance() {
	    final ArrayList<Experiment> list = m_adapter.items;
	    final int loaded = m_adapter.itemsLoaded;
	    final boolean allLoaded = m_adapter.allItemsLoaded;
	    final int page = m_adapter.page;
	    Object[] objs = new Object[4];
	    objs[0] = list;
	    objs[1] = loaded;
	    objs[2] = allLoaded;
	    objs[3] = page;
	    return objs;
	}
	

	/** Called when the activity is first created. */
    @SuppressWarnings("unchecked")
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.experiments);
        rapi = RestAPI.getInstance();
        mContext = this;
        
        Bundle extras = getIntent().getExtras();
        if (extras != null) {
        	finish = true;
        }
        
        setResult(Activity.RESULT_CANCELED);
                
        final Object data = getLastNonConfigurationInstance();
        final Object[] dataList = (Object[]) data;
        // The activity is starting for the first time, load the data from the site
        if (data != null) {
            // The activity was destroyed/created automatically
        	m_experiments = (ArrayList<Experiment>) dataList[0];
        } else {
        	m_experiments = new ArrayList<Experiment>();
        }
        
        this.m_adapter = new ExperimentAdapter(getBaseContext(), R.layout.experimentrow, R.layout.loadrow, m_experiments);
        
        if (data != null) {
        	m_adapter.itemsLoaded = (Integer) dataList[1];
        	m_adapter.allItemsLoaded = (Boolean) dataList[2];
        	m_adapter.page = (Integer) dataList[3];
        }
        setListAdapter(this.m_adapter);
        
        final EditText et = (EditText) findViewById(R.id.ExperimentSerchInput);
        et.setSingleLine(true);
        et.addTextChangedListener(new TextWatcher() {

			@Override
			public void afterTextChanged(Editable s) {

			}

			@Override
			public void beforeTextChanged(CharSequence s, int start, int count,
					int after) {
				// TODO Auto-generated method stub
				
			}

			@Override
			public void onTextChanged(CharSequence s, int start, int before, int count) {
				if (s == null || s.length() == 0) {
					m_experiments = new ArrayList<Experiment>();
					m_adapter = new ExperimentAdapter(getBaseContext(), R.layout.experimentrow, R.layout.loadrow, m_experiments);
			        setListAdapter(m_adapter);
				} else {
					m_experiments = new ArrayList<Experiment>();
					m_adapter = new ExperimentAdapter(getBaseContext(), R.layout.experimentrow, R.layout.loadrow, m_experiments);
					m_adapter.action = "search";
					m_adapter.query = s.toString();
			        setListAdapter(m_adapter);
				}
			}
        	
        });
                
    }
   
	@Override
	protected void onListItemClick(ListView l, View v, int position, long id) {
		super.onListItemClick(l, v, position, id);
		Experiment e = m_experiments.get(position);

		Intent intent = new Intent();
        intent.putExtra("edu.uml.cs.isense.pictures.experiments.exp_id", e.experiment_id);

        setResult(Activity.RESULT_OK, intent);
        finish();
	}
    
    
}

