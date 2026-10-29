{{-- Inline AJAX downloader: paste a link -> auto analyze -> formats render inline, no page reload. Progressive enhancement over the plain POST form. --}}
<style>.sf-ajax-result:empty{display:none}.sf-spinner{display:inline-block;width:14px;height:14px;border:2px solid #cbd9ee;border-top-color:#2166f3;border-radius:50%;animation:sfspin .7s linear infinite;vertical-align:-2px}@keyframes sfspin{to{transform:rotate(360deg)}}.inline-result-section{padding:48px 0 52px;background:#f7fbff;border-top:1px solid #e3eefb}.inline-result-heading{text-align:center;margin-bottom:24px}.inline-result-heading h2{margin:8px 0 6px;font-size:30px}.inline-result-card{display:grid;grid-template-columns:36% 64%;overflow:hidden;background:#fff;border:1px solid #dce8f7;border-radius:20px;box-shadow:0 22px 60px rgba(35,88,158,.14)}.inline-media{padding:26px;border-right:1px solid #e3ecf7}.inline-media img,.inline-media-placeholder{width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:13px;background:linear-gradient(135deg,#dfeeff,#eef7ff)}.inline-media-placeholder{display:grid;place-items:center;color:#2770f3;font-size:45px}.inline-media h3{margin:17px 0 12px;font-size:18px;line-height:1.4}.inline-meta{display:flex;gap:8px;flex-wrap:wrap}.inline-meta span{display:inline-flex;align-items:center;gap:6px;padding:7px 10px;border-radius:8px;background:#eaf3ff;color:#2166f3;font-size:11px;font-weight:700}.formats-panel{min-width:0}.format-heading{display:flex;align-items:center;gap:10px;padding:20px 24px;border-bottom:1px solid #e3ecf7;color:#142746;font-size:19px;font-weight:800}.format-heading i{width:35px;height:35px;display:grid;place-items:center;border-radius:10px;background:#e8f2ff;color:#2166f3}.format-heading.audio i{background:#f1edff;color:#7351f5}.inline-format-row{display:grid;grid-template-columns:74px minmax(80px,1fr) minmax(90px,1fr) auto;align-items:center;gap:14px;padding:16px 24px;border-bottom:1px solid #e8eef6}.inline-format-row:last-child{border-bottom:0}.inline-format-badge{padding:8px 9px;border-radius:8px;background:linear-gradient(135deg,#2c73f5,#1f5ee5);color:#fff;text-align:center;font-size:13px;font-weight:800}.inline-format-quality{color:#203654;font-weight:800}.inline-format-size{color:#687c98;font-size:13px;font-weight:600}.inline-download{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 15px;border:2px solid #2166f3;border-radius:9px;color:#2166f3;font-size:13px;font-weight:800;white-space:nowrap;transition:.2s}.inline-download:hover{background:#2166f3;color:#fff}.inline-empty{padding:20px 24px;color:#7a8da8;font-size:13px}.result-legal{text-align:center;margin:14px 0 0;color:#7890ae;font-size:12px}@media(max-width:800px){.inline-result-card{grid-template-columns:1fr}.inline-media{border-right:0;border-bottom:1px solid #e3ecf7}.inline-format-row{grid-template-columns:62px 1fr auto}.inline-format-size{display:none}}@media(max-width:620px){.inline-result-section{padding:32px 0}.inline-result-heading h2{font-size:24px}.inline-media{padding:17px}.format-heading{padding:16px}.inline-format-row{padding:13px 14px;gap:8px}.inline-download{padding:9px 10px;font-size:0}.inline-download i{font-size:16px}.inline-format-quality{font-size:13px}}</style>
<script>
(function(){
  var form=document.querySelector('form.search[action$="/analyze"]')||document.querySelector('form.search')||document.querySelector('form[action$="/analyze"]');
  if(!form)return;
  var input=form.querySelector('input[name="url"]');
  var token=(form.querySelector('input[name="_token"]')||{}).value||'';
  if(!input)return;
  var box=document.getElementById('sf-ajax-result');
  if(!box){
    box=document.createElement('div');box.id='sf-ajax-result';box.className='sf-ajax-result';
    var existing=document.querySelector('.inline-result-section');
    if(existing&&existing.parentNode){existing.parentNode.insertBefore(box,existing);}
    else{var sec=form.closest('section');if(sec&&sec.parentNode){sec.parentNode.insertBefore(box,sec.nextSibling);}else{form.parentNode.appendChild(box);}}
  }
  var busy=false,lastUrl='';
  function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
  function isUrl(v){v=(v||'').trim();if(!/^https?:\/\//i.test(v))return false;try{new URL(v);return true;}catch(e){return false;}}
  function rowHtml(f,audio){
    var badge=audio?' style="background:linear-gradient(135deg,#865ff5,#6944db)"':'';
    var dl;
    if(f.download_url){dl='<a class="inline-download" href="'+esc(f.download_url)+'" target="_blank" rel="nofollow noopener"><i class="bi bi-download"></i>Download</a>';}
    else if(f.prepare_url){dl='<a class="inline-download" href="'+esc(f.prepare_url)+'"><i class="bi bi-download"></i>Download</a>';}
    else{dl='<span class="inline-download" style="opacity:.45"><i class="bi bi-x-circle"></i>Unavailable</span>';}
    return '<div class="inline-format-row"><span class="inline-format-badge"'+badge+'>'+esc(f.format)+'</span><span class="inline-format-quality">'+esc(f.quality)+'</span><span class="inline-format-size">'+esc(f.size)+'</span>'+dl+'</div>';
  }
  function render(d){
    var fmts=d.formats||[];
    var vids=fmts.filter(function(f){return f.type==='Video';});
    var auds=fmts.filter(function(f){return f.type==='Audio';});
    var imgs=fmts.filter(function(f){return f.type==='Image';});
    var media=d.thumbnail?'<img src="'+esc(d.thumbnail)+'" alt="'+esc(d.title)+'" title="'+esc(d.title)+'">':'<div class="inline-media-placeholder"><i class="bi bi-play-circle-fill"></i></div>';
    var meta='<span><i class="bi bi-globe2"></i>'+esc(d.platform&&d.platform.name)+'</span>'+(d.duration?'<span><i class="bi bi-clock"></i>'+esc(d.duration)+'</span>':'');
    var panel='';
    if(imgs.length){panel+='<div class="format-heading"><i class="bi bi-image"></i>Photos</div>'+imgs.map(function(f){return rowHtml(f,false);}).join('');}
    if(vids.length||!imgs.length){panel+='<div class="format-heading"><i class="bi bi-camera-video-fill"></i>Video</div>'+(vids.length?vids.map(function(f){return rowHtml(f,false);}).join(''):'<div class="inline-empty">No video formats are available for this link.</div>');}
    if(auds.length){panel+='<div class="format-heading audio"><i class="bi bi-music-note-beamed"></i>Music</div>'+auds.map(function(f){return rowHtml(f,true);}).join('');}
    box.innerHTML='<section class="inline-result-section" id="download-results"><div class="wrap"><div class="inline-result-heading"><div class="eyebrow"><i class="bi bi-check-circle-fill"></i>&nbsp; Media Found</div><h2>Choose Your Download Format</h2><p class="sub" style="margin:0">Select a video quality or audio format below.</p></div><div class="inline-result-card"><aside class="inline-media">'+media+'<h3>'+esc(d.title)+'</h3><div class="inline-meta">'+meta+'</div></aside><div class="formats-panel">'+panel+'</div></div><p class="result-legal"><i class="bi bi-shield-check"></i>&nbsp; Only download public content you own or have permission to use.</p></div></section>';
    var r=box.querySelector('#download-results');if(r)r.scrollIntoView({behavior:'smooth',block:'start'});
  }
  function loading(){box.innerHTML='<section class="inline-result-section"><div class="wrap"><div class="inline-result-heading"><div class="eyebrow"><span class="sf-spinner"></span>&nbsp; Analyzing link…</div><p class="sub" style="margin:6px 0 0">Fetching available formats, this takes a few seconds.</p></div></div></section>';}
  function errbox(msg){box.innerHTML='<section class="inline-result-section"><div class="wrap"><div class="inline-result-heading"><div class="eyebrow" style="color:#c0392b"><i class="bi bi-exclamation-triangle-fill"></i>&nbsp; Could not analyze</div><p class="sub" style="margin:6px 0 0">'+esc(msg)+'</p></div></div></section>';}
  function run(url){
    url=(url||'').trim();
    if(!isUrl(url)||busy||url===lastUrl)return;
    lastUrl=url;busy=true;loading();
    var body=new FormData();body.append('url',url);body.append('_token',token);
    fetch(form.getAttribute('action'),{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},body:body,credentials:'same-origin'})
      .then(function(r){return r.json().then(function(j){return{ok:r.ok,j:j};}).catch(function(){return{ok:false,j:null};});})
      .then(function(res){
        busy=false;
        if(res.ok&&res.j&&res.j.ok){render(res.j);}
        else{var m=(res.j&&(res.j.error||(res.j.errors&&res.j.errors.url&&res.j.errors.url[0])||res.j.message))||'This link could not be processed. Please try another public link.';errbox(m);lastUrl='';}
      })
      .catch(function(){busy=false;lastUrl='';errbox('Network error. Please try again.');});
  }
  form.addEventListener('submit',function(e){e.preventDefault();run(input.value);});
  input.addEventListener('paste',function(){setTimeout(function(){run(input.value);},40);});
  input.addEventListener('change',function(){run(input.value);});
})();
</script>
