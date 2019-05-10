<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    private function getPath() {
        return 'files/' . $this->user->id . '/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = $this->user->files;
        return view('file.index')->with('files', $files)->with('spaceSize', $this->user->space_size)->with('spaceUsed', $this->user->space_used);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('file.create');
    }

    /**
     * Get a validator for an incoming store request.
     *
     * @override
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'data' => ['required', 'file'],
            'preview' => ['string', 'max:65535'],
            'encrypt' => ['boolean'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors = $this->validator($request->all())->errors();

        if (count($errors)) {
            return response(['errors' => $errors], 400);
        }

        $size = $request->file('data')->getSize();

        if ($this->user->space_used + $size > $this->user->space_size) {
            return response(['errors' => ['Quota limit has been reached.']], 400);
        }

        $file = new File();
        $file->name = $request->file('data')->getClientOriginalName();
        $file->mime_type = $request->file('data')->getClientMimeType();
        $file->size = $size;
        $file->user_id = $this->user->id;

        if ($request->has('preview')) {
            $file->preview = $request->input('preview');
        }

	if ($request->has('encrypt') && $request->input('encrypt')) {
            $file->encrypted = true;
            $fileContent = encrypt($request->file('data')->get());
        } else {
            $fileContent = $request->file('data')->get();
        }

        Storage::put($this->getPath() . $file->name, $fileContent);

        $file->save();

        return redirect()->route('file.index')->with('status', 'File uploaded!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        if ($file->user->id != $this->user->id) {
            return abort(401);
        }

        if ($file->encrypted) {
            $fileContent = decrypt(Storage::get($this->getPath() . $file->name));
	} else {
            $fileContent = Storage::get($this->getPath() . $file->name);
        }
        return response()->streamDownload(function() use ($fileContent) {
            echo $fileContent;
        }, $file->name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $path = "files/" . $this->user->id . "/";
        if (Storage::exists($path . $file->name)) {
            Storage::delete($path . $file->name);
        }
        $file->delete();
        return redirect()->route('file.index')->with('status', 'File deleted!');
    }
}
