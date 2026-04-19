<!DOCTYPE html>
<html lang="tl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>Ordering System</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root { --teal: #1D9E75; --teal-dark: #0F6E56; --teal-light: #E1F5EE; --bg: #F5F6FA; --white: #FFFFFF; --text: #1a1a2e; --muted: #666; --border: #e0e0e0; --red: #E24B4A; }
  body { font-family: 'Outfit', sans-serif; background: var(--bg); height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
  .topbar { background: linear-gradient(135deg, #1a1a5e 0%, #3949AB 100%); color: white; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
  .topbar h1 { font-size: 17px; font-weight: 600; }
  .main { display: grid; grid-template-columns: 160px 1fr 280px; flex: 1; overflow: hidden; }
  .categories { background: var(--white); border-right: 1px solid var(--border); overflow-y: auto; padding: 8px; display: flex; flex-direction: column; gap: 6px; }
  .cat-header { font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; padding: 4px 6px; }
  .cat-btn { padding: 16px 10px; border-radius: 10px; border: none; font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.18s; color: white; text-transform: uppercase; }
  .cat-btn[data-cat="Ulam"] { background: #37474F; }
  .cat-btn[data-cat="Rice"] { background: var(--teal-dark); }
  .cat-btn[data-cat="Beverage"] { background: #1565C0; }
  .cat-btn[data-cat="Dessert"] { background: #6A1B9A; }
  .cat-btn[data-cat="Snacks"] { background: #BF360C; }
  .cat-btn.active { outline: 3px solid rgba(255,255,255,0.5); outline-offset: -3px; }
  .products-area { display: flex; flex-direction: column; overflow: hidden; background: var(--bg); }
  .products-toolbar { padding: 8px 10px; background: var(--white); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
  .products-toolbar span { font-size: 13px; color: var(--muted); font-weight: 500; }
  .search-box { border: 1px solid var(--border); border-radius: 8px; padding: 5px 10px; font-family: 'Outfit', sans-serif; font-size: 13px; outline: none; width: 160px; }
  .search-box:focus { border-color: var(--teal); }
  .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding: 10px; overflow-y: auto; align-content: start; flex: 1; }
  .product-card { background: var(--white); border-radius: 12px; border: 1.5px solid var(--border); overflow: hidden; cursor: pointer; transition: all 0.18s; display: flex; flex-direction: column; }
  .product-card:hover { border-color: var(--teal); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(29,158,117,0.15); }
  .product-img { width: 100%; aspect-ratio: 1; background: #f0f0f0; display: flex; align-items: center; justify-content: center; overflow: hidden; }
  .product-img img { width: 100%; height: 100%; object-fit: cover; }
  .product-img .fallback { font-size: 36px; display: none; }
  .product-info { padding: 8px; flex: 1; display: flex; flex-direction: column; gap: 2px; }
  .product-name { font-size: 12px; font-weight: 600; color: var(--text); }
  .product-price { font-size: 13px; font-weight: 700; color: var(--teal-dark); }
  .checkout { background: var(--white); border-left: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; }
  .checkout-header { background: linear-gradient(135deg, #1a1a5e, #3949AB); color: white; padding: 10px 14px; font-size: 14px; font-weight: 600; flex-shrink: 0; }
  .selected-item { padding: 10px 14px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
  .selected-item .label { font-size: 10px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 3px; }
  .selected-item .product-display { font-size: 14px; font-weight: 700; color: var(--text); }
  .selected-item .desc-display { font-size: 11px; color: var(--muted); margin-top: 2px; }
  .qty-section { padding: 10px 14px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
  .qty-label { font-size: 10px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px; }
  .qty-control { display: flex; align-items: center; gap: 10px; }
  .qty-btn { width: 34px; height: 34px; border-radius: 8px; border: 1.5px solid var(--teal); background: var(--teal-light); color: var(--teal-dark); font-size: 20px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; }
  .qty-btn:hover { background: var(--teal); color: white; }
  .qty-display { font-size: 22px; font-weight: 700; color: var(--text); min-width: 36px; text-align: center; }
  .price-display { margin-left: auto; font-size: 14px; font-weight: 700; color: var(--teal-dark); }
  .add-btn { margin: 8px 14px; padding: 10px; background: var(--teal); color: white; border: none; border-radius: 10px; font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; cursor: pointer; flex-shrink: 0; }
  .add-btn:disabled { background: var(--border); color: var(--muted); cursor: not-allowed; }
  .order-list { flex: 1; overflow-y: auto; padding: 4px 14px; }
  .order-list-header { font-size: 10px; font-weight: 600; color: var(--muted); text-transform: uppercase; padding: 6px 0 4px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; }
  .order-item { display: flex; align-items: center; gap: 8px; padding: 6px 0; border-bottom: 1px solid #f0f0f0; font-size: 12px; }
  .order-item-name { flex: 1; font-weight: 600; color: var(--text); }
  .order-item-qty { color: var(--muted); min-width: 30px; text-align: center; }
  .order-item-price { font-weight: 700; color: var(--teal-dark); min-width: 60px; text-align: right; }
  .order-item-del { width: 22px; height: 22px; background: #ffeaea; border: none; border-radius: 6px; color: var(--red); font-size: 14px; cursor: pointer; font-weight: 700; }
  .order-item-del:hover { background: var(--red); color: white; }
  .empty-order { text-align: center; color: var(--muted); font-size: 12px; padding: 20px 0; }
  .totals-section { padding: 10px 14px; border-top: 1px solid var(--border); flex-shrink: 0; background: #fafafa; }
  .total-row { display: flex; justify-content: space-between; align-items: center; padding: 3px 0; font-size: 13px; }
  .total-row .t-label { color: var(--muted); }
  .total-row .t-val { font-weight: 600; }
  .total-row.grand { border-top: 1.5px solid var(--border); margin-top: 6px; padding-top: 8px; }
  .total-row.grand .t-label { font-size: 14px; font-weight: 700; color: var(--text); }
  .total-row.grand .t-val { font-size: 16px; font-weight: 700; color: var(--teal-dark); }
  .cash-section { padding: 8px 14px; border-top: 1px solid var(--border); flex-shrink: 0; }
  .cash-label { font-size: 10px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; }
  .cash-input { width: 100%; padding: 8px 10px; border: 1.5px solid var(--border); border-radius: 8px; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; outline: none; }
  .cash-input:focus { border-color: var(--teal); }
  .change-row { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; font-size: 13px; }
  .change-row .c-label { color: var(--muted); }
  .change-row .c-val { font-size: 15px; font-weight: 700; color: var(--teal-dark); }
  .change-row .c-val.negative { color: var(--red); }
  .action-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; padding: 8px 14px; flex-shrink: 0; }
  .pay-btn { padding: 13px; background: var(--teal); color: white; border: none; border-radius: 10px; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; cursor: pointer; }
  .pay-btn:hover { background: var(--teal-dark); }
  .clear-btn { padding: 13px; background: #f5f5f5; color: var(--red); border: 1.5px solid #ffd5d5; border-radius: 10px; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; cursor: pointer; }
  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; }
  .modal-overlay.show { display: flex; }
  .modal { background: var(--white); border-radius: 16px; padding: 24px; width: min(340px, 90vw); animation: popIn 0.25s ease; }
  @keyframes popIn { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  .receipt-header { text-align: center; margin-bottom: 12px; }
  .receipt-header h2 { font-size: 16px; font-weight: 700; }
  .receipt-header p { font-size: 11px; color: var(--muted); }
  .receipt-divider { border: none; border-top: 1.5px dashed var(--border); margin: 10px 0; }
  .receipt-items { max-height: 180px; overflow-y: auto; }
  .receipt-line { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; border-bottom: 1px dashed #eee; }
  .receipt-total { font-size: 16px; font-weight: 700; color: var(--teal-dark); display: flex; justify-content: space-between; margin-top: 8px; }
  .receipt-change { font-size: 14px; font-weight: 700; display: flex; justify-content: space-between; }
  .modal-btn { width: 100%; margin-top: 16px; padding: 12px; background: var(--teal); color: white; border: none; border-radius: 10px; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; cursor: pointer; }
  .toast { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%) translateY(80px); background: var(--text); color: white; padding: 10px 20px; border-radius: 30px; font-size: 13px; font-weight: 600; z-index: 2000; transition: transform 0.3s; white-space: nowrap; }
  .toast.show { transform: translateX(-50%) translateY(0); }
  .admin-tab { position: fixed; bottom: 10px; left: 10px; background: #1a1a5e; color: white; border: none; border-radius: 20px; padding: 6px 14px; font-size: 11px; font-weight: 600; cursor: pointer; z-index: 999; font-family: 'Outfit', sans-serif; }
  .admin-modal { width: min(480px, 95vw); max-height: 90vh; overflow-y: auto; }
  .admin-modal table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 10px; }
  .admin-modal th { background: #f0f0f0; padding: 6px 8px; text-align: left; font-weight: 600; }
  .admin-modal td { padding: 6px 8px; border-bottom: 1px solid #eee; }
  .close-btn { float: right; background: none; border: none; font-size: 20px; cursor: pointer; color: var(--muted); }
  .loading { text-align: center; padding: 40px; color: var(--muted); font-size: 13px; }
</style>
</head>
<body>
<div class="topbar">
  <h1>&#127869; Food Ordering System</h1>
  <span id="clock"></span>
</div>
<div class="main">
  <div class="categories">
    <div class="cat-header">Categories</div>
    <button class="cat-btn active" data-cat="Ulam" onclick="filterCat('Ulam',this)">ULAM</button>
    <button class="cat-btn" data-cat="Rice" onclick="filterCat('Rice',this)">RICE</button>
    <button class="cat-btn" data-cat="Beverage" onclick="filterCat('Beverage',this)">BEVERAGE</button>
    <button class="cat-btn" data-cat="Dessert" onclick="filterCat('Dessert',this)">DESSERT</button>
    <button class="cat-btn" data-cat="Snacks" onclick="filterCat('Snacks',this)">SNACKS</button>
  </div>
  <div class="products-area">
    <div class="products-toolbar">
      <span id="cat-title">Ulam</span>
      <input class="search-box" type="text" placeholder="Search..." oninput="searchProducts(this.value)">
    </div>
    <div class="products-grid" id="products-grid"><div class="loading">Loading products...</div></div>
  </div>
  <div class="checkout">
    <div class="checkout-header">&#128722; Order Details</div>
    <div class="selected-item">
      <div class="label">Selected Product</div>
      <div class="product-display" id="sel-product">— Walang napili —</div>
      <div class="desc-display" id="sel-desc"></div>
    </div>
    <div class="qty-section">
      <div class="qty-label">Quantity</div>
      <div class="qty-control">
        <button class="qty-btn" onclick="changeQty(-1)">&#8722;</button>
        <span class="qty-display" id="qty-display">1</span>
        <button class="qty-btn" onclick="changeQty(1)">+</button>
        <span class="price-display" id="item-price">&#8369;0.00</span>
      </div>
    </div>
    <button class="add-btn" id="add-btn" onclick="addToOrder()" disabled>+ Add to Order</button>
    <div class="order-list" id="order-list">
      <div class="order-list-header"><span>Item</span><span>Qty</span><span>Price</span><span></span></div>
      <div class="empty-order">Wala pang order.</div>
    </div>
    <div class="totals-section">
      <div class="total-row grand"><span class="t-label">TOTAL</span><span class="t-val" id="grand-total">&#8369;0.00</span></div>
    </div>
    <div class="cash-section">
      <div class="cash-label">Cash Tendered</div>
      <input class="cash-input" type="number" id="cash-input" placeholder="0.00" oninput="calcChange()" min="0">
      <div class="change-row">
        <span class="c-label">Change (Barya)</span>
        <span class="c-val" id="change-display">&#8369;0.00</span>
      </div>
    </div>
    <div class="action-btns">
      <button class="pay-btn" onclick="processPayment()">PAY</button>
      <button class="clear-btn" onclick="clearOrder()">CLEAR</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="receipt-modal">
  <div class="modal">
    <div class="receipt-header"><h2>&#9989; Order Complete!</h2><p id="receipt-date"></p></div>
    <hr class="receipt-divider">
    <div class="receipt-items" id="receipt-items"></div>
    <hr class="receipt-divider">
    <div class="receipt-total"><span>TOTAL</span><span id="r-total"></span></div>
    <div class="receipt-change"><span>Cash</span><span id="r-cash"></span></div>
    <div class="receipt-change"><span>Change</span><span id="r-change"></span></div>
    <button class="modal-btn" onclick="closeReceipt()">OK — Bagong Order</button>
  </div>
</div>

<div class="modal-overlay" id="admin-modal">
  <div class="modal admin-modal">
    <button class="close-btn" onclick="document.getElementById('admin-modal').classList.remove('show')">&#10005;</button>
    <h2 style="font-size:16px;margin-bottom:6px;">&#128200; Sales Report</h2>
    <div id="admin-content"></div>
  </div>
</div>

<div class="toast" id="toast"></div>
<button class="admin-tab" onclick="showAdmin()">&#128200; Sales</button>

<script>
const API = 'https://ordering-backend-wmve.onrender.com/index.php';
const IMG = 'https://ordering-backend-wmve.onrender.com/images/';
let products = [], currentCat = 'Ulam', selectedProduct = null, qty = 1, order = [], searchTerm = '';

setInterval(() => { document.getElementById('clock').textContent = new Date().toLocaleTimeString('en-PH'); }, 1000);

async function loadProducts() {
  try {
    const res = await fetch(API + '?action=get_products');
    products = await res.json();
    renderProducts();
  } catch(e) {
    document.getElementById('products-grid').innerHTML = '<div class="loading">Error loading. Check backend.</div>';
  }
}

function renderProducts() {
  const grid = document.getElementById('products-grid');
  const filtered = products.filter(p => p.category === currentCat && p.name.toLowerCase().includes(searchTerm.toLowerCase()));
  if (!filtered.length) { grid.innerHTML = '<div class="loading">Walang produkto.</div>'; return; }
  grid.innerHTML = filtered.map(p => `
    <div class="product-card" onclick="selectProduct(${p.id})">
      <div class="product-img">
        <img src="${IMG}${p.emoji}" alt="${p.name}" onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
        <span class="fallback">&#127869;</span>
      </div>
      <div class="product-info">
        <div class="product-name">${p.name}</div>
        <div class="product-price">&#8369;${parseFloat(p.price).toFixed(2)}</div>
      </div>
    </div>`).join('');
}

function filterCat(cat, btn) {
  currentCat = cat; selectedProduct = null; qty = 1; resetSelection();
  document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('cat-title').textContent = cat;
  renderProducts();
}

function searchProducts(val) { searchTerm = val; renderProducts(); }

function selectProduct(id) {
  selectedProduct = products.find(p => p.id == id); qty = 1;
  document.getElementById('sel-product').textContent = selectedProduct.name;
  document.getElementById('sel-desc').textContent = selectedProduct.description + ' — ₱' + parseFloat(selectedProduct.price).toFixed(2);
  document.getElementById('qty-display').textContent = 1;
  document.getElementById('item-price').textContent = '₱' + parseFloat(selectedProduct.price).toFixed(2);
  document.getElementById('add-btn').disabled = false;
}

function resetSelection() {
  selectedProduct = null;
  document.getElementById('sel-product').textContent = '— Walang napili —';
  document.getElementById('sel-desc').textContent = '';
  document.getElementById('qty-display').textContent = '1';
  document.getElementById('item-price').textContent = '₱0.00';
  document.getElementById('add-btn').disabled = true;
}

function changeQty(delta) {
  if (!selectedProduct) return;
  qty = Math.max(1, qty + delta);
  document.getElementById('qty-display').textContent = qty;
  document.getElementById('item-price').textContent = '₱' + (parseFloat(selectedProduct.price) * qty).toFixed(2);
}

function addToOrder() {
  if (!selectedProduct) return;
  const ex = order.find(o => o.id == selectedProduct.id);
  if (ex) ex.qty += qty;
  else order.push({ id: selectedProduct.id, name: selectedProduct.name, price: parseFloat(selectedProduct.price), qty });
  renderOrder();
  showToast('✓ ' + selectedProduct.name + ' (x' + qty + ') added!');
  qty = 1;
  document.getElementById('qty-display').textContent = 1;
  document.getElementById('item-price').textContent = '₱' + parseFloat(selectedProduct.price).toFixed(2);
}

function removeFromOrder(id) { order = order.filter(o => o.id != id); renderOrder(); }

function renderOrder() {
  const list = document.getElementById('order-list');
  const total = order.reduce((s, o) => s + o.price * o.qty, 0);
  list.innerHTML = '<div class="order-list-header"><span>Item</span><span>Qty</span><span>Price</span><span></span></div>' +
    (order.length ? order.map(o => `<div class="order-item">
      <span class="order-item-name">${o.name}</span>
      <span class="order-item-qty">x${o.qty}</span>
      <span class="order-item-price">₱${(o.price*o.qty).toFixed(2)}</span>
      <button class="order-item-del" onclick="removeFromOrder(${o.id})">✕</button>
    </div>`).join('') : '<div class="empty-order">Wala pang order.</div>');
  document.getElementById('grand-total').textContent = '₱' + total.toFixed(2);
  calcChange();
}

function calcChange() {
  const total = order.reduce((s, o) => s + o.price * o.qty, 0);
  const cash = parseFloat(document.getElementById('cash-input').value) || 0;
  const change = cash - total;
  const el = document.getElementById('change-display');
  el.textContent = '₱' + change.toFixed(2);
  el.className = 'c-val' + (change < 0 ? ' negative' : '');
}

async function processPayment() {
  if (!order.length) { showToast('⚠️ Walang order!'); return; }
  const total = order.reduce((s, o) => s + o.price * o.qty, 0);
  const cash = parseFloat(document.getElementById('cash-input').value) || 0;
  if (cash < total) { showToast('⚠️ Di sapat ang cash!'); return; }
  const change = cash - total;
  try {
    const res = await fetch(API + '?action=save_order', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ items: order, total, cash, change })
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('receipt-date').textContent = new Date().toLocaleString('en-PH');
      document.getElementById('receipt-items').innerHTML = order.map(o =>
        `<div class="receipt-line"><span>${o.name} x${o.qty}</span><span>₱${(o.price*o.qty).toFixed(2)}</span></div>`).join('');
      document.getElementById('r-total').textContent = '₱' + total.toFixed(2);
      document.getElementById('r-cash').textContent = '₱' + cash.toFixed(2);
      document.getElementById('r-change').textContent = '₱' + change.toFixed(2);
      document.getElementById('receipt-modal').classList.add('show');
      try { if (window.AppInventor) AppInventor.setWebViewString(JSON.stringify({name:order.map(o=>o.name).join(', '),qty:order.reduce((s,o)=>s+o.qty,0),total:total.toFixed(2),change:change.toFixed(2)})); } catch(e) {}
    }
  } catch(e) { showToast('⚠️ Error saving order!'); }
}

function closeReceipt() { document.getElementById('receipt-modal').classList.remove('show'); clearOrder(); }

function clearOrder() {
  order = []; selectedProduct = null; qty = 1; resetSelection(); renderOrder();
  document.getElementById('cash-input').value = '';
  document.getElementById('change-display').textContent = '₱0.00';
  document.getElementById('change-display').className = 'c-val';
}

async function showAdmin() {
  try {
    const res = await fetch(API + '?action=get_sales');
    const data = await res.json();
    const orders = data.orders || [], grand = parseFloat(data.grand_total) || 0;
    document.getElementById('admin-content').innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:10px 0;">
        <div style="background:#f0f9f5;border-radius:8px;padding:10px;text-align:center;">
          <div style="font-size:10px;color:#666;font-weight:600;text-transform:uppercase;">Total Sales</div>
          <div style="font-size:22px;font-weight:700;color:#0F6E56;">₱${grand.toFixed(2)}</div>
        </div>
        <div style="background:#f5f0ff;border-radius:8px;padding:10px;text-align:center;">
          <div style="font-size:10px;color:#666;font-weight:600;text-transform:uppercase;">Transactions</div>
          <div style="font-size:22px;font-weight:700;color:#3949AB;">${orders.length}</div>
        </div>
      </div>
      <table><thead><tr><th>#</th><th>Date</th><th>Items</th><th>Total</th><th>Change</th></tr></thead>
      <tbody>${orders.map((o,i)=>`<tr><td>${i+1}</td><td style="font-size:11px;">${o.created_at}</td><td style="font-size:11px;">${o.items||''}</td><td style="font-weight:700;color:#0F6E56;">₱${parseFloat(o.total).toFixed(2)}</td><td>₱${parseFloat(o.change_amount).toFixed(2)}</td></tr>`).join('')||'<tr><td colspan="5" style="text-align:center;color:#999;">Wala pang sales.</td></tr>'}</tbody></table>`;
  } catch(e) { document.getElementById('admin-content').innerHTML = '<p style="color:red;">Error.</p>'; }
  document.getElementById('admin-modal').classList.add('show');
}

function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2000);
}

loadProducts();
</script>
</body>
</html>
