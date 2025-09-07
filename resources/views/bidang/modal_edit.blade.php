@foreach ($units as $unit)
<div class="modal fade" id="editModal{{ $unit->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $unit->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="editModalLabel{{ $unit->id }}">Edit Bidang</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('bidang.update', $unit->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="name{{ $unit->id }}">Nama <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name{{ $unit->id }}" class="form-control"
                   value="{{ old('name', $unit->name) }}" required>
          </div>
          <div class="form-group">
            <label for="slug{{ $unit->id }}">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" id="slug{{ $unit->id }}" class="form-control"
                   value="{{ old('slug', $unit->slug) }}" required>
            <small class="form-text text-muted">
              “Slug” adalah versi nama yang ramah URL. Biasanya huruf kecil, angka, dan tanda hubung.
            </small>
          </div>
          <div class="form-group">
            <label for="description{{ $unit->id }}">Deskripsi</label>
            <textarea name="description" id="description{{ $unit->id }}" rows="3" class="form-control">{{ old('description', $unit->description) }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach