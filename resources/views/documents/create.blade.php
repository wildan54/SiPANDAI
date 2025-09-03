@extends('layouts.adminlte')

@section('title', 'Tambah Dokumen')

@section('content')
	<!-- Tambah Dokumen -->
	<section id="tambah-dokumen" class="content tab-pane fade">
	  <div class="container-fluid">
		<h1 class="my-3">Tambah Dokumen</h1>
		<div class="row">
		  <div class="col-md-12"><!-- Full width -->
			<div class="card card-primary">
			  <div class="card-header">
				<h3 class="card-title">Form Tambah Dokumen</h3>
			  </div>
			  <form>
				<div class="card-body">
				  <div class="form-group">
					<label for="title">Judul Dokumen</label>
					<input type="text" class="form-control" id="title" placeholder="Masukkan judul dokumen">
				  </div>
				  <div class="form-group">
					<label for="description">Deskripsi</label>
					<textarea class="form-control" id="description" rows="3" placeholder="Masukkan deskripsi"></textarea>
				  </div>
				  
				<div class="form-group">
				  <label>Sumber File</label>
				  <div class="form-check">
					<input class="form-check-input" type="radio" name="file_source" id="uploadOption" value="upload" checked>
					<label class="form-check-label" for="uploadOption">
					  Upload File
					</label>
				  </div>
				  <div class="form-check">
					<input class="form-check-input" type="radio" name="file_source" id="embedOption" value="embed">
					<label class="form-check-label" for="embedOption">
					  Masukkan Embed dari Cloud
					</label>
				  </div>

				  <!-- Upload File -->
				  <div id="uploadFileDiv" class="mt-2">
					<input type="file" class="form-control" id="file">
				  </div>

				  <!-- Embed File -->
				  <div id="embedFileDiv" class="mt-2" style="display: none;">
					<input type="text" class="form-control" id="file_embed" placeholder="Masukkan URL/embed link file dari cloud">
				  </div>
				</div>
				
				  <div class="form-group">
					<label for="id_type">Tipe Dokumen</label>
					<select id="id_type" class="form-control">
					  <option value="">-- Pilih Tipe Dokumen --</option>
					  <option value="1">Surat Keputusan</option>
					  <option value="2">Laporan</option>
					  <option value="3">Notulen</option>
					</select>
				  </div>
				  <div class="form-group">
					<label for="id_unit">Unit</label>
					<select id="id_unit" class="form-control">
					  <option value="">-- Pilih Unit --</option>
					  <option value="1">Keuangan</option>
					  <option value="2">SDM</option>
					  <option value="3">Umum</option>
					</select>
				  </div>
				  <div class="form-group">
					<label for="year">Tahun</label>
					<input type="number" class="form-control" id="year" placeholder="contoh: 2025">
				  </div>
				  <div class="form-group">
					<label for="slug">Slug</label>
					<input type="text" class="form-control" id="slug" placeholder="contoh: surat-keputusan-2025">
				  </div>
				  <div class="form-group">
					<label for="meta_title">Meta Title</label>
					<input type="text" class="form-control" id="meta_title" placeholder="Masukkan meta title (SEO)">
				  </div>
				  <div class="form-group">
					<label for="meta_description">Meta Description</label>
					<textarea class="form-control" id="meta_description" rows="3" placeholder="Masukkan meta description (SEO)"></textarea>
				  </div>
				</div>
				<div class="card-footer">
				  <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
				  <button type="reset" class="btn btn-secondary">Reset</button>
				</div>
			  </form>
			</div>
		  </div>
		</div>
	  </div>
	</section>
@endsection
