@extends('admin.layouts.admin')

@section('title', 'Edit Artikel - Ijen Geopark')

@section('content')
    <!-- Tambahkan Library Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <div class="card">
        <a href="{{ url('admin/kelola_artikel') }}" class="action-btn btn-back" style="background: #555; margin-bottom: 20px;">⬅ Batal & Kembali</a>
        <h2>Edit Artikel</h2>

        @if($errors->any())
            <div style="color:red; margin-bottom:15px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('admin/artikel/' . $artikel->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Judul Destinasi</label>
                <input type="text" name="judul" value="{{ old('judul', $artikel->judul) }}" required>
            </div>
            <div class="form-group">
                <label>Label</label>
                <input type="text" name="label" value="{{ old('label', $artikel->label) }}">
            </div>
            <div class="form-group">
                <label>Ringkasan</label>
                <textarea name="ringkasan" required>{{ old('ringkasan', $artikel->ringkasan) }}</textarea>
            </div>
            <div class="form-group">
                <label>Konten Lengkap</label>
                <textarea name="konten" required>{{ old('konten', $artikel->konten) }}</textarea>
            </div>
            
            <hr style="margin:20px 0; border:1px solid #eee;">
            <h4>Pengaturan Gambar Hero & Posisi Crop</h4>

            @php
                if (strpos($artikel->gambar, 'http') === 0) {
                    $sumber_gambar = htmlspecialchars($artikel->gambar);
                } else {
                    $sumber_gambar = asset("uploads/" . $artikel->gambar);
                }
            @endphp
            
            <div style="background: #e9ecef; padding: 15px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;">
                <label>Gambar & Posisi Crop Saat Ini:</label>
                <div style="width: 100%; max-width: 400px; aspect-ratio: 21/9; overflow: hidden; position: relative; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 4px; background: white;">
                    <img src="{{ $sumber_gambar }}" style="
                    position: absolute;
                    width: {{ $artikel->css_width ?: 100 }}%;
                    height: {{ $artikel->css_height ?: 100 }}%;
                    left: {{ $artikel->css_left ?: 0 }}%;
                    top: {{ $artikel->css_top ?: 0 }}%;
                    max-width: none;
                    object-fit: cover;
                ">
                </div>

                <p style="font-size: 13px; color: #555; margin-bottom: 15px;"><i>Pilih gambar baru (File / URL Cloud) lalu klik tombol Load Preview di bawah untuk mengatur ulang posisi Crop. Biarkan jika tidak ingin mengubah.</i></p>

                <div class="form-group">
                    <label>Opsi 1: Upload File Gambar Baru (Lokal)</label>
                    <input type="file" name="gambar" id="fileInput" accept="image/*">
                </div>
                
                <div style="text-align:center; font-weight:bold; margin: 10px 0;">-- ATAU --</div>
                
                <div class="form-group">
                    <label>Opsi 2: URL Gambar Cloud Saat Ini / Baru</label>
                    <input type="text" name="gambar_url" id="gambar_url" value="{{ old('gambar_url', $artikel->gambar) }}">
                </div>

                <button type="button" id="btnPreview" class="action-btn" style="background: #17a2b8;">Load Preview & Atur Crop</button>
                
                <!-- Tempat Preview Cropper -->
                <div id="cropArea" style="margin-top: 15px; max-height: 400px; display: none;">
                    <img id="imageToCrop" style="max-width: 100%;">
                </div>
                
                <!-- Input tersembunyi dengan default value dari database -->
                <input type="hidden" name="css_width" id="css_width" value="{{ old('css_width', $artikel->css_width) }}">
                <input type="hidden" name="css_height" id="css_height" value="{{ old('css_height', $artikel->css_height) }}">
                <input type="hidden" name="css_left" id="css_left" value="{{ old('css_left', $artikel->css_left) }}">
                <input type="hidden" name="css_top" id="css_top" value="{{ old('css_top', $artikel->css_top) }}">
            </div>
            
            <button type="submit" class="action-btn btn-add" style="font-size: 16px;">Simpan Perubahan</button>
        </form>
    </div>

    <script>
        let cropper;
        const img = document.getElementById('imageToCrop');
        const cropArea = document.getElementById('cropArea');

        function initCropper() {
            if(cropper) cropper.destroy();
            
            img.onload = function() {
                cropper = new Cropper(img, {
                    aspectRatio: 21 / 9, 
                    viewMode: 1,
                    crop: function(event) {
                        const data = event.detail; 
                        const natW = img.naturalWidth;
                        const natH = img.naturalHeight;
                        
                        if(data.width > 0 && data.height > 0) {
                            const cssW = (natW / data.width) * 100;
                            const cssH = (natH / data.height) * 100;
                            const cssL = -(data.x / data.width) * 100;
                            const cssT = -(data.y / data.height) * 100;
                            
                            document.getElementById('css_width').value = cssW;
                            document.getElementById('css_height').value = cssH;
                            document.getElementById('css_left').value = cssL;
                            document.getElementById('css_top').value = cssT;
                        }
                    }
                });
            };
        }

        document.getElementById('btnPreview').addEventListener('click', function() {
            const fileInput = document.getElementById('fileInput');
            let urlInput = document.getElementById('gambar_url').value.trim();
            
            // Cek apakah ada file lokal yang dipilih
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    cropArea.style.display = 'block';
                    initCropper();
                };
                reader.readAsDataURL(fileInput.files[0]);
            } 
            // Jika tidak ada file lokal, pakai URL / Nama File tersimpan
            else if (urlInput) {
                // Jika itu nama file lokal (tidak berawalan http), tambahkan path uploads/ dengan asset()
                if (!urlInput.startsWith('http') && !urlInput.startsWith('/')) {
                    urlInput = '{{ asset("uploads") }}/' + urlInput;
                }
                
                img.src = urlInput;
                cropArea.style.display = 'block';
                initCropper();
            } else {
                alert("Pilih file gambar atau masukkan URL terlebih dahulu!");
            }
        });
    </script>
@endsection
