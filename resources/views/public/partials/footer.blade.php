<footer class="text-white pt-5 pb-4 mt-5 px-5 rounded-top-3 p-3" style="background-color:#030F6B;">
    
    <div class="row gy-4 gx-5">
      <!-- Logo & Deskripsi -->
      <div class="col-lg-6">
        <div class="d-flex align-items-center mb-3">
          <a href="{{ route('public.home') }}" class="text-decoration-none">
            <img src="{{ asset('images/Logo-SiPANDAI.png') }}" 
                alt="Portal SiPANDAI" 
                class="img-fluid" 
                style="max-height: 50px;">
          </a>
        </div>
        <p class="small mb-0">
          SiPANDAI adalah portal milik Dinas Pekerjaan Umum, Perumahan dan Kawasan Permukiman 
          Kabupaten Ponorogo. Portal ini mempublikasikan dokumen-dokumen dinas yang dapat diakses oleh publik.
        </p>
      </div>

      <!-- Link & Sosial Media -->
      <div class="col-lg-3">
        <h6 class="fw-bold text-warning mb-3">Link Terkait</h6>
        <ul class="list-unstyled small">
          <li>
            <a href="https://dpupkp.ponorogo.go.id" target="_blank" rel="noopener" 
               class="text-white text-decoration-none d-flex align-items-center">
              <i class="bi bi-globe me-2 text-warning"></i> Official Website
            </a>
          </li>
          <li>
            <a href="https://lapor.go.id/" target="_blank" rel="noopener" 
               class="text-white text-decoration-none d-flex align-items-center">
              <i class="bi bi-chat-dots me-2 text-warning"></i> Lapor
            </a>
          </li>
        </ul>

        <h6 class="fw-bold text-warning mb-3">Sosial Media</h6>
        <div class="d-flex gap-2 mt-3">
          <!-- YouTube -->
          <a href="https://www.youtube.com/@dpupkpponorogo" aria-label="YouTube DPUPKP Ponorogo"
             class="bg-white rounded-circle d-flex align-items-center justify-content-center"
             style="width:40px; height:40px; color: #FF0000;">
            <i class="bi bi-youtube"></i>
          </a>
          <!-- Instagram -->
          <a href="https://www.instagram.com/dpupkp.png?utm_source=ig_web_button_share_sheet&igsh=cWpmb25pdm05NXhv" 
             aria-label="Instagram DPUPKP Ponorogo"
             class="bg-white rounded-circle d-flex align-items-center justify-content-center"
             style="width:40px; height:40px; color: #C13584;">
            <i class="bi bi-instagram"></i>
          </a>
          <!-- TikTok -->
          <a href="https://www.tiktok.com/@dpupkp" aria-label="TikTok DPUPKP Ponorogo"
             class="bg-white rounded-circle d-flex align-items-center justify-content-center"
             style="width:40px; height:40px; color: #000000;">
            <i class="bi bi-tiktok"></i>
          </a>
        </div>
      </div>

      <!-- Kontak -->
      <div class="col-lg-3">
        <h6 class="fw-bold text-warning mb-3">Kontak</h6>
        <ul class="list-unstyled small mb-0">
          <li>Sekretariat Dinas PUPKP Kabupaten Ponorogo</li>
          <li>Jl. Gajahmada No. 67 Bangunsari Ponorogo</li>
          <li>Telp. (0352) 481632</li>
        </ul>
      </div>
    </div>

    <!-- Footer Bottom -->
    <hr class="border-secondary my-4">
    <div class="text-center small">
      © {{ date('Y') }} All Rights Reserved. Developed & Maintained by 
      <span class="text-warning fw-bold">ITC DPUPKP</span>
    </div>

</footer>
