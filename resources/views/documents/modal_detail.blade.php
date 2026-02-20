<!-- Modal Detail Dokumen -->
<div class="modal fade" id="documentModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content rounded-lg shadow-lg">
      <div class="modal-body p-0">
        <div class="row no-gutters">

          <!-- PREVIEW -->
          <div class="col-md-8 p-3">
            <h5 class="font-weight-bold mb-3" id="docTitle"></h5>

            {{-- <div class="embed-responsive embed-responsive-4by3 border rounded-lg">
              <embed id="docEmbed" type="application/pdf" class="embed-responsive-item">
            </div> --}}

            <div class="embed-responsive embed-responsive-4by3 border rounded-lg">
              <iframe
                id="docEmbed"
                class="embed-responsive-item"
                src=""
                type="application/pdf"
                frameborder="0">
              </iframe>
            </div>


            <div class="mt-2">
              <small class="text-muted">
                Jika preview tidak muncul,
                <a href="#" id="docPreviewLink" target="_blank" class="font-weight-bold">
                  klik di sini
                </a>
              </small>
            </div>
          </div>

          <!-- DETAIL -->
          <div class="col-md-4 p-4 position-relative">
            <div class="d-flex justify-content-between mb-3">
              <span class="badge badge-warning" id="docCategory"></span>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <ul class="list-unstyled" id="docDetails"></ul>
            <p class="text-muted small" id="docDescription"></p>

            <!-- ACTION BUTTON -->
            <div class="position-absolute" style="bottom:15px; right:15px;">
              <div class="d-flex gap-2" id="docActionButtons">
                <a href="#" id="docEditBtn" class="btn btn-warning">
                  Edit Dokumen
                </a>
                <button class="btn btn-secondary" data-dismiss="modal">
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
$(function () {
    // Klik tombol eye
    $(document).on('click', '.showDocument', function () {
        let id = $(this).data('id');
        
        $.get("/admin/dokumen/" + id, function (data) {


              $('#docTitle').text(data.title.toUpperCase());

              let src = '';

              if (data.embed_link) {
                  // 🌍 Cloud / Google Drive / S3 / dll
                  src = data.embed_link;
              } else if (data.has_file) {
                  // 🏠 File server → lewat controller
                  src = `/admin/dokumen/${data.slug}/view`;
              }

              $('#docEmbed').attr('src', src + '#toolbar=0');
              $('#docPreviewLink').attr('href', src);


              $('#docCategory').text(data.type?.category?.name ?? '-');

              $('#docDetails').html(`
                  <li><strong>Kategori</strong> : ${data.type?.name ?? '-'}</li>
                  <li><strong>Bidang</strong> : ${data.unit?.name ?? '-'}</li>
                  <li><strong>Tahun</strong> : ${data.upload_date_year ?? '-'}</li>
                  <li><strong>Tanggal Unggah</strong> : ${data.upload_date_formatted ?? '-'}</li>
                  <li><strong>Diunggah Oleh</strong> : ${data.uploader?.name ?? '-'}</li>
                  <li><strong>Status</strong> : ${data.status}</li>
              `);

              $('#docDescription').text(data.description ?? 'Tidak ada deskripsi');
              $('#docPreviewLink').attr('href', data.embed_link ?? '#');

              // ==========================
              // ACTION BUTTON LOGIC
              // ==========================
              let actionHtml = '';

              @if(auth()->user()->role === 'administrator')

                  // ADMIN HANYA PROSES SUBMITTED
                  if (data.status === 'submitted') {
                      actionHtml = `
                          <form method="POST" action="{{ url('admin/dokumen') }}/${data.id}/approve" style="display:inline;">
                              @csrf
                              <button class="btn btn-success">Setujui</button>
                          </form>

                          <form method="POST" action="{{ url('admin/dokumen') }}/${data.id}/reject" style="display:inline;">
                              @csrf
                              <button class="btn btn-danger">Tolak</button>
                          </form>

                          <button class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                      `;
                  } else {
                      actionHtml = `
                          <button class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                      `;
                  }

              @elseif(auth()->user()->role === 'editor')

                  // EDITOR BISA SUBMIT DRAFT & REJECTED
                  if (data.status === 'draft' || data.status === 'rejected') {
                      actionHtml = `
                          <a href="{{ url('admin/dokumen') }}/${data.id}/edit" class="btn btn-warning">
                              Edit
                          </a>

                          <form method="POST" action="{{ url('admin/dokumen') }}/${data.id}/submit" style="display:inline;">
                              @csrf
                              <button class="btn btn-primary">Submit</button>
                          </form>

                          <button class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                      `;
                  } else {
                      actionHtml = `
                          <button class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                      `;
                  }

              @else

                  actionHtml = `
                      <button class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                  `;

              @endif

              $('#docActionButtons').html(actionHtml);


              $('#documentModal').modal('show');
      });
    });

    // Event setelah modal ditutup → reset isi
    $('#documentModal').on('hidden.bs.modal', function () {
        $('#docTitle').text('');
        $('#docEmbed').attr('src', '');
        $('#docCategory').text('');
        $('#docDetails').html('');
        $('#docDescription').text('');
        $('#docEditBtn').attr('href', '#');
        $('#docPreviewLink').attr('href', '#');
    });

    // Contoh tambahan: klik "Edit" langsung ke halaman edit
    $(document).on('click', '#docEditBtn', function (e) {
        // kalau mau AJAX edit, bisa preventDefault di sini
        // e.preventDefault();
        // lalu tampilkan form edit di modal lain
    });
});
</script>
@endpush

