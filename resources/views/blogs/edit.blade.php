@extends('layouts.app')

@section('title', 'Edit Barang | PT Musang')

@section('content')
    <div class="container mt-3">
        <div class="col-md-7 bg-light p-4 rounded">
            <h4>Edit Barang</h4>
            <p>Atur barang-barang disini!
            </p>
            <hr>

            <form action="{{ route('updateBlog', $blog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Foto Barang</label>
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                        placeholder="Thumbnail Blog..." name="thumbnail" value="{{ old('thumbnail') ? old('thumbnail') : $blog->thumbnail }}">
                    @error('thumbnail')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Judul Barang</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Judul Blog..."
                        name="title" value="{{ old('title') ? old('title') : $blog->title }}">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Konten Barang</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" placeholder="Konten Blog..." name="content"
                        rows="5">
                    {{ old('content') ? old('content') : $blog->content }}
                </textarea>
                    @error('content')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Kategori Barang</label>
                    <select class="form-control @error('category') is-invalid @enderror" name="category">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
