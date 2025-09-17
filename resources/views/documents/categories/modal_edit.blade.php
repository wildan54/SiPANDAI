@foreach ($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">
          Edit Kategori
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('documents.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="name{{ $category->id }}">Nama <span class="text-danger">*</span></label>
            <input type="text" 
                   name="name" 
                   id="name{{ $category->id }}" 
                   class="form-control" 
                   value="{{ $category->name }}" 
                   required>
          </div>
          <div class="form-group">
            <label for="slug{{ $category->id }}">Slug <span class="text-danger">*</span></label>
            <input type="text" 
                   name="slug" 
                   id="slug{{ $category->id }}" 
                   class="form-control" 
                   value="{{ $category->slug }}" 
                   required>
            <small class="form-text text-muted">
              “Slug” adalah versi nama yang ramah URL. Biasanya semuanya huruf kecil dan hanya mengandung huruf, angka, serta tanda hubung.
            </small>
          </div>
          <div class="form-group">
            <label for="description{{ $category->id }}">Deskripsi</label>
            <textarea name="description" 
                      id="description{{ $category->id }}" 
                      rows="3" 
                      class="form-control">{{ $category->description }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach