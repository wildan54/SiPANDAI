<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editUserForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="edit_name">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" id="edit_name" required>
          </div>
          <div class="form-group">
            <label for="edit_username">Username</label>
            <input type="text" name="username" class="form-control" id="edit_username">
          </div>
          <div class="form-group">
            <label for="edit_email">Email</label>
            <input type="email" name="email" class="form-control" id="edit_email" required>
          </div>
          <div class="form-group">
            <label for="edit_role">Role</label>
            <select name="role" class="form-control" id="edit_role" required>
              <option value="">-- Pilih Role --</option>
              <option value="administrator">Administrator</option>
              <option value="editor">Editor</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_password">Password <small>(kosongkan jika tidak ingin diganti)</small></label>
            <input type="password" name="password" class="form-control" id="edit_password">
          </div>
          <div class="form-group">
            <label for="edit_password_confirmation">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="edit_password_confirmation">
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


@push('scripts')
<script>
$(document).ready(function() {
  $('.edit-user-btn').on('click', function() {
    let userId = $(this).data('id');
    let name = $(this).data('name');
    let username = $(this).data('username');
    let email = $(this).data('email');
    let role = $(this).data('role'); // <-- Tambahkan ini

    // Set action form
    $('#editUserForm').attr('action', '/pengguna/' + userId);

    // Isi form
    $('#edit_name').val(name);
    $('#edit_username').val(username);
    $('#edit_email').val(email);
    $('#edit_role').val(role); // <-- Tambahkan ini
    $('#edit_password').val('');
    $('#edit_password_confirmation').val('');

    // Tampilkan modal
    $('#editUserModal').modal('show');
  });
});
</script>
@endpush