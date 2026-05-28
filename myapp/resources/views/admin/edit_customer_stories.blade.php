@extends('admin.layouts.admin')

@section('title', 'Edit Customer Story - Ijen Geopark')

@section('content')
    <div class="card">
        <a href="{{ url('admin/kelola_customer_stories') }}" class="action-btn btn-back" style="background: #555; margin-bottom: 20px;">⬅ Batal & Kembali</a>
        <h2>Edit Customer Story</h2>

        @if($errors->any())
            <div style="color:red; margin-bottom:15px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('admin/customer_stories/' . $story->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Judul/Quote</label>
                <input type="text" name="title" value="{{ old('title', $story->title) }}" placeholder="Contoh: Pengalaman Tak Terlupakan!" required>
            </div>

            <div class="form-group">
                <label>Deskripsi/Cerita</label>
                <textarea name="description" placeholder="Cerita dari pengunjung..." required>{{ old('description', $story->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Nama Pengunjung</label>
                <input type="text" name="visitor_name" value="{{ old('visitor_name', $story->visitor_name) }}" placeholder="Contoh: Budi Santoso" required>
            </div>

            <hr style="margin:20px 0; border:1px solid #eee;">
            <h4>Pengaturan Gambar</h4>

            @php
                if ($story->image && strpos($story->image, 'http') === 0) {
                    $sumber_gambar = htmlspecialchars($story->image);
                } else {
                    $sumber_gambar = $story->image ? asset("uploads/" . $story->image) : 'https://via.placeholder.com/300?text=No+Image';
                }
            @endphp
            
            <div style="background: #e9ecef; padding: 15px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;">
                <label>Gambar Saat Ini:</label>
                <div style="width: 100%; max-width: 300px; height: 200px; overflow: hidden; position: relative; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 4px; background: white;">
                    <img src="{{ $sumber_gambar }}" style="
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                ">
                </div>

                <p style="font-size: 13px; color: #555; margin-bottom: 15px;"><i>Pilih gambar baru (File / URL Cloud) untuk mengubah. Biarkan kosong jika tidak ingin mengubah.</i></p>

                <div class="form-group">
                    <label>Opsi 1: Upload File Gambar Baru (Lokal)</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                
                <div style="text-align:center; font-weight:bold; margin: 10px 0;">-- ATAU --</div>
                
                <div class="form-group">
                    <label>Opsi 2: URL Gambar Cloud</label>
                    <input type="text" name="image_url" value="{{ old('image_url', $story->image) }}" placeholder="https://example.com/image.jpg">
                </div>
            </div>
            
            <button type="submit" class="action-btn btn-add" style="font-size: 16px;">Simpan Perubahan</button>
        </form>
    </div>
@endsection
