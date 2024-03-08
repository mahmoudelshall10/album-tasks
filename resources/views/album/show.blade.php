@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('albums.index') }}" class="btn btn-primary">Return Back</a>
                    <br>
                    <br>
                    <p>Created By : {{ $album->CreatedBy->name }}</p>
                    <p>Album Name : {{ $album->CreatedBy->name }}</p>
                </div>

                <div class="card-body">
                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! \Session::get('success') !!}</li>
                        </ul>
                    </div>
                @endif

                @if (\Session::has('error'))
                    <div class="alert alert-danger">
                        <ul>
                            <li>{!! \Session::get('error') !!}</li>
                        </ul>
                    </div>
                @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Created By</th>
                                    <th>Name</th>
                                    <th>Show</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($album->FootAlbums as $objalbum)
                                <tr>
                                    <td>{{ $objalbum->id }}</td>
                                    <td>{{ $objalbum->CreatedBy->name }}</td>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
