<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        // return response()->json(['data'=> $posts]); //mengirim semua yang ada di dalam tabel
        return PostDetailResource::collection($posts->loadMissing(['writer:id,username','comments:id,post_id,user_id,comments_content'])); //apa yang kita setting di PostResource seenak jidat
    }

    public function show($id)
    {
        $post = Post::with('writer:id,username')->findOrFail($id);
        return new PostDetailResource($post->loadMissing(['writer:id,username','comments:id,post_id,user_id,comments_content']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $image =null;
        if ($request->file) {
            //disini kita upload file nya
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName.'.'.$extension;
            //kalau mau dibatasi, seperti dilarang upload file exe, bisa diisi disini. Namun, di dalam codingan ini, saya tidak memasukannya, jadi bebas mau upluod file mana saja

            Storage::putFileAs('image', $request->file, $image);
        }

        $request['image'] = $image;
        $request['author'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }

    // public function show2($id)
    // {
    //     $post = Post::findOrFail($id);
    //     return new PostDetailResource($post);
    // }
}
