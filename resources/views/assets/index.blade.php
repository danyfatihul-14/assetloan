@extends('layouts.app')

@section('title', 'Asset | Sistem Peminjaman Asset')

@section('content')
<section>
    <div class="card">
        <h2 id="asset-form-title">Tambah Asset</h2>
        <form id="asset-form" class="asset-form">
            <input type="hidden" name="id" id="asset-id">
            <label><span class="label-text">Kode <span class="required">*</span></span><input name="kode" id="asset-kode" required placeholder="LPT-001"></label>
            <label><span class="label-text">Nama asset <span class="required">*</span></span><input name="nama" id="asset-nama" required placeholder="Laptop Lenovo"></label>
            <label><span class="label-text">Kategori</span><input name="kategori" id="asset-kategori" placeholder="Elektronik"></label>
            <label><span class="label-text">Kondisi <span class="required">*</span></span><select name="kondisi" id="asset-kondisi"><option value="baik">Baik</option><option value="rusak">Rusak</option></select></label>
            <div class="form-actions">
                <button class="btn" id="asset-submit">Tambah asset</button>
                <button type="button" class="btn secondary" id="asset-cancel" hidden>Batal</button>
            </div>
        </form>
    </div>
    <div class="card table-wrap">
        <div class="section-heading"><h2>Daftar Asset</h2><input id="asset-search" placeholder="Cari kode atau nama"></div>
        <table>
            <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Kondisi</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="assets-table"></tbody>
        </table>
    </div>
</section>
@push('scripts')
<script src="{{ asset('js/assets.js') }}"></script>
@endpush
@endsection
