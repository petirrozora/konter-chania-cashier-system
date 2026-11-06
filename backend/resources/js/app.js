document.addEventListener('DOMContentLoaded',()=>{
 const q=s=>document.querySelector(s), qa=s=>document.querySelectorAll(s);

 // Owner mobile navigation
 const ownerToggle=q('#owner-menu-toggle');
 const ownerSidebar=q('#owner-sidebar');
 const sidebarBackdrop=q('#sidebar-backdrop');
 const closeOwnerMenu=()=>{
   ownerSidebar?.classList.remove('mobile-open');
   sidebarBackdrop?.classList.remove('open');
   ownerToggle?.setAttribute('aria-expanded','false');
   document.body.classList.remove('menu-open');
 };
 const openOwnerMenu=()=>{
   ownerSidebar?.classList.add('mobile-open');
   sidebarBackdrop?.classList.add('open');
   ownerToggle?.setAttribute('aria-expanded','true');
   document.body.classList.add('menu-open');
 };
 ownerToggle?.addEventListener('click',()=>{
   ownerSidebar?.classList.contains('mobile-open') ? closeOwnerMenu() : openOwnerMenu();
 });
 sidebarBackdrop?.addEventListener('click',closeOwnerMenu);
 ownerSidebar?.querySelectorAll('a').forEach(a=>a.addEventListener('click',closeOwnerMenu));
 window.addEventListener('resize',()=>{if(window.innerWidth>760) closeOwnerMenu()});


 qa('[data-open]').forEach(b=>b.addEventListener('click',()=>{const d=q('#'+b.dataset.open);d?.classList.add('open');d?.setAttribute('aria-hidden','false')}));
 qa('[data-close]').forEach(b=>b.addEventListener('click',()=>{const d=b.closest('.drawer');d?.classList.remove('open');d?.setAttribute('aria-hidden','true')}));
 qa('.drawer').forEach(d=>d.addEventListener('click',e=>{if(e.target===d){d.classList.remove('open');d.setAttribute('aria-hidden','true')}}));
 const pt=q('[data-password-toggle]'); if(pt) pt.addEventListener('click',()=>{const i=q('#password');i.type=i.type==='password'?'text':'password'});
 const sf=q('#stock-form'); if(sf){
   qa('[data-stock-in]').forEach(b=>b.addEventListener('click',()=>{sf.action=`/admin/stocks/${b.dataset.stockIn}/in`;q('#stock-title').textContent='Barang Masuk';q('#stock-product').textContent=b.dataset.name;q('#reason-label').style.display='none';q('#stock-drawer').classList.add('open')}));
   qa('[data-stock-out]').forEach(b=>b.addEventListener('click',()=>{sf.action=`/admin/stocks/${b.dataset.stockOut}/out`;q('#stock-title').textContent='Barang Keluar';q('#stock-product').textContent=b.dataset.name;q('#reason-label').style.display='grid';q('#stock-drawer').classList.add('open')}));
 }
 const search=q('#product-search'); const results=q('#search-results'); let options=[],active=-1,cart=JSON.parse(localStorage.getItem('kp_cart')||'[]');
 const money=n=>'Rp '+Number(n||0).toLocaleString('id-ID');
 function renderCart(){const el=q('#cart-items'); if(!el)return; if(!cart.length){el.innerHTML='<p class="empty">Belum ada barang. Cari produk untuk memulai.</p>'}else{el.innerHTML=cart.map((x,i)=>`<div class="cart-item"><div><strong>${x.name}</strong><small>${money(x.price)} × ${x.quantity} · stok ${x.stock}</small></div><div class="qty-control"><button data-minus="${i}" aria-label="Kurangi">−</button><input data-qty="${i}" value="${x.quantity}" inputmode="numeric"><button data-plus="${i}" aria-label="Tambah">+</button><button data-del="${i}" aria-label="Hapus">×</button></div><strong class="num">${money(x.price*x.quantity)}</strong></div>`).join('');}
   const total=cart.reduce((s,x)=>s+x.price*x.quantity,0);q('#cart-total').textContent=money(total);updateChange();localStorage.setItem('kp_cart',JSON.stringify(cart));
   qa('[data-minus]').forEach(b=>b.onclick=()=>changeQty(+b.dataset.minus,-1));qa('[data-plus]').forEach(b=>b.onclick=()=>changeQty(+b.dataset.plus,1));qa('[data-del]').forEach(b=>b.onclick=()=>{cart.splice(+b.dataset.del,1);renderCart()});qa('[data-qty]').forEach(i=>i.onchange=()=>{const n=Math.max(1,Math.min(cart[+i.dataset.qty].stock,+i.value||1));cart[+i.dataset.qty].quantity=n;renderCart()});
 }
 function changeQty(i,d){cart[i].quantity=Math.max(1,Math.min(cart[i].stock,cart[i].quantity+d));renderCart()}
 function updateChange(){const total=cart.reduce((s,x)=>s+x.price*x.quantity,0),paid=+(q('#paid')?.value||0),diff=paid-total,cl=q('#change'),lab=q('#change-label'),pay=q('#pay');if(!cl)return;cl.textContent=money(Math.abs(diff));lab.textContent=diff>=0?'Kembalian':'Kurang';cl.style.color=diff>=0?'var(--petrol)':'var(--danger)';if(pay)pay.disabled=!cart.length||diff<0; }
 if(search){
  let timer; search.addEventListener('input',()=>{clearTimeout(timer);timer=setTimeout(async()=>{const term=search.value.trim();if(!term){results.classList.remove('open');return}const r=await fetch(`/employee/transactions/search?q=${encodeURIComponent(term)}`);options=await r.json();active=0;results.innerHTML=options.map((p,i)=>`<div class="search-option ${i===0?'active':''}" role="option" data-pidx="${i}"><div><strong>${p.name}</strong><small>${p.code} · stok ${p.stock}</small></div><strong>${money(p.price)}</strong></div>`).join('');results.classList.toggle('open',options.length>0);search.setAttribute('aria-expanded',options.length>0);qa('[data-pidx]').forEach(o=>o.onclick=()=>add(options[+o.dataset.pidx]));},120)});
  search.addEventListener('keydown',e=>{if(e.key==='ArrowDown'||e.key==='ArrowUp'){e.preventDefault();if(!options.length)return;active=(active+(e.key==='ArrowDown'?1:-1)+options.length)%options.length;qa('[data-pidx]').forEach((o,i)=>o.classList.toggle('active',i===active))}else if(e.key==='Enter'&&!e.repeat){e.preventDefault();if(options[active])add(options[active])}else if(e.key==='Escape'){search.value='';results.classList.remove('open')}});
  async function add(p){const found=cart.find(x=>x.id===p.id);if(found){if(found.quantity<p.stock)found.quantity++;else return}else cart.push({id:p.id,name:p.name,price:p.price,stock:p.stock,unit:p.unit,quantity:1});renderCart();search.value='';results.classList.remove('open');search.focus()}
  document.addEventListener('keydown',e=>{if(e.key==='F2'||(e.key==='/'&&!['INPUT','TEXTAREA'].includes(document.activeElement.tagName))){e.preventDefault();search.focus()}if(e.key==='F8'){e.preventDefault();q('#paid')?.focus()}if(e.key==='F9'||(e.ctrlKey&&e.key==='Enter')){e.preventDefault();q('#pay')?.click()}});
  qa('[data-money]').forEach(b=>b.onclick=()=>{const total=cart.reduce((s,x)=>s+x.price*x.quantity,0);q('#paid').value=b.dataset.money==='0'?total:b.dataset.money;updateChange()});q('#paid')?.addEventListener('input',updateChange);
  q('#clear-cart')?.addEventListener('click',()=>{cart=[];renderCart();search.focus()});
  q('#pay')?.addEventListener('click',async()=>{if(!cart.length)return;const paid=+q('#paid').value;const r=await fetch('/employee/transactions',{method:'POST',headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1]||''},body:JSON.stringify({items:cart.map(x=>({product_id:x.id,quantity:x.quantity})),paid_amount:paid})});const data=await r.json();if(!r.ok){alert(data.message||Object.values(data.errors||{})[0]?.[0]||'Transaksi gagal');return}q('#success-screen').hidden=false;q('#success-invoice').textContent=data.sale.invoice_code;q('#success-total-value').textContent=money(data.sale.total_price);q('#success-change-value').textContent=money(data.sale.change_amount);cart=[];localStorage.removeItem('kp_cart');q('#new-sale').focus()});
  q('#new-sale')?.addEventListener('click',()=>{q('#success-screen').hidden=true;q('#paid').value='';renderCart();search.focus()});renderCart();
 }
 const copy=q('#copy-restock');if(copy)copy.onclick=async()=>{const ids=[...qa('.restock-check:checked')].map(x=>x.value);if(!ids.length){alert('Pilih produk terlebih dahulu.');return}const token=document.querySelector('meta[name="csrf-token"]')?.content;const r=await fetch('/admin/restock/copy',{method:'POST',headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify({ids})});const d=await r.json();await navigator.clipboard.writeText(d.text);copy.textContent='Tersalin';setTimeout(()=>copy.textContent='Salin daftar belanja',1200)};
});
