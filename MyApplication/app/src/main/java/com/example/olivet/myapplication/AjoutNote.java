package com.example.olivet.myapplication;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.pdf.PdfDocument;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;

import java.util.ArrayList;

/**
 * Created by schultz on 20/12/17.
 */

public class AjoutNote extends Activity {

    ArrayList<Spinner> listeSpin = new ArrayList<Spinner>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        requestWindowFeature(Window.FEATURE_NO_TITLE);

        setContentView(R.layout.ajouternote);

        TextView tvIdJury = (TextView) findViewById(R.id.textViewAjNoteIDJury);
        final int id = getIntent().getExtras().getInt("NumJury");
        tvIdJury.setText(tvIdJury.getText().toString()+id);

        TextView tvNomProjet = (TextView) findViewById(R.id.textViewNomProjet);
        final String NomProj = getIntent().getExtras().getString("NomProj");
        tvNomProjet.setText(tvNomProjet.getText().toString()+NomProj);


        final Spinner spinnerOri = (Spinner) findViewById(R.id.spinnerOriginalite);
        // Create an ArrayAdapter using the string array and a default spinner layout
        ArrayAdapter<CharSequence> adapterOri = ArrayAdapter.createFromResource(this,
                R.array.notes_array, android.R.layout.simple_spinner_item);
        // Specify the layout to use when the list of choices appears
        adapterOri.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        // Apply the adapter to the spinner
        spinnerOri.setAdapter(adapterOri);


        final Spinner spinnerProto = (Spinner) findViewById(R.id.spinnerProto);
        ArrayAdapter<CharSequence> adapterProto = ArrayAdapter.createFromResource(this,
                R.array.notes_array, android.R.layout.simple_spinner_item);
        adapterProto.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerProto.setAdapter(adapterProto);


        final Spinner spinnerDem = (Spinner) findViewById(R.id.spinnerDemarche);
        ArrayAdapter<CharSequence> adapterDem = ArrayAdapter.createFromResource(this,
                R.array.notes_array, android.R.layout.simple_spinner_item);
        adapterDem.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerDem.setAdapter(adapterDem);


        final Spinner spinnerPluri = (Spinner) findViewById(R.id.spinnerPluri);
        ArrayAdapter<CharSequence> adapterPluri = ArrayAdapter.createFromResource(this,
                R.array.notes_array, android.R.layout.simple_spinner_item);
        adapterPluri.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerPluri.setAdapter(adapterPluri);


        final Spinner spinnerMait = (Spinner) findViewById(R.id.spinnerMaitrise);
        ArrayAdapter<CharSequence> adapterMait = ArrayAdapter.createFromResource(this,
                R.array.notes_array, android.R.layout.simple_spinner_item);
        adapterMait.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerMait.setAdapter(adapterMait);


        final Spinner spinnerDevDur = (Spinner) findViewById(R.id.spinnerDevDurable);
        ArrayAdapter<CharSequence> adapterDevDur = ArrayAdapter.createFromResource(this,
                R.array.notes_array, android.R.layout.simple_spinner_item);
        adapterDevDur.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerDevDur.setAdapter(adapterDevDur);

        listeSpin.add(spinnerDevDur);
        listeSpin.add(spinnerMait);
        listeSpin.add(spinnerPluri);
        listeSpin.add(spinnerDem);
        listeSpin.add(spinnerProto);
        listeSpin.add(spinnerOri);


        //FAire les add au meme moment apres creation et verif le sens
        //REfaire la pârtie ou listeNote = [null, null...]


        ArrayList<Integer> listeNote = getIntent().getExtras().getIntegerArrayList("listeNote");

        for (int i = 1; i < listeNote.size(); i++) {
            if (listeNote.get(i) != null) {
                listeSpin.get(i-1).setSelection(listeNote.get(i) + 1);
            }
            else{
                listeSpin.get(i-1).setSelection(0);
            }
        }

        Button bsuppr = (Button) findViewById(R.id.buttonSuppr);
        bsuppr.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                spinnerOri.setSelection(0);
                spinnerProto.setSelection(0);
                spinnerDem.setSelection(0);
                spinnerPluri.setSelection(0);
                spinnerMait.setSelection(0);
                spinnerDevDur.setSelection(0);
            }
        });

        Button bABS = (Button) findViewById(R.id.buttonABS);
        bABS.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                spinnerOri.setSelection(1);
                spinnerProto.setSelection(1);
                spinnerDem.setSelection(1);
                spinnerPluri.setSelection(1);
                spinnerMait.setSelection(1);
                spinnerDevDur.setSelection(1);
            }
        });

        Button bValider = (Button) findViewById(R.id.buttonValider);
        bValider.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                ArrayList<Integer> listeNote = new ArrayList<Integer>();

                GroupeManager gpMan = new GroupeManager(view.getContext());
                gpMan.open();
                int idGp = gpMan.getNumGroupe(NomProj);
                gpMan.close();

                boolean trouve = false;
                ArrayList<Integer> listeASuppr = new ArrayList<Integer>();
                for (ArrayList<Integer> listeAux : Page_connexion.listeGrpNote){
                    if (idGp == listeAux.get(0)){
                        listeASuppr = listeAux;
                        trouve = true;
                    }
                }

                if (trouve){
                    Page_connexion.listeGrpNote.remove(listeASuppr);
                }

                listeNote.add(idGp);
                for (Spinner spin : listeSpin) {

                    if (("None").equals((String) spin.getSelectedItem())) {
                        listeNote.add(null);
                    } else {
                        listeNote.add(Integer.parseInt((String) spin.getSelectedItem()));
                    }
                }
                Page_connexion.listeGrpNote.add(listeNote);

                DonneManager donMan = new DonneManager(view.getContext());
                donMan.open();
                Cursor c = donMan.getDonneIdNote(id, idGp);
                int idNote = -1;
                if (c.moveToFirst()){
                    idNote = c.getInt(c.getColumnIndex("idNote"));
                }
                Donne donne = new Donne(id, idGp, idNote);
                donMan.modDonne(donne);
                donMan.close();

                NoteManager noteMan = new NoteManager(view.getContext());
                noteMan.open();
                Note note = new Note(idNote, listeNote.get(1), listeNote.get(2), listeNote.get(3), listeNote.get(4), listeNote.get(5), listeNote.get(6));
                noteMan.modNoteHard(note);
                noteMan.close();

                Intent i = new Intent(AjoutNote.this,Planning.class);
                i.putExtra("NumJury", getIntent().getExtras().getInt("NumJury"));
                i.putExtra("nomProjet", getIntent().getExtras().getStringArrayList("nomProjet"));
                i.putExtra("heureD", getIntent().getExtras().getStringArrayList("heureD"));
                i.putExtra("heureF", getIntent().getExtras().getStringArrayList("heureF"));
                i.putExtra("NumGroupe", getIntent().getExtras().getIntegerArrayList("NumGroupe"));
                finish();
                startActivity(i);
            }
        });
    }

    @Override
    public void onBackPressed(){
        Intent i = new Intent(AjoutNote.this,Planning.class);
        i.putExtra("NumJury", getIntent().getExtras().getInt("NumJury"));
        i.putExtra("nomProjet", getIntent().getExtras().getStringArrayList("nomProjet"));
        i.putExtra("heureD", getIntent().getExtras().getStringArrayList("heureD"));
        i.putExtra("heureF", getIntent().getExtras().getStringArrayList("heureF"));
        i.putExtra("NumGroupe", getIntent().getExtras().getIntegerArrayList("NumGroupe"));
        finish();
        startActivity(i);
    }

    @Override
    public void onPause(){
        super.onPause();
        finish();

    }
}
