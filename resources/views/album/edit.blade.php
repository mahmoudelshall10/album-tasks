@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('albums.index') }}" class="btn btn-primary">Return Back</a>
                </div>

                <div class="card-body">


                <div class="alert alert-success" id="div_success">
                    <ul>Successfully Uploaded !!</ul>
                </div>

                <div class="alert alert-danger" id="div_error">
                    <ul>Something Wrong !!</ul>
                </div>

                        <form action="{{route('albums.update',$album->id)}}"  method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="name">Album Name</label>
                                <input type="text" name="name" required class="form-control" id="name" value="{{ $album->name }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <br>
                        </form>
                        <br>
                        <form action="{{ route('foot_albums.UploadMultiFile') }}" method="POST" class="dropzone" enctype="multipart/form-data">
                            @csrf
                            <div class="fallback">
                                <input type="file" name="attachment" multiple id="attachment">
                            </div>
                        </form>


                        <br>
                        <table class="table table-bordered" id="myTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Show</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($album->FootAlbums as $objalbum)
                                <tr id="remove_single_{{ $objalbum->id }}">
                                    <td>{{ $objalbum->id }}</td>
                                    <td>{{ $objalbum->name }}</td>
                                    <td>
                                        @if($objalbum->type == 'image')
                                        <img src="{{ url('/storage/app/attachments_folder') }}/{{ $objalbum->file_path}}" style="width: 60px;height: 60px;">
                                        <br>
                                        <br>
                                        <a href="{{ url('/storage/app/attachments_folder/'.$objalbum->file_path) }}" class="btn btn-info" download>Download</a>
                                        @elseif($objalbum->type == 'file')
                                        <a href="{{ route('foot_albums.ShowFile',$objalbum->id) }}" class="btn btn-info">Show File</a>

                                        <a href="{{ url('/storage/app/attachments_folder/'.$objalbum->file_path) }}" class="btn btn-info" download>Download</a>
                                        @endif
                                       </td>
                                       <td><button type="button" onclick="deleteSingleId({{ $objalbum->id }})" class="btn btn-danger"><span aria-hidden="true">&times;</span></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script >
    $(document).ready(function(){
        $("#div_success").hide();
        $("#div_error").hide();
    });
    function deleteSingleId(id){
        $.ajax({
            url:"{{ route('RemoveSingleFile') }}",
            type:"POST",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id},
            success:function(data){
                $("#remove_single_"+id).remove();
            }
        })
    }

    var myDropzone = new Dropzone('.dropzone', {
        url: "{{ route('foot_albums.UploadMultiFile') }}",
        paramName: 'attachment',
        uploadMultiple: true,
        parallelUploads: 1,
        maxFilesize: 4,
        addRemoveLinks : true,
        dictRemoveFile : "Remove",
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.xlsx,.xls,.rar.rtf,.PDF,.txt,.docx,.doc",
        params: {
            id: "{{ $album->id }}",
        },
    });

    myDropzone.on('success', function(file, response) {
        var response = JSON.parse(response);
        var url = "{{ url('') }}";
        $.each(response, function(index, value) {
        $("#myTable tbody").append(`
            <tr id="remove_single_${value.id}">
                <td>${value.id}</td>
                <td>${value.name}</td>
                <td>
                    ${value.type === 'image' ? `
                        <img src="${url+'/storage/app/attachments_folder'}/${value.file_path}" style="width: 60px; height: 60px;">
                        <br><br>
                        <a href="${url+'/storage/app/attachments_folder'}/${value.file_path}" class="btn btn-info" download>Download</a>
                    ` : value.type === 'file' ? `
                        <a href="${url+'/foot_albums/show_file/'}/${value.id}" class="btn btn-info">Show File</a>
                        <a href="${url+'/storage/app/attachments_folder'}/${value.file_path}" class="btn btn-info" download>Download</a>
                    ` : ''}
                </td>
                <td>
                    <button type="button" onclick="deleteSingleId(${value.id})" class="btn btn-danger">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </td>
            </tr>
        `);
    });
    $("#div_success").show();
    $("#div_error").hide();
    });

    myDropzone.on('error', function(file, errorMessage) {
        $("#div_success").hide();
        $("#div_error").show();
    });

</script>
@endsection
