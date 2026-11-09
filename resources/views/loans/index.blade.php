@extends('layouts.app')

@section('title', 'Peminjaman | Sistem Peminjaman Asset')

@section('content')
<section>
    <div class="card">
        <h2>Tambah Peminjaman</h2>
        <form id="loan-form">
            <label><span class="label-text">Asset <span class="required">*</span></span><select name="asset_id" id="asset-select" required></select></label>
            <label><span class="label-text">Peminjam <span class="required">*</span></span><input name="peminjam" required placeholder="Nama peminjam"></label>
            <label><span class="label-text">Keperluan</span><input name="keperluan" placeholder="Opsional"></label>
            <label><span class="label-text">Rencana kembali <span class="required">*</span></span><input type="date" name="tgl_rencana_kembali" id="return-date" required></label>
            <button class="btn">Pinjam</button>
        </form>
    </div>
    <div class="card table-wrap">
        <div class="section-heading"><h2>Daftar Peminjaman</h2><select id="loan-status"><option value="">Semua status</option><option value="dipinjam">Dipinjam</option><option value="dikembalikan">Dikembalikan</option></select></div>
        <table>
            <thead><tr><th>Asset</th><th>Peminjam</th><th>Keperluan</th><th>Dipinjam</th><th>Rencana kembali</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="loans-table"></tbody>
        </table>
    </div>
</section>
@push('scripts')
<script src="{{ asset('js/loans.js') }}"></script>
@endpush
@endsection
