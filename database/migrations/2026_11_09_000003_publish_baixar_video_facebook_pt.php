<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Portuguese (pt-BR) Facebook video guide (2026-11-09), third post in the
 * Brazil-focused pt cluster (after the pt Reels and pt photo guides).
 * Brazil is the site's #1 impression country and uses Facebook heavily,
 * yet there was no Portuguese Facebook post. Targets "baixar vídeo do
 * facebook (pelo link)", "como baixar vídeo do facebook no celular" and
 * fb.watch links.
 *
 * Backend is VERIFIED for this platform (a public Facebook video link
 * returns MP4), and the /facebook-downloader tool page is live (200).
 * Distinct platform, so no cannibalisation with the pt Instagram posts;
 * tool-funnel CTA built in from the start.
 */
class PublishBaixarVideoFacebookPt extends Migration
{
    public function up()
    {
        $now = now();
        foreach ($this->posts() as $post) {
            DB::table('blog_posts')->updateOrInsert(['slug' => $post['slug']], $post + ['language' => 'pt', 'status' => 'published', 'published_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    private function posts()
    {
        return [
            [
                'title' => 'Como Baixar Vídeo do Facebook pelo Link (MP4, Sem App)',
                'slug' => 'baixar-video-do-facebook-pelo-link-guia',
                'excerpt' => 'Baixe vídeos públicos do Facebook pelo link, em MP4 e sem aplicativo — inclusive links fb.watch e Reels. Passo a passo no Android e iPhone, qual link copiar e o que fazer quando o vídeo não baixa.',
                'content' => <<<'HTML'
<div class="note"><strong>Baixe agora:</strong> cole o link de um vídeo público do Facebook no <a href="/facebook-downloader">downloader de vídeos do Facebook</a> — é <strong>grátis</strong>, funciona no navegador e <strong>não precisa de app nem cadastro</strong>. Escolha o MP4 e salve. O guia abaixo mostra qual link copiar (inclusive fb.watch) e o que fazer quando o vídeo não baixa.</div>
<p>Baixar um vídeo do Facebook é rápido quando você usa o link certo. Não precisa de aplicativo, de extensão nem de gravador de tela — o link do vídeo público é tudo o que o <a href="/facebook-downloader">downloader do Facebook</a> precisa. Este guia mostra como copiar o link (de um post, de um Reels ou um link <code>fb.watch</code>), como escolher o MP4 e o que fazer quando o vídeo não baixa, no Android, iPhone, Windows ou Mac. Baixe apenas vídeos públicos que você criou ou tem permissão para usar.</p>

<h2>Como baixar um vídeo do Facebook — passo a passo</h2>
<ol>
<li>Abra o vídeo público no Facebook.</li>
<li>Toque nos três pontos do post (ou no botão <strong>Compartilhar</strong>) e escolha <strong>Copiar link</strong>. No computador, você também pode copiar o endereço da barra do navegador com o vídeo aberto.</li>
<li>Cole o link no <a href="/facebook-downloader">downloader de vídeos do Facebook</a>.</li>
<li>Escolha a qualidade do MP4 e toque em Baixar.</li>
</ol>
<p>O resultado depende do vídeo de origem e da disponibilidade no momento. O Save-Froms só acessa conteúdo público e não entra em contas, grupos fechados ou perfis privados.</p>

<h2>Qual link copiar: vídeo, Reels, fb.watch ou perfil?</h2>
<p>O Facebook usa vários formatos de endereço. Um vídeo pode ter um link longo com <code>/videos/</code>, um link curto <code>fb.watch</code>, ou um link de Reels. Todos funcionam quando levam a um único vídeo público. Já o link de um perfil ou de uma página inteira não identifica qual vídeo você quer — abra o vídeo e use <strong>Compartilhar → Copiar link</strong>.</p>
<p>Se um link <code>fb.watch</code> abrir uma tela de login ou de instalação do app em vez do vídeo, abra-o no navegador e copie o endereço final que aparecer na barra.</p>

<h2>Qualidade: qual MP4 escolher</h2>
<p>Você costuma ver mais de uma opção de qualidade. Para assistir no celular, uma opção intermediária (SD) já fica boa e ocupa menos espaço. Para tela maior ou para guardar, escolha a opção de maior resolução (HD), se o vídeo tiver. Lembre-se: a qualidade nunca é maior do que a versão que o Facebook guardou — um download não cria detalhe que não existe no original.</p>

<h2>Baixar vídeo do Facebook no Android</h2>
<p>Depois de tocar em Baixar, confirme no aviso do navegador e espere terminar. O MP4 costuma ir para a pasta Downloads e aparecer na galeria. Se o navegador abrir o vídeo em vez de salvar, use o menu do navegador e escolha Baixar.</p>

<h2>Baixar vídeo do Facebook no iPhone</h2>
<p>O Safari salva o vídeo no app Arquivos, geralmente em Downloads. Use o indicador de download do Safari para ver o progresso. Ao terminar, abra o MP4 em Arquivos e use Compartilhar se quiser salvar uma cópia em Fotos.</p>

<h2>Por que um vídeo do Facebook pode não baixar</h2>
<ul>
<li>O vídeo é privado, de um grupo fechado ou de um perfil que exige login.</li>
<li>O link copiado aponta para um perfil ou página, não para um vídeo único.</li>
<li>O vídeo foi apagado ou tem restrição de região.</li>
<li>O link <code>fb.watch</code> abriu uma tela de login em vez do vídeo — abra no navegador e copie o endereço final.</li>
<li>O link gerado expirou — cole o link do vídeo de novo.</li>
</ul>
<p>Teste o vídeo em uma janela anônima. Se ele não tocar publicamente ali, o downloader também não conseguirá acessá-lo.</p>

<h2>Vídeo, Reels e outras redes</h2>
<p>Para um Reels do Facebook, o caminho é o mesmo — cole o link no <a href="/facebook-reels-downloader">downloader de Reels do Facebook</a>. E se você também baixa do Instagram, veja <a href="/blog/baixar-video-instagram-links-publicos-guia">como baixar vídeo do Instagram pelo link</a> e <a href="/blog/baixar-reels-do-instagram-pelo-link-guia">como baixar Reels do Instagram</a>.</p>

<h2>Direitos autorais e privacidade</h2>
<p>Ser público não é o mesmo que ter permissão para reutilizar. Não baixe vídeos privados ou de grupos fechados, não reposte o conteúdo de alguém sem autorização e mantenha os créditos do criador. Para uso de marca, guarde a permissão por escrito junto com o arquivo.</p>

<h2>Perguntas frequentes</h2>
<h3>Como baixo um vídeo do Facebook pelo link?</h3><p>Abra o vídeo, toque em Compartilhar e depois em Copiar link, cole no downloader do Facebook e escolha o MP4. Funciona no navegador, sem app e sem cadastro.</p>
<h3>Dá para baixar um link fb.watch?</h3><p>Sim. Cole o link fb.watch direto. Se ele abrir uma tela de login, abra no navegador, copie o endereço final do vídeo e use esse link.</p>
<h3>Dá para baixar vídeo privado do Facebook?</h3><p>Não. Só é possível salvar vídeos públicos. Vídeos privados, de grupos fechados ou que exigem login não podem ser acessados.</p>
<h3>Dá para baixar em HD?</h3><p>Se o vídeo tiver uma versão HD, você pode escolhê-la. A qualidade máxima é sempre a que o Facebook guardou para aquele vídeo.</p>
<h3>Onde o vídeo fica depois de baixar?</h3><p>No Android, na pasta Downloads (e na galeria); no iPhone, no app Arquivos, de onde você pode salvar em Fotos.</p>
<p><small>Guia publicado: 9 de novembro de 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-baixar-video-do-facebook-pelo-link-guia.svg',
                'meta_title' => 'Baixar Vídeo do Facebook pelo Link — MP4, Sem App',
                'meta_description' => 'Baixe vídeos públicos do Facebook pelo link, em MP4 e sem app — inclusive links fb.watch e Reels. Passo a passo no Android e iPhone, e o que fazer quando o vídeo não baixa.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['baixar-video-do-facebook-pelo-link-guia'])->delete();
    }
}
