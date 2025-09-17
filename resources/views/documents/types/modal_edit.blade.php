@foreach($types as $type)
<div class="modal fade" id="editModal{{ $type->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $type->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-primary">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="editModalLabel{{ $type->id }}">Edit Tipe Dokumen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form action="{{ route('documents.types.update', $type->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="name{{ $type->id }}">Nama <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name{{ $type->id }}" value="{{ $type->name }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="slug{{ $type->id }}">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" id="slug{{ $type->id }}" value="{{ $type->slug }}" class="form-control" required>
            <small class="form-text text-muted">
              “Slug” adalah versi nama yang ramah URL. Biasanya semuanya huruf kecil dan hanya mengandung huruf, angka, serta tanda hubung. Jika dikosongkan, slug akan dibuat otomatis berdasarkan nama tipe.
            </small>
          </div>
          <div class="form-group">
            <label for="description{{ $type->id }}">Deskripsi</label>
            <textarea name="description" id="description{{ $type->id }}" rows="3" class="form-control">{{ $type->description }}</textarea>
          </div>
          <div class="form-group">
            <label for="document_category_id{{ $type->id }}">Kategori Dokumen</label>
            <select name="document_category_id" id="document_category_id{{ $type->id }}" class="form-control">
              <option value="">-- Pilih Kategori --</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $type->document_category_id == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach