<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FootAlbums;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class FootAlbumsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function RemoveSingleFile(Request $request)
    {
        $attachment = FootAlbums::find($request->id);
        $path = 'storage/app/attachments_folder/' . $attachment->file_path;
        if($attachment){
            if(file_exists($path))
            {
                unlink($path);
            }
        }
        $attachment->delete();
        return json_encode(['status'=>true]);
    }


    public function ShowFile($id)
    {
        $attachment = FootAlbums::find($id);
        return view('albums.show_file',compact('attachment'));
    }

    public function uploadSingleImage(Request $request){
        $rules =
        [
            'attachment'  => 'required|file|mimes:png,jpg,jpeg,pdf,doc,docx,txt,PDF,rtf,rar,xls,xlsx,gif|max:4000',

        ];

        $names =
        [
            'attachment'                  =>'Attachment',
        ];
        
        $data = $this->validate($request , $rules , [] , $names);

        $destinationPath = 'storage/app/attachments_folder/';

        if (!file_exists($destinationPath)) {

            mkdir($destinationPath, 755, true);
        }
        $id = $request->id ? $request->id : null;
        $attachment = $request->file('file');

        $filename3 = '';
        $filename = '';
        $extension = '';

        $filename        = $attachment->getClientOriginalName();
        $extension       = $attachment->getClientOriginalExtension();
        $filename3       = time().'-'.Str::uuid().'-'.Auth::user()->id.'.'.$attachment->getClientOriginalExtension();

        $attachment->move($destinationPath, $filename3);

        $new_file = new FootAlbums();
        $new_file->name       = $filename;
        $new_file->type       = $this->getFileType($extension);
        $new_file->file_path  = $filename3;
        $new_file->album_id   = $id;
        $new_file->created_by =  Auth::user()->id;
        $new_file->save();

    }

    public function UploadMultiFile(Request $request)
    {
        $arrImage = [];
        $rules =
        [
            'attachment' => 'required|array',
            'attachment.*'  => 'required|file|mimes:png,jpg,jpeg,pdf,doc,docx,txt,PDF,rtf,rar,xls,xlsx,gif|max:4000',

        ];

        $names =
        [
            'attachment'                  =>'Attachment',
        ];

        $data = $this->validate($request , $rules , [] , $names);

            $id = $request->id ? $request->id : null;

            $destinationPath = 'storage/app/attachments_folder/';

            if (!file_exists($destinationPath)) {

                mkdir($destinationPath, 755, true);
            }

          if($request->has('attachment')) {


            foreach($request->file('attachment') as $key => $attachment){

                $filename3 = '';
                $filename = '';
                $extension = '';

                $filename        = $attachment->getClientOriginalName();
                $extension       = $attachment->getClientOriginalExtension();
                $filename3       = time().'-'.Str::uuid().'-'.$id.'-'.Auth::user()->id.'.'.$attachment->getClientOriginalExtension();
                $attachment->move($destinationPath, $filename3);

                $insert[$key]['name'] = $filename;
                $insert[$key]['path'] = $filename3;
                $insert[$key]['type'] = $extension;
            }



            foreach($insert as $attachment){

                $new_file = new FootAlbums();
                $new_file->name       = $attachment['name'];
                $new_file->type       = $this->getFileType($attachment['type']);
                $new_file->file_path  = $attachment['path'];
                $new_file->album_id =  $id;
                $new_file->created_by =  Auth::user()->id;
                $new_file->save();
                array_push($arrImage,$new_file);
            }
        }
        return json_encode($arrImage);
    }

    private function getFileType($extension)
    {
        $imageExtensions = ['jpeg', 'jpg', 'png', 'gif'];
        $pdfExtensions = ['pdf','doc','docx','txt','PDF',"rtf","rar",'xls','xlsx'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $pdfExtensions)) {
            return 'file';
        }

        return 'unknown';
    }
}
