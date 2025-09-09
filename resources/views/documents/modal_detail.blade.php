<!-- Modal Detail Dokumen -->
<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content rounded-lg shadow-lg">
      <div class="modal-body p-0">
        <div class="row no-gutters">
          <!-- Kolom Kiri: Preview Dokumen -->
          <div class="col-md-8 p-3">
            <h5 class="font-weight-bold mb-3" id="docTitle"></h5>

            <!-- Responsive embed PDF -->
            <div class="embed-responsive embed-responsive-4by3 border rounded-lg">
              <embed id="docEmbed" src="" type="application/pdf" class="embed-responsive-item">
            </div>

            <!-- Tombol fallback preview (selalu tampil) -->
            <div class="mt-2" id="docPreviewWrapper">
              <small class="text-muted">
                Jika preview dokumen tidak muncul, silakan 
                <a href="#" id="docPreviewLink" target="_blank" class="font-weight-bold text-primary">
                  preview melalui sini
                </a>.
              </small>
            </div>
          </div>

          <!-- Kolom Kanan: Detail Dokumen -->
          <div class="col-md-4 p-4 position-relative mt-3 mt-md-0">
              <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="w-100 pr-2">
                      <span class="badge badge-warning d-inline-block text-truncate" id="docCategory" style="max-width: 100%;">
                          Kategori
                      </span>
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <ul class="list-unstyled" id="docDetails">
                  <!-- akan diisi lewat JS -->
              </ul>

              <p class="text-muted small" id="docDescription"></p>

              <!-- Tombol pojok kanan bawah -->
              <div class="position-absolute" style="bottom: 15px; right: 15px;">
                  <div class="d-flex">
                      <a href="#" id="docEditBtn" class="btn btn-warning mr-2">
                          Edit
                      </a>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">
                          Kembali
                      </button>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).on('click', '.showDocument', function () {
    let id = $(this).data('id');

    $.get("{{ url('dokumen') }}/" + id, function (data) {
        // isi modal dengan data JSON
        $('#docTitle').text(data.title.toUpperCase());
        $('#docEmbed').attr('src', data.embed_link + "#toolbar=0");
        $('#docCategory').text(data.type?.name ?? '-');

        $('#docDetails').html(`
            <li><strong>Kategori</strong> : ${data.type?.name ?? '-'}</li>
            <li><strong>Bidang</strong> : ${data.unit?.name ?? '-'}</li>
            <li><strong>Tahun</strong> : ${data.upload_date_year ?? '-'}</li>
            <li><strong>Diunggah</strong> : ${data.upload_date_formatted ?? '-'}</li><br>
        `);

        $('#docDescription').text(data.description ?? 'Tidak ada deskripsi');
        $('#docEditBtn').attr('href', "{{ url('dokumen') }}/" + id + "/edit");

        // set link preview alternatif
        $('#docPreviewLink').attr('href', data.embed_link ?? '#');

        $('#documentModal').modal('show');
    });
});
</script>
@endpush
