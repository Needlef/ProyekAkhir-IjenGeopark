@extends('admin.layouts.admin')

@section('title', 'Kelola Artikel - Ijen Geopark')

@section('content')
    <div class="card">
        <h2>Kelola Artikel</h2>
        <a href="#form-tambah" class="action-btn btn-add">+ Tambah Artikel Baru</a>

        <!-- Daftar Artikel -->
        <table>
            <tr>
                <th>ID</th>
                <th>Gambar Preview</th>
                <th>Judul</th>
                <th>Label</th>
                <th>Aksi</th>
            </tr>
            @foreach($artikel as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td style="width: 120px; text-align: center;">
                    @php
                        if (strpos($row->gambar, 'http') === 0) {
                            $bg_image = htmlspecialchars($row->gambar);
                        } else {
                            $bg_image = asset("uploads/" . $row->gambar);
                        }
                    @endphp
                    <div style="width: 100px; aspect-ratio: 21/9; overflow: hidden; position: relative; border-radius: 4px; display: inline-block;">
                        <img src="{{ $bg_image }}" style="
                            position: absolute;
                            width: {{ $row->css_width ?: 100 }}%;
                            height: {{ $row->css_height ?: 100 }}%;
                            left: {{ $row->css_left ?: 0 }}%;
                            top: {{ $row->css_top ?: 0 }}%;
                            max-width: none;
                            object-fit: cover;
                        ">
                    </div>
                </td>
                <td>{{ htmlspecialchars($row->judul) }}</td>
                <td>{{ htmlspecialchars($row->label) }}</td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ url('admin/artikel/'.$row->id.'/edit') }}" class="action-btn btn-edit">Edit</a>
                        <form action="{{ url('admin/artikel/'.$row->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-delete">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if(count($artikel) == 0)
            <tr><td colspan="5" style="text-align:center;">Belum ada artikel.</td></tr>
            @endif
        </table>
    </div>

    <!-- Form Tambah -->
    <div class="card" id="form-tambah" style="margin-top: 30px;">
        <h3>Tambah Artikel Baru</h3>
        
        @if($errors->any())
            <div style="color:red; margin-bottom:15px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('admin/artikel') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Judul Destinasi</label>
                <input type="text" name="judul" required>
            </div>
            <div class="form-group">
                <label>Label (Kategori)</label>
                <input type="text" name="label">
            </div>
            <div class="form-group">
                <label>Ringkasan Pendek</label>
                <textarea name="ringkasan" style="height: 60px;"></textarea>
            </div>
            <div class="form-group">
                <label>Konten Lengkap</label>
                <textarea name="konten" style="height: 150px;"></textarea>
            </div>

            <hr style="margin:20px 0; border:1px solid #eee;">
            <h4>Pengaturan Gambar Hero</h4>
            
            <div class="form-group">
                <label>Opsi 1: Upload File Gambar</label>
                <input type="file" name="gambar" accept="image/*">
            </div>
            <div style="text-align:center; font-weight:bold; margin: 10px 0;">-- ATAU --</div>
            <div class="form-group">
                <label>Opsi 2: Masukkan URL Gambar (http/https)</label>
                <input type="text" name="gambar_url" placeholder="https://example.com/image.jpg">
            </div>

            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;">
                    <label>Width (%)</label>
                    <input type="number" name="css_width" value="100" step="0.001">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Height (%)</label>
                    <input type="number" name="css_height" value="100" step="0.001">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Left (%)</label>
                    <input type="number" name="css_left" value="0" step="0.001">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Top (%)</label>
                    <input type="number" name="css_top" value="0" step="0.001">
                </div>
            </div>

            <button type="submit">Simpan Artikel</button>
        </form>
    </div>
@endsection
