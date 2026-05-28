@extends('admin.layouts.admin')

@section('title', 'Kelola Artikel - Ijen Geopark')

@section('content')
    <!-- Tambahkan Library Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
            <h2 style="margin: 0;">Kelola Artikel</h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="text" id="search-kelola" placeholder="Cari artikel..." style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; outline: none; min-width: 200px;">
                <a href="#form-tambah" class="action-btn btn-add" style="margin: 0;">+ Tambah Artikel Baru</a>
            </div>
        </div>

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
            <tr class="artikel-row" data-id="{{ $row->id }}">
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
            <h4>Pengaturan Gambar Hero & Posisi Crop</h4>
            
            <div style="background: #e9ecef; padding: 15px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;">
                <p style="font-size: 13px; color: #555; margin-bottom: 15px;"><i>Pilih gambar (File / URL Cloud) lalu klik tombol Load Preview di bawah untuk mengatur posisi Crop.</i></p>

                <div class="form-group">
                    <label>Opsi 1: Upload File Gambar (Lokal)</label>
                    <input type="file" name="gambar" id="fileInputTambah" accept="image/*">
                </div>
                
                <div style="text-align:center; font-weight:bold; margin: 10px 0;">-- ATAU --</div>
                
                <div class="form-group">
                    <label>Opsi 2: Masukkan URL Gambar (http/https)</label>
                    <input type="text" name="gambar_url" id="gambarUrlTambah" placeholder="https://example.com/image.jpg">
                </div>

                <button type="button" id="btnPreviewTambah" class="action-btn" style="background: #17a2b8;">Load Preview & Atur Crop</button>
                
                <!-- Tempat Preview Cropper -->
                <div id="cropAreaTambah" style="margin-top: 15px; max-height: 400px; display: none;">
                    <img id="imageToCropTambah" style="max-width: 100%;">
                </div>
                
                <!-- Input tersembunyi untuk nilai crop -->
                <input type="hidden" name="css_width" id="cssWidthTambah" value="100">
                <input type="hidden" name="css_height" id="cssHeightTambah" value="100">
                <input type="hidden" name="css_left" id="cssLeftTambah" value="0">
                <input type="hidden" name="css_top" id="cssTopTambah" value="0">
            </div>

            <button type="submit">Simpan Artikel</button>
        </form>
    </div>

    <script>
        let cropperTambah;
        const imgTambah = document.getElementById('imageToCropTambah');
        const cropAreaTambah = document.getElementById('cropAreaTambah');

        function initCropperTambah() {
            if(cropperTambah) cropperTambah.destroy();
            
            imgTambah.onload = function() {
                cropperTambah = new Cropper(imgTambah, {
                    aspectRatio: 21 / 9, 
                    viewMode: 1,
                    crop: function(event) {
                        const data = event.detail; 
                        const natW = imgTambah.naturalWidth;
                        const natH = imgTambah.naturalHeight;
                        
                        if(data.width > 0 && data.height > 0) {
                            const cssW = (natW / data.width) * 100;
                            const cssH = (natH / data.height) * 100;
                            const cssL = -(data.x / data.width) * 100;
                            const cssT = -(data.y / data.height) * 100;
                            
                            document.getElementById('cssWidthTambah').value = cssW;
                            document.getElementById('cssHeightTambah').value = cssH;
                            document.getElementById('cssLeftTambah').value = cssL;
                            document.getElementById('cssTopTambah').value = cssT;
                        }
                    }
                });
            };
        }

        document.getElementById('btnPreviewTambah').addEventListener('click', function() {
            const fileInput = document.getElementById('fileInputTambah');
            let urlInput = document.getElementById('gambarUrlTambah').value.trim();
            
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgTambah.src = e.target.result;
                    cropAreaTambah.style.display = 'block';
                    initCropperTambah();
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else if (urlInput) {
                imgTambah.src = urlInput;
                cropAreaTambah.style.display = 'block';
                initCropperTambah();
            } else {
                alert("Pilih file gambar atau masukkan URL terlebih dahulu!");
            }
        });

        // --- LOGIKA PENCARIAN FUSE.JS UNTUK TABEL ---
        document.addEventListener('DOMContentLoaded', function() {
            const articlesData = @json($artikel);
            
            const fuseOptions = {
                keys: ['judul', 'label', 'ringkasan'],
                threshold: 0.3
            };
            // Pastikan Fuse sudah terload dari CDN di bawah
            if (typeof Fuse !== 'undefined') {
                const fuse = new Fuse(articlesData, fuseOptions);
                const searchInput = document.getElementById('search-kelola');
                const rowElements = Array.from(document.querySelectorAll('.artikel-row'));
                
                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value.trim();
                    
                    if (query === '') {
                        rowElements.forEach(row => row.style.display = ''); // Kembalikan ke display default table-row
                        return;
                    }
                    
                    const results = fuse.search(query);
                    const matchedIds = results.map(result => result.item.id.toString());
                    
                    rowElements.forEach(row => {
                        const rowId = row.getAttribute('data-id');
                        if (matchedIds.includes(rowId)) {
                            row.style.display = ''; // Tampilkan
                        } else {
                            row.style.display = 'none'; // Sembunyikan
                        }
                    });
                });
            }
        });
    </script>
    <!-- Tambahkan Library Fuse.js -->
    <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js"></script>
@endsection
