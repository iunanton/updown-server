@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">My Files</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($files->count() == 0)
                        <p>No files</p>
                    @else
                        <div class="table-responsive">
                            <table class="table" style="min-width: 1300px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Size</th>
                                        <th>Encrypted</th>
                                        <th>MIME type</th>
                                        <th>Preview</th>
                                        <th>Owner</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($files as $file)
                                        <tr>
                                            <td>{{ $file->id }}</td>
                                            <td><a href="{{ route('file.show', $file->id) }}">{{ \Illuminate\Support\Str::limit($file->name, 30, '[...]') }}</a></td>
                                            <td>{{ $file->size }}</td>
                                            <td>{{ $file->encrypted ? "yes" : "no" }}</td>
                                            <td>{{ $file->mime_type }}</td>
                                            <td>{{ $file->preview }}</td>
                                            <td>{{ $file->user_id }}</td>
                                            <td>{{ $file->created_at }}</td>
                                            <td>{{ $file->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <div class="progress my-2">
                <div class="progress-bar bg-pink" role="progressbar" style="width: {{ $spaceUsed * 100 / $spaceSize }}%" aria-valuenow="{{ $spaceUsed * 100 / $spaceSize }}" aria-valuemin="0" aria-valuemax="100">{{ $spaceUsed . " / " . $spaceSize }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
