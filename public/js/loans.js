const loanForm = document.querySelector('#loan-form');
const loanTable = document.querySelector('#loans-table');
const loanStatus = document.querySelector('#loan-status');
const returnDate = document.querySelector('#return-date');
const today = new Date();
returnDate.min = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

async function loadAvailableAssets() {
    const assets = await api('/api/assets?status=tersedia');
    const available = assets.filter((asset) => asset.kondisi === 'baik');
    document.querySelector('#asset-select').innerHTML = available.length
        ? available.map((asset) => `<option value="${asset.id}">${esc(asset.kode)} - ${esc(asset.nama)}</option>`).join('')
        : '<option value="">Tidak ada asset tersedia</option>';
}
async function loadLoans() {
    const status = loanStatus.value;
    const loans = await api(`/api/loans${status ? `?status=${status}` : ''}`);
    loanTable.innerHTML = loans.length ? loans.map((loan) => `
        <tr><td>${esc(loan.asset.kode)} - ${esc(loan.asset.nama)}</td><td>${esc(loan.peminjam)}</td><td>${esc(loan.keperluan) || '-'}</td>
        <td>${date(loan.tgl_pinjam)}</td><td>${date(loan.tgl_rencana_kembali)} ${loan.terlambat ? '<span class="tag late">terlambat</span>' : ''}</td>
        <td><span class="tag ${loan.status}">${loan.status}</span></td><td>${loan.status === 'dipinjam' ? `<button class="btn small" data-return="${loan.id}">Kembalikan</button>` : date(loan.tgl_kembali)}</td></tr>
    `).join('') : '<tr><td colspan="7" class="empty">Belum ada peminjaman.</td></tr>';
}
async function refreshLoans() { await loadAvailableAssets(); await loadLoans(); }
loanForm.onsubmit = safe(async (event) => {
    await api('/api/loans', { method: 'POST', body: JSON.stringify(Object.fromEntries(new FormData(event.target))) });
    event.target.reset();
    await refreshLoans();
    toast('Peminjaman dicatat');
});
loanStatus.onchange = safe(loadLoans);
loanTable.onclick = safe(async (event) => {
    const id = event.target.dataset.return;
    if (id) {
        await api(`/api/loans/${id}/return`, { method: 'PATCH' });
        await refreshLoans();
        toast('Asset dikembalikan');
    }
});
refreshLoans().catch((error) => toast(error.message));
