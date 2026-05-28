@extends('admin.layouts.admin')

@section('title', 'Kelola Customer Stories - Ijen Geopark')

@section('content')
    <div class="card">
        <h2>Kelola Customer Stories</h2>
        <a href="#form-tambah" class="action-btn btn-add">+ Tambah Customer Story Baru</a>

        <!-- Daftar Customer Stories -->
        <table>
            <tr>
                <th>ID</th>
                <th>Gambar Preview</th>
                <th>Judul/Quote</th>
                <th>Nama Pengunjung</th>
                <th>Aksi</th>
            </tr>
            @foreach($stories as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td style="width: 120px; text-align: center;">
                    @php
                        if ($row->image && strpos($row->image, 'http') === 0) {
                            $bg_image = htmlspecialchars($row->image);
                        } else {
                            $bg_image = $row->image ? asset("uploads/" . $row->image) : 'https://via.placeholder.com/100?text=No+Image';
                        }
                    @endphp
                    <div style="width: 100px; height: 100px; overflow: hidden; position: relative; border-radius: 4px; display: inline-block;">
                        <img src="{{ $bg_image }}" style="
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        ">
                    </div>
                </td>
                <td>{{ htmlspecialchars(substr($row->title, 0, 50)) }}{{ strlen($row->title) > 50 ? '...' : '' }}</td>
                <td>{{ htmlspecialchars($row->visitor_name) }}</td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ url('admin/customer_stories/'.$row->id.'/edit') }}" class="action-btn btn-edit">Edit</a>
                        <form action="{{ url('admin/customer_stories/'.$row->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Yakin ingin menghapus customer story ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-delete">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if(count($stories) == 0)
            <tr><td colspan="5" style="text-align:center;">Belum ada customer story.</td></tr>
            @endif
        </table>
    </div>

    <hr style="margin: 30px 0; border: 1px solid #eee;">

    <!-- Form Tambah Customer Story -->
    <div class="card" id="form-tambah">
        <h2>Tambah Customer Story Baru</h2>
        
        @if($errors->any())
            <div style="color:red; margin-bottom:15px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('admin/customer_stories') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Judul/Quote</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Pengalaman Tak Terlupakan!" required>
            </div>

            <div class="form-group">
                <label>Deskripsi/Cerita</label>
                <textarea name="description" placeholder="Cerita dari pengunjung..." required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Nama Pengunjung</label>
                <input type="text" name="visitor_name" value="{{ old('visitor_name') }}" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="form-group">
                <label>Opsi 1: Upload Gambar (Lokal)</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div style="text-align:center; font-weight:bold; margin: 10px 0;">-- ATAU --</div>

            <div class="form-group">
                <label>Opsi 2: URL Gambar Cloud</label>
                <input type="text" name="image_url" placeholder="https://example.com/image.jpg">
            </div>

            <button type="submit" class="action-btn btn-add" style="font-size: 16px;">Simpan Customer Story</button>
        </form>
    </div>
@endsection
