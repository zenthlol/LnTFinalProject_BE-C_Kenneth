<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        // if (Auth::user()->role == 'Member') {
        //     $blogs = Blog::where('user_id', Auth::user()->id)->get();
        // } else {
        //     $blogs = Blog::all();
        // }

        $blogs = Auth::user()->role == 'Member' ? Blog::where('user_id', Auth::user()->id)->get() : Blog::all();
        $categories = Category::all();
        return view('blogs.index', [
            'blogs' => $blogs,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'thumbnail' => 'required',
            'title' => 'required|min:5|max:80',
            'content' => 'required',
            'category' => 'required',
        ]);

        // File Processing
        $files = $request->file('thumbnail');
        $fullFileName = $files->getClientOriginalName();
        $fileName = pathinfo($fullFileName)['filename'];
        $extension = $files->getClientOriginalExtension();
        $thumbnail = $fileName . '-' . date('YmdHis') . '-' . Str::random(10) . '.' . $extension;
        $files->storeAs('public/blogs/', $thumbnail);

        // Create Blog
        Blog::create([
            'thumbnail' => $thumbnail,
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category,
            'user_id' => Auth::user()->id
        ]);

        return redirect('/blog')->with('success_msg', 'Barang berhasil ditambah');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::all();
        return view('blogs.edit', [
            'blog' => $blog,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Jika tidak ada gambar
        if ($request->file('thumbnail') == null) {
            $request->validate([
                'title' => 'required|min:5|max:80',
                'content' => 'required',
                'category' => 'required',
            ]);

            // Update Blog
            $blog = Blog::findOrFail($id);
            $blog->update([
                'title' => $request->title,
                'content' => $request->content,
                'category_id' => $request->category
            ]);

            return redirect('/blog')->with('success_msg', 'Barang berhasil diubah');
        } else {
            // Validasi
            $request->validate([
                'thumbnail' => 'required',
                'title' => 'required|min:5|max:80',
                'content' => 'required',
                'category' => 'required',
            ]);

            // File Processing
            $files = $request->file('thumbnail');
            $fullFileName = $files->getClientOriginalName();
            $fileName = pathinfo($fullFileName)['filename'];
            $extension = $files->getClientOriginalExtension();
            $thumbnail = $fileName . '-' . date('YmdHis') . '-' . Str::random(10) . '.' . $extension;
            $files->storeAs('public/blogs/', $thumbnail);

            // Update Blog
            $blog = Blog::findOrFail($id);
            if (Storage::exists('public/blogs/' . $blog->thumbnail)) {
                Storage::delete('public/blogs/' . $blog->thumbnail);
            }

            $blog->update([
                'thumbnail' => $thumbnail,
                'title' => $request->title,
                'content' => $request->content,
                'category_id' => $request->category
            ]);

            return redirect('/blog')->with('success_msg', 'Barang berhasil diubah');
        }
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if (Storage::exists('public/blogs/' . $blog->thumbnail)) {
            Storage::delete('public/blogs/' . $blog->thumbnail);
        }
        $blog->delete();

        return redirect('/blog')->with('success_msg', 'Barang berhasil dihapus');
    }
}
