@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">API</div>

                <div class="card-body">
		    <table class="table">
			<thead>
                            <tr>
                                <th>Method</th>
                                <th>URI</th>
                                <th>Parameters</th>
                                <th>Middleware</th>
                                <th>Code</th>
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>POST</td><td>/api/register</td><td>name, email, password, password_comfirmation</td><td>-</td><td>200</td><td>{token_type, expires_in, access_token, refresh_token}</td>
                        </tr>
                        <tr>
                            <td>POST</td><td>/api/login</td><td>email, password</td><td>-</td><td>200</td><td>{token_type, expires_in, access_token, refresh_token}</td>
                        </tr>
                        <tr>
                            <td>POST</td><td>/api/refresh</td><td>refresh_token</td><td>-</td><td>200</td><td>{token_type, expires_in, access_token, refresh_token}</td>
                        </tr>
                        <tr>
                            <td>GET</td><td>/api/logout</td><td>-</td><td>auth:api</td><td>204</td><td>-</td>
                        </tr>
			<tr>
                            <td>GET</td><td>/api/user</td><td>-</td><td>auth:api</td><td>200</td><td>{id, name, email, email_verified_at, space_size, user_space, files, created_at, updated_at}</td>
                        </tr>
                        <tr>
                            <td>GET</td><td>/api/file</td><td>-</td><td>auth:api</td><td>200</td><td>[files]</td>
                        </tr>
                        <tr>
                            <td>GET</td><td>/api/file/{file_id}</td><td>-</td><td>auth:api</td><td>200</td><td>binary data</td>
                        </tr>
                        <tr>
                            <td>POST</td><td>/api/file</td><td>data, encrypt, preview</td><td>auth:api</td><td>201</td><td>id, name, size, encrypted, user_id, create_at, updated_at</td>
                        </tr>
                        <tr>
                            <td>DELETE</td><td>/api/file/{file_id}</td><td>-</td><td>auth:api</td><td>204</td><td>-</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
