@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload</div>

                <div class="card-body text-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="upload-form" action="{{ route('file.store') }}" method="POST" enctype="multipart/form-data" style="display:none;">
                        @csrf
                        <input id="file" type="file" name="data" onchange="document.getElementById('upload-form').submit()">
                    </form>

                    <div class="btn btn-pink mt-4 pl-4 pr-4 pt-0 pb-0" onclick="document.getElementById('file').click()">
                        <img src="{{ asset('images/upload.png') }}" class="img-fluid" alt="upload">
                    </div>

                    <div class="text-muted"><small>Max file size is 200 MB</small></div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
