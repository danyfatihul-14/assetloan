const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
const date = (value) => value ? String(value).slice(0, 10) : '-';
const toast = (message) => { const element = document.querySelector('#toast'); element.textContent = message; element.style.display = 'block'; setTimeout(() => element.style.display = 'none', 3000); };
async function api(url, options = {}) {
    const response = await fetch(url, { headers: {'Content-Type':'application/json','Accept':'application/json'}, ...options });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(data.message || data.error || Object.values(data.errors || {}).flat()[0] || 'Permintaan gagal');
    return data;
}
const safe = (callback) => async (event) => {
    event?.preventDefault();
    try { await callback(event); } catch (error) { toast(error.message); }
};
