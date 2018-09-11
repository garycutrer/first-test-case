<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;

class PostsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // allow guests to see blog posts

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);

    }


    public function index()
    {
        // $posts = DB::select('SELECT * FROM posts'); <-using DB library
        //  $posts = Post::orderBy('created_at', 'desc')->get();

        // $posts = Post::all();

        //return view ($posts);

        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required'

        ]);

        // Insert new post into database

        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->save();

        return redirect('/posts')->with('success', 'Post created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $post = Post::find($id);
        return view('posts.show')->with('post', $post); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::find($id);

        // Check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect ('/posts')->with('error', 'Unauthorized access');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //        dd([$id, $request]);


        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required'

        ]);


        // $sameid = $id;

        // Update post into database

        $post = Post::find($id);

        // Check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect ('/posts')->with('error', 'Unauthorized user action');
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect('/posts')->with('success', 'Post updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        
        // Check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect ('/posts')->with('error', 'Unauthorized attempt to delete');
        }

        $post->delete();
        echo "this.";
        return redirect('/posts/')->with('success', 'Post deleted');

    }
}
