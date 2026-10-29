"""Generate designed SVG covers for blog posts.

Usage (from the repo root, with a JSON export of posts):
  python3 -I tools/covers.py posts.json
posts.json: [{"slug","title","language","featured_image"}, ...] (e.g. dumped
from blog_posts). Writes public/images/blog/cover-<slug>.svg for each post and
prints a slug => image mapping to feed a migration (see
database/data/covers-2026-10-29.json). Style: platform colour, title in
2-3 lines, subtitle, badge, device mock with MP4/MP3/JPG chip; RTL-safe for
Arabic (direction="rtl" with text-anchor="start" at x=1200).
"""
import sys
import json,html,re,os
posts=json.load(open(sys.argv[1]))
OUT="public/images/blog/"
PAL={ # (bg1,bg2,accent1,accent2,badge text)
 "youtube":("#fff1f2","#ffe4e6","#ff4d4d","#b91c1c","YOUTUBE"),
 "instagram":("#fdf2f8","#fce7f3","#f77737","#c13584","INSTAGRAM"),
 "tiktok":("#ecfeff","#cffafe","#25f4ee","#0f172a","TIKTOK"),
 "facebook":("#eff6ff","#dbeafe","#60a5fa","#1877f2","FACEBOOK"),
 "twitter":("#f3f4f6","#e5e7eb","#9ca3af","#111827","X / TWITTER"),
 "vimeo":("#ecfeff","#cffafe","#38bdf8","#1ab7ea","VIMEO"),
 "dailymotion":("#eff6ff","#dbeafe","#3b82f6","#0b73e0","DAILYMOTION"),
 "default":("#eef6ff","#e6ecff","#2668f6","#7a4cf5","GUIDE"),
}
def platform(slug,title):
    t=(slug+" "+title).lower()
    for k in ["youtube","instagram","tiktok","facebook","twitter","vimeo","dailymotion"]:
        if k in t or (k=="twitter" and ("تويتر" in t or "x-video" in t)) or (k=="youtube" and ("يوتيوب" in t or "shorts" in t or "mp3" in t and "youtube" in t)) or (k=="instagram" and ("انستقرام" in t or "ریلز" in t)) or (k=="tiktok" and "تيك توك" in t) or (k=="facebook" and "فيسبوك" in t): return k
    return "default"
def wrap(text,maxc):
    words=text.split(); lines=[]; cur=""
    for w in words:
        if len(cur)+len(w)+1>maxc and cur: lines.append(cur); cur=w
        else: cur=(cur+" "+w).strip()
    if cur: lines.append(cur)
    return lines[:3]
def esc(s): return html.escape(s,quote=True)
def cover(p):
    slug,title,lang=p["slug"],p["title"],p["language"]
    rtl = lang=="ar"
    main,_,sub=title.partition(":")
    main=main.strip(); sub=sub.strip()
    if not sub: sub="Save-Froms guide" if not rtl else "دليل Save-Froms"
    maxc = 18 if rtl else 20
    lines=wrap(main,maxc)
    longest=max(len(l) for l in lines)
    size = 64 if longest<=13 else 56 if longest<=17 else 48 if longest<=21 else 42
    sub=(sub[:62]+"…") if len(sub)>62 else sub
    k=platform(slug,title); bg1,bg2,a1,a2,badge=PAL[k]
    if lang=="hi-Latn": badge="HINDI GUIDE"
    if rtl: badge="دليل عربي"
    font="'Noto Sans Arabic','Segoe UI',Tahoma,Arial,sans-serif" if rtl else "'DM Sans','Segoe UI',Arial,sans-serif"
    # text block: LTR at x=80, RTL at x=1200 anchored start with direction rtl
    tx = 1200 if rtl else 80
    dirattr=' direction="rtl"' if rtl else ''
    y0=250; lh=int(size*1.2)
    title_svg="".join(f'<text x="{tx}" y="{y0+i*lh}" text-anchor="start"{dirattr} fill="#0f1f3d" font-family="{font}" font-size="{size}" font-weight="800">{esc(l)}</text>' for i,l in enumerate(lines))
    sub_y=y0+len(lines)*lh+20
    bx = tx-190 if rtl else tx
    dev_x = 70 if rtl else 820
    svg=f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720" width="1280" height="720">
<defs><linearGradient id="bg" x1="0" x2="1" y1="0" y2="1"><stop stop-color="{bg1}"/><stop offset="1" stop-color="{bg2}"/></linearGradient>
<linearGradient id="scr" x1="0" x2="1" y1="0" y2="1"><stop stop-color="{a1}"/><stop offset="1" stop-color="{a2}"/></linearGradient>
<pattern id="dots" width="28" height="28" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" fill="#0f1f3d" opacity=".07"/></pattern>
<filter id="sh"><feDropShadow dx="0" dy="16" stdDeviation="16" flood-color="#0f1f3d" flood-opacity=".2"/></filter></defs>
<rect width="1280" height="720" fill="url(#bg)"/><rect width="1280" height="720" fill="url(#dots)"/>
<circle cx="{'130' if rtl else '1150'}" cy="100" r="230" fill="#fff" opacity=".35"/>
<g filter="url(#sh)" transform="translate({dev_x},120)"><rect width="390" height="480" rx="40" fill="#0f1f3d"/><rect x="16" y="16" width="358" height="448" rx="30" fill="url(#scr)"/>
<rect x="150" y="30" width="90" height="10" rx="5" fill="#0f1f3d" opacity=".5"/>
<circle cx="195" cy="210" r="66" fill="#0f1f3d" opacity=".5"/><path d="m176 176 58 34-58 34Z" fill="#fff"/>
<rect x="50" y="330" width="290" height="12" rx="6" fill="#fff" opacity=".85"/><rect x="50" y="358" width="200" height="12" rx="6" fill="#fff" opacity=".5"/>
<rect x="50" y="395" width="130" height="40" rx="20" fill="#fff"/><text x="115" y="422" text-anchor="middle" fill="#0f1f3d" font-family="Arial" font-size="20" font-weight="800">{"MP3" if "mp3" in slug or "audio" in slug else "JPG" if "photo" in slug else "MP4"}</text>
<circle cx="300" cy="415" r="22" fill="#fff"/><path d="M300 403v18m-7-7 7 7 7-7" stroke="#0f1f3d" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/></g>
<rect x="{bx}" y="150" width="190" height="50" rx="25" fill="#0f1f3d"/><text x="{bx+95}" y="184" text-anchor="middle" fill="#fff" font-family="{font}" font-size="22" font-weight="800" letter-spacing="1">{esc(badge)}</text>
{title_svg}
<text x="{tx}" y="{sub_y}" text-anchor="start"{dirattr} fill="#3b4f6e" font-family="{font}" font-size="26" font-weight="500">{esc(sub)}</text>
<g transform="translate({'1120' if rtl else '80'},630)"><rect width="46" height="46" rx="12" fill="#2668f6"/><path d="m17 13 16 10-16 10Z" fill="#fff"/><text x="{'-12' if rtl else '58'}" y="31" text-anchor="{'end' if rtl else 'start'}" fill="#0f1f3d" font-family="{font}" font-size="24" font-weight="800">save-froms.net</text></g>
</svg>'''
    name=f"cover-{slug}.svg"
    open(OUT+name,"w").write(svg)
    return name
mapping={}
for p in posts:
    if p["featured_image"].endswith(".svg"):
        mapping[p["slug"]]="images/blog/"+cover(p)
print(json.dumps(mapping,indent=0))
print(len(mapping),"covers generated")
