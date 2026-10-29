<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Three more Hinglish guides (India Autocomplete, 2026-10-29):
 * "youtube shorts download kaise kare", "instagram se photo download kaise
 * kare", "video se audio kaise nikale".
 */
class PublishThreeMoreHinglishGuides extends Migration
{
    public function up()
    {
        $now = now();
        foreach ($this->posts() as $post) {
            DB::table('blog_posts')->updateOrInsert(['slug' => $post['slug']], $post + ['language' => 'hi-Latn', 'status' => 'published', 'published_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    private function posts()
    {
        return [
            [
                'title' => 'YouTube Shorts Download Kaise Kare: Short Ka Link, MP4 Quality Aur Gallery Tak',
                'slug' => 'youtube-shorts-download-kaise-kare',
                'excerpt' => 'YouTube Shorts download karne ka tareeqa: Short ka apna link copy karo, browser mein paste karo, vertical MP4 save karo. Shorts feed ka link kyun nahi chalta, 1080p kab milta hai aur Shorts ka audio.',
                'content' => <<<'HTML'
<p><strong>YouTube Shorts download kaise kare</strong>, is mein log sab se zyada ek jagah atakte hain: Shorts feed scroll karte hue Share dabate hain aur jo link copy hota hai woh kabhi kabhi feed ka hota hai, kisi ek Short ka nahi. Sahi link <code>youtube.com/shorts/</code> ke baad ek ID wala hota hai, aur wohi browser mein paste karne par vertical MP4 milta hai. Is guide mein link copy karne ka sahi tareeqa, Android aur iPhone par file gallery tak lana, quality ka sach, aur Shorts ka sirf audio nikalna, sab hai.</p>
<div class="note"><strong>Public Shorts, permission ke saath:</strong> Save-Froms sirf public Short ka link padhta hai, aapka YouTube account use nahi karta. Apne Shorts ya jin ka creator save karne ki ijazat deta hai, wohi rakhein.</div>
<h2>Short ka sahi link kaise copy karein</h2>
<ol>
<li>Shorts feed mein jis Short par ho, usi par <strong>Share</strong> dabao (daayin taraf teer ka icon).</li>
<li><strong>Copy link</strong> chuno. Link <code>https://youtube.com/shorts/AbCdEf…</code> jaisa hoga.</li>
<li>Agar link mein <code>/shorts/</code> ke baad kuch nahi, ya link <code>youtube.com/@channel/shorts</code> hai, to woh feed ya channel ka hai; Short ko dobara kholo aur phir Share karo.</li>
<li>Chrome ya Safari mein <a href="/youtube-shorts-downloader">YouTube Shorts downloader</a> page kholo, link paste karo, Download dabao.</li>
</ol>
<p>Computer par Short khulne ke baad address bar ka link seedha copy karo. <code>youtu.be/…</code> aur <code>youtube.com/watch?v=…</code> wala link bhi usi Short ka hota hai aur chalta hai.</p>
<h2>Result mein kya milta hai</h2>
<table><thead><tr><th>Row</th><th>Size (60 second Short)</th><th>Kab chunein</th></tr></thead><tbody>
<tr><td>MP4 360p / 480p</td><td>3 se 8 MB</td><td>WhatsApp status ya bhejne ke liye</td></tr>
<tr><td>MP4 720p</td><td>8 se 15 MB</td><td>Phone par dekhne ke liye, sab se sahi</td></tr>
<tr><td>MP4 1080p</td><td>15 se 30 MB</td><td>Edit karna ho ya TV par dikhana ho</td></tr>
<tr><td>MP3 audio</td><td>1 MB</td><td>Sirf sound chahiye</td></tr>
</tbody></table>
<p>Shorts vertical hote hain, is liye "720p" ka matlab 720 × 1280 hai. 1080p row tab aati hai jab creator ne 1080p mein upload kiya ho; phone se seedha banaye gaye zyadatar Shorts 1080p hote hain, lekin dusri jagah se liye gaye clips aksar 720p ya kam. 1080p par "Preparing" dikhe to ek minute ruko, video aur audio jor kar file banti hai.</p>
<h2>Android: Short gallery mein kaise aaye</h2>
<p>Chrome file ko Download folder mein rakhta hai; gallery kuch second mein dikha deti hai. Na dikhe to Files by Google kholo, Download mein file par long-press karo, Move → DCIM. WhatsApp status lagane ke liye file Download folder se bhi chuni ja sakti hai, 30 second ki limit yaad rahe, lamba Short WhatsApp khud kaat deta hai.</p>
<h2>iPhone: Files se Photos tak</h2>
<p>Safari file ko Files → Downloads mein rakhta hai. Files app kholo, Short par tap karo, Share → <strong>Save Video</strong>; ab Photos mein hai aur Instagram ya WhatsApp se share ho sakta hai. YouTube app ke andar se Safari nahi khulta, is liye link copy kar ke Safari alag kholna zaroori hai.</p>
<h2>Apna Short download karna (creator ke liye)</h2>
<p>Apne Shorts ke liye YouTube Studio app mein Short → three dots → Download seedha file deta hai, lekin woh 720p hota hai aur kabhi kabhi music hata deta hai. Yahan apne Short ka link paste karne par wohi 1080p rendition milta hai jo viewers dekhte hain, music ke saath. Original file hamesha phone ke camera roll ya drafts mein behtar quality mein hoti hai; use delete mat karo.</p>
<h2>Shorts ka sirf audio ya music</h2>
<p>Result ke Audio section mein MP3 rows hoti hain; 128 kbps Shorts ke liye kafi hai. Yaad rahe ke yeh Short ka mixed audio hai: creator ki awaaz aur music saath. Library ka gaana alag nahi milta; apne Short mein wahi sound lagana ho to YouTube ke "Use this sound" se lagao, credit khud ho jata hai. Detail <a href="/blog/youtube-shorts-to-mp3-public-audio-options-guide">Shorts to MP3 guide</a> mein hai.</p>
<h2>Shorts aur normal video mein farq</h2>
<p>Short asal mein 3 minute tak ki vertical YouTube video hi hai, is liye uska link <code>watch?v=</code> wale link ki tarah kaam karta hai aur rows bhi wahi aati hain. Farq sirf itna hai ke Shorts feed mein Share dabane par kabhi kabhi feed ka link copy hota hai; isi liye Short ko tap kar ke alag khol kar Share karna sab se safe hai. Jo Short kisi channel ke "Shorts" tab se khola ho, uska link bhi usi tarah chalta hai. YouTube ki normal video download karni ho to <a href="/blog/youtube-se-video-download-kaise-kare">YouTube se video download guide</a> dekho; steps same hain, bas quality rows zyada hoti hain.</p>
<h2>Download nahi ho raha?</h2>
<table><thead><tr><th>Masla</th><th>Wajah</th><th>Hal</th></tr></thead><tbody>
<tr><td>"Not supported" ya khali result</td><td>Feed ya channel ka link</td><td>Short khol kar uska apna Share link copy karo</td></tr>
<tr><td>Result mein koi aur video</td><td>Feed aage scroll ho gaya tha</td><td>Sahi Short par ruk kar Share karo</td></tr>
<tr><td>403 error</td><td>Purana link expire</td><td>Dobara paste karo</td></tr>
<tr><td>Sirf 360p row</td><td>Short hi kam quality ka tha</td><td>Koi tool isay bara nahi kar sakta</td></tr>
<tr><td>Download hi nahi shuru hota</td><td>YouTube app ke andar wala browser</td><td>Chrome ya Safari alag kholo</td></tr>
</tbody></table>
<h2>Aksar pooche jane wale sawal</h2>
<h3>Shorts download karne ka app kaunsa hai?</h3><p>Koi nahi chahiye; Chrome ya Safari wahi MP4 deta hai. Google Play par YouTube downloader apps allowed hi nahi.</p>
<h3>Shorts bina watermark ke milta hai?</h3><p>YouTube Shorts par watermark hota hi nahi, file saaf aati hai. Dusre platform se liye gaye Shorts par unka watermark pehle se hoga.</p>
<h3>Ek channel ke sab Shorts ek saath?</h3><p>Nahi, har Short ka apna link paste karna hota hai; computer par yeh kaam tez hai.</p>
<h3>Short ko Instagram Reel bana sakte hain?</h3><p>File ke hisab se haan, 9:16 hai. Lekin apna content ho ya ijazat ho, tab hi.</p>
<p><small>Guide published: October 29, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/youtube-shorts-download-kaise-kare-guide.svg',
                'meta_title' => 'YouTube Shorts Download Kaise Kare: Sahi Link Se MP4 Tak',
                'meta_description' => 'YouTube Shorts download kaise kare: Short ka apna link copy karo, browser mein paste karo, 720p ya 1080p MP4 save karo. Feed link, gallery aur audio ka hal.',
            ],
            [
                'title' => 'Instagram Se Photo Download Kaise Kare: Full Size JPG, Carousel Ki Sab Photos, Bina App',
                'slug' => 'instagram-se-photo-download-kaise-kare',
                'excerpt' => 'Instagram se photo download karne ka tareeqa jo screenshot se behtar hai: post ka link paste karo aur har photo full size JPG mein save karo, carousel ki sab photos bhi. Private account aur DP ka sach.',
                'content' => <<<'HTML'
<p><strong>Instagram se photo download kaise kare</strong>, is ka aam jawab screenshot hota hai, jo chhota, dhundla aur status bar ke saath aata hai. Behtar tareeqa: public post ka link browser mein paste karo, Instagram jo full size JPG rakhta hai (1080 ya 1440 pixel) wohi file seedha milti hai, aur carousel ki har photo alag row mein. Is guide mein Android, iPhone aur computer ke steps, size ka sach, DP aur private account ka jawab, aur file kahan jati hai, sab hai.</p>
<div class="note"><strong>Photo photographer ki hai:</strong> Save-Froms sirf public post padhta hai, aapka login use nahi karta. Apni photos, ya jin ka owner save karne ki ijazat deta hai, wohi rakhein, aur repost karein to naam ke saath credit dein.</div>
<h2>Photo download: 5 steps</h2>
<ol>
<li>Instagram mein photo post kholo (profile grid se tap kar ke, taake post apne page par khule).</li>
<li>Upar daayin taraf three dots dabao, ya paper-plane icon, phir <strong>Copy link</strong>. Link <code>instagram.com/p/</code> se shuru hoga.</li>
<li>Instagram band karo, Chrome ya Safari kholo.</li>
<li><a href="/instagram-photo-downloader">Instagram photo downloader</a> page par link paste karo, button dabao.</li>
<li>Result mein har photo ki JPG row aayegi, size ke saath; jo chahiye us par Download dabao.</li>
</ol>
<h2>Screenshot vs download: farq</h2>
<table><thead><tr><th></th><th>Screenshot</th><th>Download</th></tr></thead><tbody>
<tr><td>Size</td><td>Phone ki screen jitna, zoom par dhundla</td><td>1080 ya 1440 px, Instagram ki sab se bari copy</td></tr>
<tr><td>Crop</td><td>Status bar, caption, buttons saath</td><td>Sirf photo</td></tr>
<tr><td>Carousel</td><td>Har slide alag screenshot</td><td>Sab slides ek baar mein</td></tr>
<tr><td>Format</td><td>PNG, bara file</td><td>JPG, 150 se 600 KB</td></tr>
</tbody></table>
<h2>"Full size" ka matlab Instagram par</h2>
<p>Instagram har photo ko upload par compress karta hai aur sab se bari copy 1080 px lambi side rakhta hai; naye uploads jin mein "Upload at highest quality" on tha, 1440 px. Square photo 1080 × 1080 ya 1440 × 1440, portrait 1080 × 1350 ya 1440 × 1800, landscape 1080 × 566 ya 1440 × 754. Is se bari photo Instagram ke paas hai hi nahi; photographer ka original camera file alag baat hai jo woh khud de sakta hai.</p>
<h2>Carousel ki sab photos</h2>
<p>Carousel (stacked squares wala icon) mein 20 tak slides hoti hain, photo aur video mix. Ek hi link paste karne par har slide apni row mein aati hai, number ke saath; photos JPG, videos MP4. Link mein <code>?img_index=3</code> jaisa hissa ho to koi farq nahi, sab slides aati hain.</p>
<h2>DP, Story aur Reel ka cover</h2>
<ul>
<li><strong>Profile picture (DP):</strong> post nahi hai, link nahi hota, is liye download nahi hoti. Screenshot hi raasta hai, aur DP waise bhi 320 px ki hoti hai.</li>
<li><strong>Story ki photo:</strong> jab tak Story live hai, <a href="/instagram-stories-downloader">Instagram Stories downloader</a> se 1080 × 1920 JPG milti hai.</li>
<li><strong>Reel ka cover:</strong> Reel ka link video deta hai, cover alag nahi milta. Video ka ek frame chahiye to download kar ke screenshot lo.</li>
<li><strong>Highlights ki photo:</strong> Highlight ka link Stories page par chalta hai, bina time limit ke.</li>
</ul>
<h2>Private account ki photo</h2>
<p>Private account ki koi photo kisi tool se nahi aati, follow karte hon tab bhi, kyunki tool aapka login use nahi karta. "Private Instagram viewer" websites ya to password maangti hain (kabhi mat do) ya fake result dikhati hain. Link incognito mein kholo: login maange to private hai, aur photo owner se hi mangwani hogi.</p>
<h2>Android, iPhone aur computer par file</h2>
<table><thead><tr><th>Device</th><th>Kahan</th><th>Gallery mein</th></tr></thead><tbody>
<tr><td>Android (Chrome)</td><td>Download folder</td><td>Gallery khud dikhati hai; Files se Pictures mein move bhi kar sakte ho</td></tr>
<tr><td>iPhone (Safari)</td><td>Files → Downloads</td><td>Photo kholo, Share → Save Image</td></tr>
<tr><td>Computer</td><td>Downloads</td><td>Seedha khulti hai</td></tr>
</tbody></table>
<p>Computer par instagram.com se kaam tez hai: post kholo, address bar se link copy, paste, download. Ek hi account ki kai posts ho to yahi sab se tez tareeqa hai.</p>
<h2>Photo ka link kis tarah ka hona chahiye</h2>
<p>Sirf <code>instagram.com/p/</code> wala link photo deta hai. Profile ka link (<code>instagram.com/username/</code>), Explore ya hashtag page ka link, aur Reel ka link photo nahi dete; Reel ka link video deta hai. Short link <code>instagram.com/s/…</code> mile to ek baar browser mein kholo aur asal <code>/p/</code> link copy karo.</p>
<h2>Apni photos wapas chahiye?</h2>
<p>Apne account ki sab photos ek saath lene ke liye Instagram ki Settings → Your activity → Download your information se archive mangwao; 48 ghante tak lagte hain aur sab usi public size mein milti hain. Ek do photos ke liye link paste karna tez hai. Original photo phone ke camera roll mein Instagram se kai guna bari hoti hai, use mat mitao.</p>
<h2>Photo repost ya story mein lagani hai?</h2>
<p>Download ki hui photo apni Story ya post mein lagane se pehle owner ka naam caption mein likho aur asal post ka link do; Instagram ka apna "Add post to your story" option credit khud lagata hai aur usi ke liye bana hai. Business page ya brand ke liye kisi photographer ki photo use karni ho to likhit ijazat rakho, kyunki public post ka matlab free use nahi hota. Apni photos ke liye koi rukawat nahi, bas yaad rahe ke Instagram wali copy original se chhoti hai.</p>
<h2>Aksar pooche jane wale sawal</h2>
<h3>Instagram photo download karne ka app chahiye?</h3><p>Nahi. Browser full size JPG deta hai; apps wahi karte hain, ads ke saath.</p>
<h3>Photo HD mein milti hai?</h3><p>Instagram ki sab se bari copy milti hai, 1080 ya 1440 px. Is se bari Instagram ke paas nahi.</p>
<h3>Owner ko pata chalega?</h3><p>Nahi, koi account use nahi hota.</p>
<h3>Photo ko wallpaper ya print ke liye use kar sakte hain?</h3><p>Apni photo ho ya ijazat ho, tab. 1440 px print ke liye 4 × 6 inch tak theek hai.</p>
<p><small>Guide published: October 29, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/instagram-se-photo-download-kaise-kare-guide.svg',
                'meta_title' => 'Instagram Se Photo Download Kaise Kare: Full Size JPG',
                'meta_description' => 'Instagram se photo download kaise kare bina app ke: post ka link paste karo, har photo full size JPG mein save karo, carousel ki sab photos bhi. DP aur private.',
            ],
            [
                'title' => 'Video Se Audio Kaise Nikale: MP4 To MP3 Bina Converter App, Mobile Aur PC',
                'slug' => 'video-se-audio-kaise-nikale-mp4-to-mp3',
                'excerpt' => 'Video se audio nikalne ke do raste: online video ho to link paste kar ke seedha MP3 row lo, phone mein file ho to VLC ya free app se bina quality khoye convert karo. Kaunsa bitrate, aur converter apps se bachna kyun.',
                'content' => <<<'HTML'
<p><strong>Video se audio kaise nikale</strong>, is sawal ke do alag jawab hain aur log galat wala chun lete hain. Agar video YouTube ya TikTok par hai, to usay download kar ke convert karne ki zaroorat hi nahi: link paste karo aur result ka Audio section seedha MP3 deta hai. Agar video phone ya computer mein pehle se file hai, tab converter chahiye, aur free wale (VLC, FFmpeg) online sites se behtar hain kyunki file aapke device se bahar nahi jati. Yeh guide dono raste, sahi bitrate, aur online "MP4 to MP3 converter" sites ka khatra samjhata hai.</p>
<div class="note"><strong>Audio ka bhi malik hota hai:</strong> gaana ya awaaz video se alag karne se copyright nahi hatta. Apni recordings, license wala content, ya creator ki ijazat wala audio hi rakhein.</div>
<h2>Pehle yeh tay karo: video online hai ya file?</h2>
<table><thead><tr><th>Video kahan hai</th><th>Raasta</th><th>Converter chahiye?</th></tr></thead><tbody>
<tr><td>YouTube ya YouTube Shorts link</td><td><a href="/youtube-mp3-downloader">YouTube to MP3</a> page par link, Audio row download</td><td>Nahi</td></tr>
<tr><td>TikTok link</td><td><a href="/tiktok-mp3-downloader">TikTok MP3</a> page, Audio row</td><td>Nahi</td></tr>
<tr><td>Instagram, Facebook, X link</td><td>MP4 download karo, phir neeche wala tareeqa</td><td>Haan, device par</td></tr>
<tr><td>Phone ki gallery ya WhatsApp ki video</td><td>Neeche wala tareeqa</td><td>Haan, device par</td></tr>
<tr><td>Computer mein MP4/MOV/MKV</td><td>VLC ya FFmpeg</td><td>Haan, device par</td></tr>
</tbody></table>
<h2>Online video: converter ke bina MP3</h2>
<p>YouTube har video ka audio alag stream mein rakhta hai, is liye result mein MP3 48, 128 aur 256 kbps ki rows seedha milti hain, bina re-encode ke, wahi quality jo YouTube ke paas hai. Link paste karo, Audio section se row chuno, file Download folder mein. Poora tareeqa <a href="/blog/youtube-se-mp3-kaise-download-kare">YouTube se MP3 guide</a> mein hai. Instagram, Facebook aur X ek hi MP4 dete hain jisme video aur awaaz saath hoti hai, un ke liye device par convert karna padta hai.</p>
<h2>Android par video se audio (bina online site ke)</h2>
<ol>
<li>Play Store se <strong>VLC for Android</strong> install karo (free, ads nahi). Ya koi bhi "video to mp3" app jo Play Store par 4+ rating aur bina login ka ho.</li>
<li>VLC mein video kholo, three dots → <strong>Convert</strong> (naye versions mein) ya "Play as audio".</li>
<li>Seedha tareeqa VLC mein na mile to <strong>Media Converter</strong> app (Play Store, free) mein video chuno, output MP3, bitrate 128 ya 192, Convert.</li>
<li>File Music ya Download folder mein milti hai; music app khud dikha deti hai.</li>
</ol>
<p>Online "MP4 to MP3 converter" websites par video upload hoti hai, jo personal video ke liye theek nahi, aur 100 MB se bari file par aksar fail ho jati hain. Offline app mein file phone se bahar nahi jati.</p>
<h2>iPhone par</h2>
<p>Files app aur Shortcuts se bina kisi app ke ho jata hai: Shortcuts mein naya shortcut, "Encode Media" action, "Audio only" on, format M4A ya MP3, phir video select karo. Nahi to VLC for iOS install karo, video import karo aur Convert. Audio Files app mein save hoti hai; Apple Music mein local file seedha nahi aati, VLC ya Documents app mein chalti hai.</p>
<h2>Computer par (Windows/Mac): VLC ya FFmpeg</h2>
<table><thead><tr><th>Tareeqa</th><th>Steps</th><th>Quality</th></tr></thead><tbody>
<tr><td>VLC</td><td>Media → Convert/Save → file chuno → Profile "Audio – MP3" → Start</td><td>Achi; bitrate 128 default</td></tr>
<tr><td>FFmpeg (command)</td><td><code>ffmpeg -i video.mp4 -vn -b:a 192k audio.mp3</code></td><td>Sab se sahi, bitrate aap chunte ho</td></tr>
<tr><td>Audacity</td><td>Import video (FFmpeg library ke saath) → Export MP3</td><td>Achi, trim bhi ho jata hai</td></tr>
</tbody></table>
<p>Kai files ek saath (Mac/Linux): <code>for f in *.mp4; do ffmpeg -i "$f" -vn -b:a 192k "${f%.mp4}.mp3"; done</code>. Windows par VLC ki Convert window mein kai files queue ho jati hain.</p>
<h2>Audio sirf ek hisse ka chahiye?</h2>
<p>Poora audio nikalne ke baad ek gaana ya ek hissa kaatne ke liye Android par Lexis Audio Editor, iPhone par Voice Memos mein import kar ke trim, aur computer par Audacity. FFmpeg mein seedha kaat sakte ho: <code>ffmpeg -ss 00:01:00 -to 00:02:30 -i video.mp4 -vn -b:a 192k part.mp3</code>, jahan pehla time shuru aur doosra khatam hai.</p>
<h2>Kaunsa bitrate chunein</h2>
<p>Video ke andar audio pehle se compressed hota hai (aksar AAC 128 kbps). MP3 banate waqt 320 kbps chunne se quality nahi barhti, sirf file bari hoti hai. Baat-cheet, lecture aur bhajan ke liye 128 kbps; gaano ke liye 192 se 256. Source se zyada kabhi nahi. 10 minute ka 128 kbps MP3 lagbhag 10 MB hota hai.</p>
<h2>Awaaz saaf karni hai ya tez?</h2>
<p>Video se nikala hua audio aksar dheema ya shor wala hota hai, khaas kar phone se banayi gayi recording mein. Audacity (computer, free) mein Effect → Normalize se awaaz barabar ho jati hai aur Noise Reduction se background ka shor kam. Phone par Lexis Audio Editor mein bhi Normalize aur Noise reduction dono hain. Yeh kaam MP3 banane se pehle karo, kyunki MP3 dobara save karne par thori quality jati hai; sab se acha tareeqa hai pehle M4A ya WAV mein nikalo, saaf karo, phir aakhir mein ek baar MP3 export karo.</p>
<h2>MP3 zaroori hai ya M4A chalega?</h2>
<p>Phone ki video ka audio bina re-encode ke nikalna ho to FFmpeg se <code>ffmpeg -i video.mp4 -vn -c:a copy audio.m4a</code> chalao: ek second mein, zero quality loss. M4A har phone aur computer par chalta hai; MP3 tab hi banao jab koi purana player ya car stereo sirf MP3 maangta ho.</p>
<h2>Aksar pooche jane wale sawal</h2>
<h3>Video se audio nikalne ka sab se acha app kaunsa hai?</h3><p>VLC, free aur bina ads. Online video ke liye app hi nahi chahiye, Audio row seedha milti hai.</p>
<h3>Quality kam hoti hai?</h3><p>Re-encode mein thori si, sunne mein farq nahi. <code>-c:a copy</code> se bilkul nahi.</p>
<h3>WhatsApp ki video se audio nikal sakte hain?</h3><p>Haan, woh phone mein file hai; VLC ya Media Converter se.</p>
<h3>Online converter site safe hai?</h3><p>Public video ke liye zaroorat nahi, personal video ke liye theek nahi: file unke server par jati hai.</p>
<p><small>Guide published: October 29, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/video-se-audio-kaise-nikale-guide.svg',
                'meta_title' => 'Video Se Audio Kaise Nikale: MP4 To MP3 Bina Converter',
                'meta_description' => 'Video se audio kaise nikale: online video ho to link paste kar ke seedha MP3 lo; phone ya PC ki file ho to VLC ya FFmpeg se bina quality khoye MP4 to MP3 karo.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['youtube-shorts-download-kaise-kare', 'instagram-se-photo-download-kaise-kare', 'video-se-audio-kaise-nikale-mp4-to-mp3'])->delete();
    }
}
