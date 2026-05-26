@if(count($artikel_data) > 0)
    @foreach($artikel_data as $row)
        @php
            // Cek Sumber Gambar (URL vs Fisik)
            if (strpos($row->gambar, 'http') === 0) {
                $bg_image = htmlspecialchars($row->gambar);
            } else {
                $bg_image = "/uploads/" . htmlspecialchars($row->gambar);
            }
        @endphp
        
            <div class="card">
                <div style="width: 100%; aspect-ratio: 21/9; overflow: hidden; position: relative; border-radius: 12px 12px 0 0;">
                    <img src="{{ $bg_image }}" style="
                        position: absolute;
                        width: {{ $row->css_width ?: 100 }}%;
                        height: {{ $row->css_height ?: 100 }}%;
                        left: {{ $row->css_left ?: 0 }}%;
                        top: {{ $row->css_top ?: 0 }}%;
                        max-width: none;
                        object-fit: cover;
                    " alt="{{ htmlspecialchars($row->judul) }}">
                </div>

                <div class="card-body">
                    <h3>{{ htmlspecialchars($row->judul) }}</h3>
                    <p>{{ htmlspecialchars($row->ringkasan) }}</p>
                    <a href="{{ url('artikel/' . $row->id) }}" class="cta-link">Call to action &rarr;</a>
                </div>
            </div>
    @endforeach
@else
    <p style="text-align:center; width:100%; grid-column: 1 / -1;">Belum ada destinasi yang ditambahkan. Silakan login ke panel Admin.</p>
@endif
