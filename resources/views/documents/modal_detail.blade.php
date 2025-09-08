<!-- Modal Detail Dokumen -->
<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content rounded-lg shadow-lg">
      <div class="modal-body p-0">
        <div class="row no-gutters">
          <!-- Kolom Kiri: Preview Dokumen -->
          <div class="col-md-8 p-3">
            <h5 class="font-weight-bold mb-3" id="docTitle"></h5>
            <div class="border rounded-lg" style="height: 500px;">
              <embed id="docEmbed" src="" type="application/pdf" width="100%" height="100%">
            </div>
          </div>

          <!-- Kolom Kanan: Detail Dokumen -->
          <div class="col-md-4 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="badge badge-warning px-3 py-2" id="docCategory"></span>
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
                  <i class="fas fa-edit"></i> Edit
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                  <i class="fas fa-arrow-left"></i> Kembali
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
            <li><strong>Diunggah</strong> : ${data.upload_date_formatted ?? '-'}</li>
            <li><strong>Tipe</strong> : PDF</li>
            <li><strong>Ukuran</strong> : - </li>
        `);

        $('#docDescription').text(data.description ?? 'Tidak ada deskripsi');
        $('#docEditBtn').attr('href', "{{ url('documents') }}/" + id + "/edit");

        $('#documentModal').modal('show');
    });
});
</script>
@endpush