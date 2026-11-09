const assetForm = document.querySelector('#asset-form');
const assetTable = document.querySelector('#assets-table');
const assetSearch = document.querySelector('#asset-search');
const assetCancel = document.querySelector('#asset-cancel');

async function loadAssets() {
    const query = encodeURIComponent(assetSearch.value.trim());
    const assets = await api(`/api/assets${query ? `?q=${query}` : ''}`);
    assetTable.innerHTML = assets.length ? assets.map((asset) => `
        <tr><td>${esc(asset.kode)}</td><td>${esc(asset.nama)}</td><td>${esc(asset.kategori)}</td>
        <td><span class="tag ${asset.kondisi}">${asset.kondisi}</span></td><td><span class="tag ${asset.status}">${asset.status}</span></td>
        <td><button class="btn small" data-edit="${asset.id}">Edit</button> <button class="btn small danger" data-delete="${asset.id}">Hapus</button></td></tr>
    `).join('') : '<tr><td colspan="6" class="empty">Belum ada asset.</td></tr>';
}

function resetAssetForm() {
    assetForm.reset();
    document.querySelector('#asset-id').value = '';
    document.querySelector('#asset-form-title').textContent = 'Tambah Asset';
    document.querySelector('#asset-submit').textContent = 'Tambah asset';
    assetCancel.hidden = true;
}

async function editAsset(id) {
    const asset = await api(`/api/assets/${id}`);
    document.querySelector('#asset-id').value = asset.id;
    document.querySelector('#asset-kode').value = asset.kode;
    document.querySelector('#asset-kode').disabled = true;
    document.querySelector('#asset-nama').value = asset.nama;
    document.querySelector('#asset-kategori').value = asset.kategori;
    document.querySelector('#asset-kondisi').value = asset.kondisi;
    document.querySelector('#asset-form-title').textContent = 'Edit Asset';
    document.querySelector('#asset-submit').textContent = 'Simpan perubahan';
    assetCancel.hidden = false;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

assetForm.onsubmit = safe(async (event) => {
    const formData = Object.fromEntries(new FormData(event.target));
    const id = formData.id;
    delete formData.id;
    await api(id ? `/api/assets/${id}` : '/api/assets', { method: id ? 'PUT' : 'POST', body: JSON.stringify(formData) });
    resetAssetForm();
    await loadAssets();
    toast(id ? 'Asset diperbarui' : 'Asset ditambahkan');
});
assetCancel.onclick = resetAssetForm;
assetSearch.oninput = safe(loadAssets);
assetTable.onclick = safe(async (event) => {
    const editId = event.target.dataset.edit;
    const deleteId = event.target.dataset.delete;
    if (editId) return editAsset(editId);
    if (deleteId && confirm('Hapus asset ini?')) {
        await api(`/api/assets/${deleteId}`, { method: 'DELETE' });
        await loadAssets();
        toast('Asset dihapus');
    }
});
loadAssets().catch((error) => toast(error.message));
