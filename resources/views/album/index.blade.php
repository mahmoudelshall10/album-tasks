@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                <a href="{{ route('albums.create') }}" class="btn btn-primary">Create Album</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! \Session::get('success') !!}</li>
                        </ul>
                    </div>
                @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Show</th>
                                <th>Edit</th>
                                {{-- <th>Copy</th> --}}
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($albums as $objalbum)

                            <tr>
                                <td>{{ $objalbum->id }}</td>
                                <td>{{ $objalbum->name }}</td>
                                <td>{{ $objalbum->CreatedBy->name }}</td>
                                <td><a href="{{route('albums.show',$objalbum->id)}}" target="_blank" class="btn btn-success">Show</a></td>
                                <td><a href="{{route('albums.edit',$objalbum->id)}}" target="_blank" class="btn btn-warning">Edit</a></td>

                                
                                         <td><a href="#deModalCopy{{$objalbum->id}}" data-toggle="modal" class="btn btn-info">Copy</a>
                                    <div class="modal fade" id="deModalCopy{{$objalbum->id}}" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Copy Confirmation</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to Copy this album?</p>
                                                    <form method="POST"  action="{{route('albums.copy_album',$objalbum->id)}}">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="name">Album Name</label>
                                                            <input type="text" name="name" required class="form-control" id="name" value="{{ $objalbum->name }}">
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Copy</button>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                   

                                <td><a href="#deModal{{$objalbum->id}}" data-toggle="modal" class="btn btn-danger">Delete</a>
                                <div class="modal fade" id="deModal{{$objalbum->id}}" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this record?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST"  action="{{route('albums.destroy',$objalbum->id)}}">
                                                    @csrf
                                                    @method('Delete')
                                                    <button type="submit" class="btn btn-danger">Confirm</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
