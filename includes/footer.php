</main><footer class="eh-nav text-white-50 text-center py-4 mt-5">© EVENTHUB — demo project · Sample contact: hello@example.com</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>document.querySelectorAll('.cd').forEach(el=>{const t=new Date(el.dataset.t).getTime();if(isNaN(t))return;const f=()=>{let d=Math.max(0,t-Date.now())/1000|0;el.textContent=d?`${d/86400|0}d ${d%86400/3600|0}h ${d%3600/60|0}m ${d%60}s`:'Event started';};f();setInterval(f,1000);});</script></body></html>
