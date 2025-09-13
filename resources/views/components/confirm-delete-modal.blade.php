@props([
    'id' => 'confirmDeleteModal',
    'title' => 'Konfirmasi Hapus',
    'text' => 'Anda Yakin Ingin Menghapus',
    'name' => 'item',
    'action' => '#',
    'hasMoveOption' => false,
    'moveOptions' => [],
])

<!-- Modal -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="{{ $action }}">
        @csrf
        @method('DELETE')

        <div class="modal-body">
        @if($hasMoveOption)
            <div class="form-check">
                <input class="form-check-input" type="radio" name="action" id="{{ $id }}Delete" value="delete" checked>
                <label class="form-check-label" for="{{ $id }}Delete">
                Hapus semua dokumen terkait
                </label>
            </div>

            <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="action" id="{{ $id }}Move" value="move">
                <label class="form-check-label" for="{{ $id }}Move">
                Pindahkan dokumen ke:
                </label>
            </div>
            <select name="target_id" class="form-control mt-2">
                <option value="">-- pilih tujuan --</option>
                @foreach($moveOptions as $option)
                <option value="{{ $option->id }}">{{ $option->name }}</option>
                @endforeach
            </select>
        @else
            <p class="text-danger font-italic">{{ $text }}</p><br>
            <p>Anda Yakin Ingin Menghapus <strong>{{ $name }}</strong>?</p>
        @endif
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Konfirmasi</button>
        </div>
      </form>

    </div>
  </div>
</div>