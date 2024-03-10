<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use App\Models\FootAlbums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
        $rules =
        [
            'name'  => 'required|string|max:255',

        ];

        $names =
        [
            'name'                  =>'Name',
        ];

        $data = $this->validate($request , $rules , [] , $names);

        $album = new Albums();
        $album->name = $request->name;
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
        $rules =
        [
            'name'  => 'required|string|max:255',

        ];

        $names =
        [
            'name'                  =>'Name',
        ];

        $data = $this->validate($request , $rules , [] , $names);

        $album = Albums::find($id);
        $album->name = $request->name;
        $album->save();
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

    public function CopyAlbums(Request $request,$id){
        $rules =
        [
            'name'  => 'required|string|max:255',

        ];

        $names =
        [
            'name'                  =>'Name',
        ];

        $data = $this->validate($request , $rules , [] , $names);


        $album = Albums::find($id);

        $newAlbum = $album->replicate();
        $newAlbum->name = $request->name;

        $newAlbum->created_at  = now();
        $newAlbum->updated_at  = now();
        $newAlbum->save();

        $sourceFilePath = 'storage/app/attachments_folder/';

        foreach($album->FootAlbums as $objalbum){
            $new_file = new FootAlbums();
            $new_file->name       = $objalbum->name;
            $new_file->type       = $objalbum->type;

            $oldFilePath = $sourceFilePath . $objalbum->file_path;
            $newFilePath = $newAlbum->id . '-' . $objalbum->file_path;

            // dd(File::exists($oldFilePath));

            if (File::exists($oldFilePath)) {
                File::copy($oldFilePath,$sourceFilePath.$newFilePath);
            }

            $new_file->file_path  = $newFilePath;
            $new_file->album_id   = $newAlbum->id;
            $new_file->created_by =  Auth::user()->id;
            $new_file->save();
        }


        return redirect()->back()
        ->with('success','Album copied successfully');
    }
}
