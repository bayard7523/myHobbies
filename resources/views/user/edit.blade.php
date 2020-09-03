@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit User Details</div>
                    <div class="card-body">
                        <form autocomplete="off" action="/user/{{$user->id}}" enctype="multipart/form-data" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                       class="form-control {{ $errors->has('name') ? 'border-danger' : '' }}" id="name"
                                       name="name" value="{{ $user->name ?? old('name') }}">
                                <small class="form-text text-danger">{!! $errors->first('name') !!}</small>
                            </div>

                            @if(file_exists('img/users/' . $user->id . '_large.jpg'))
                            <div class="mb-2">
                                <img style="max-width: 400px; max-height: 300px" src="/img/users/{{ $user->id }}_large.jpg" alt="">
                                <a class="btn btn-outline-danger float-right" href="/delete-images/user/{{ $user->id }}">Delete Image</a>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file"
                                       class="form-control {{ $errors->has('image') ? 'border-danger' : '' }}" id="image"
                                       name="image" value="">
                                <small class="form-text text-danger">{!! $errors->first('image') !!}</small>
                            </div>
                            <div class="form-group">
                                <label for="motto">Motto</label>
                                <input type="text"
                                       class="form-control {{ $errors->has('motto') ? 'border-danger' : '' }}" id="motto"
                                       name="motto" value="{{ $user->motto ?? old('motto') }}">
                                <small class="form-text text-danger">{!! $errors->first('motto') !!}</small>
                            </div>
                            <div class="form-group">
                                <label for="motto">About me</label>
                                <textarea class="form-control {{ $errors->has('about_me') ? 'border-danger' : '' }}"
                                          id="motto" name="about_me"
                                          rows="5">{{$user->about_me ?? old('about_me')}}</textarea>
                                <small class="form-text text-danger">{!! $errors->first('about_me') !!}</small>
                            </div>
                            <input class="btn btn-primary mt-4" type="submit" value="Update Profile">
                        </form>
                        <a class="btn btn-primary float-right" href="{{ URL::previous() }}"><i class="fas fa-arrow-circle-up"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
