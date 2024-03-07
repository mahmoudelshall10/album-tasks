<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use App\Models\FootAlbums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class AlbumsController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = Albums::orderBy('id','asc')->get();
        return view('album.index',compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $footer = FootAlbums::whereNull('album_id')->orderBy('id','asc')->get();
        return view('album.create',compact('footer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $album = new Albums();
        $album->created_by = Auth::user()->id;
        $album->save();

        FootAlbums::whereNull('album_id')->where('created_by',Auth::user()->id)->update(['album_id' => $album->id]);

        return redirect()->route('albums.index')
        ->with('success','Albums created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\albums  $albums
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $album = Albums::find($id);
        return view('album.show',compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\albums  $albums
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $album = Albums::find($id);
        return view('album.edit',compact('album'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\albums  $albums
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $album = Albums::find($id);

        // FootAlbums::whereNull('album_id')->where('created_by',Auth::user()->id)->update(['album_id' => $album->id]);

        return redirect()->route('albums.index')
        ->with('success','Album updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\albums  $albums
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $album = Albums::find($id);

        $attachments = FootAlbums::where('album_id',$id)->get();
        foreach($attachments as $attachment){

            $path = 'storage/app/attachments_folder/' . $attachment->file_path;
            if($attachment->file_path){
                if(file_exists($path))
                {
                    unlink($path);
                }
            }
        }

        $attachments = FootAlbums::where('album_id',$id)->delete();

        $album->delete();

        return redirect()->back()
                ->with('success','Album deleted successfully');
    }
}
