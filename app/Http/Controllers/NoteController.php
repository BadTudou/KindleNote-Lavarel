<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('note.index', ['notes'=>Note::all()]);
        return view('note.index', ['notes'=>Auth::user()->notes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('note.create', ['note'=>new Note()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'bail|required|max:100',
            'author'=>'bail|required|max:100',
            'dateTime'=>'bail|required|date',
            'text'=>'required'
        ]);

        if ($validator->fails()) {
            var_dump($request->all());
            // TODO: withInput() not work
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $note = new Note;
        $note->user_id = Auth::id();
        $note->title = $request->title;
        $note->author = $request->author;
        $note->dateTime = $request->dateTime;
        $note->text = $request->text;
        if($note->save()){
            return Redirect::to(action('NoteController@index'));
        }
        else{
            return Redirect::to(action('NoteController@store'))->withErrors('error')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('note.show', ['note'=>Note::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return  view('note.edit', ['note'=>Note::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'bail|required|max:100',
            'author'=>'bail|required|max:100',
            'dateTime'=>'bail|required|date',
            'text'=>'required'
        ]);

        if ($validator->fails()) {
            var_dump($request->all());
            // TODO: withInput() not work
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $note = Note::find($id);
        $note->user_id = Auth::id();
        $note->title = $request->title;
        $note->author = $request->author;
        $note->dateTime = $request->dateTime;
        $note->text = $request->text;
        if($note->save()){
            return Redirect::to(action('NoteController@index'));
        }
        else{
            return Redirect::to(action('NoteController@store'))->withErrors('save note has error')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->notes->find($id)){
            Note::find($id)->delete();
            return Redirect::to(action('NoteController@index'));
        }
        echo 'destroy'.$id;
    }

}