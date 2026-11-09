<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Portuguese (pt-BR) Instagram photo / carousel guide (2026-11-09),
 * second post in the Brazil-focused pt cluster (see the Reels pt post
 * from the same day). Brazil is the site's #1 impression country but had
 * almost no Portuguese content; this extends the proven Instagram-pt vein
 * to the photo sub-intent ("baixar foto do instagram", "baixar foto do
 * instagram em alta resolução", "baixar todas as fotos do carrossel").
 *
 * Distinct from the pt video and pt Reels posts (photo is a separate
 * query set, so no cannibalisation), and mirrors the hi-Latn/ar clusters
 * that each already have a photo post. Backend is VERIFIED for this
 * intent: an Instagram carousel/photo link returns JPG images, and the
 * dedicated /instagram-photo-downloader tool page is live (200). Tool-
 * funnel CTA is built in from the start.
 */
class PublishBaixarFotoInstagramPt extends Migration
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
                'title' => 'Como Baixar Foto do Instagram pelo Link (HD, Todas do Carrossel)',
                'slug' => 'baixar-foto-do-instagram-pelo-link-hd-guia',
                'excerpt' => 'Baixe fotos públicas do Instagram pelo link, em alta resolução e sem app — inclusive todas as imagens de um carrossel de uma vez. Passo a passo no Android e iPhone, qual link copiar e o que fazer quando a foto não baixa.',
                'content' => <<<'HTML'
<div class="note"><strong>Baixe agora:</strong> cole o link de uma foto pública no <a href="/instagram-photo-downloader">downloader de fotos do Instagram</a> — é <strong>grátis</strong>, funciona no navegador e <strong>não precisa de app nem cadastro</strong>. Em um carrossel, você salva <strong>todas as imagens</strong>. O guia abaixo mostra qual link copiar e o que fazer quando a foto não baixa.</div>
<p>Baixar uma foto do Instagram é simples quando você usa o link certo. Não precisa de aplicativo, de conta nem de print — o link de compartilhamento de um post público é tudo o que o <a href="/instagram-photo-downloader">downloader de fotos</a> precisa. E se o post for um carrossel, você consegue salvar <strong>todas as fotos</strong> de uma vez, cada uma em arquivo próprio. Este guia mostra qual link copiar, como obter a melhor qualidade e o que fazer quando a foto não baixa, no Android, iPhone, Windows ou Mac. Salve apenas fotos que você criou ou tem permissão para usar.</p>

<h2>Como baixar uma foto do Instagram — passo a passo</h2>
<ol>
<li>Abra o post com a foto que você quer salvar.</li>
<li>Toque no menu de três pontos (ou em Compartilhar) e escolha <strong>Copiar link</strong>.</li>
<li>Cole o link no <a href="/instagram-photo-downloader">downloader de fotos do Instagram</a>.</li>
<li>Veja as imagens retornadas e baixe a que você quer — ou todas, se for um carrossel.</li>
</ol>
<p>O resultado depende do post de origem e da disponibilidade no momento. O Save-Froms só acessa conteúdo público e não entra em contas privadas.</p>

<h2>Baixar todas as fotos de um carrossel</h2>
<p>Um carrossel é aquele post com várias imagens que você desliza para o lado. Você não precisa salvar uma por uma: ao colar o link do post, o downloader lista <strong>todas as fotos</strong> do carrossel, e cada uma vira um arquivo JPG separado. Isso é útil para coleções, catálogos de produtos ou um conjunto de fotos que você mesmo publicou.</p>
<p>Copie o link do post inteiro (não tente "copiar imagem"), porque é o link do post que identifica todas as fotos de uma vez.</p>

<h2>Qual link copiar: foto, carrossel ou perfil?</h2>
<p>O link de um post normalmente contém <code>/p/</code> seguido de um código. Esse é o link que você cola. Já o link de um perfil tem muitos posts, então não diz ao downloader qual foto você quer — e a aba Explorar é uma coleção, não um item único. Abra o post e use <strong>Copiar link</strong>.</p>

<h2>Qualidade: dá para baixar a foto em HD?</h2>
<p>Você recebe a imagem na melhor resolução que o Instagram guardou para aquele post — normalmente a mesma que aparece no app. Um downloader não "cria" detalhe que não existe no arquivo: se a foto foi publicada comprimida, essa é a versão disponível. Ainda assim, baixar pelo link entrega uma imagem bem melhor do que um print da tela.</p>

<h2>Baixar foto do Instagram no Android</h2>
<p>Depois de tocar em Baixar, confirme no aviso do navegador. O JPG costuma ir para a pasta Downloads e aparecer na galeria em um álbum de Downloads. Se não aparecer logo, abra o app Arquivos uma vez para a galeria reconhecer a imagem.</p>

<h2>Baixar foto do Instagram no iPhone</h2>
<p>O Safari salva a imagem no app Arquivos. Para colocá-la no app Fotos, abra o arquivo e use Compartilhar → Salvar Imagem. Se nada acontecer ao tocar no botão, verifique a permissão de download do Safari ou tente novamente.</p>

<h2>Por que uma foto pode não baixar</h2>
<ul>
<li>A conta é privada, ou o post exige um usuário logado.</li>
<li>O criador apagou ou arquivou o post.</li>
<li>O link copiado aponta para um perfil ou para a aba Explorar, não para um post.</li>
<li>O conteúdo tem restrição de região ou de conta.</li>
<li>O link gerado expirou — analise o link do post de novo.</li>
</ul>
<p>Teste o post em uma janela anônima. Se ele não abrir publicamente ali, o downloader também não conseguirá acessá-lo.</p>

<h2>Foto, vídeo, Reels e Stories são diferentes</h2>
<p>Cada tipo de mídia tem seu caminho. Para vídeos, veja <a href="/blog/baixar-video-instagram-links-publicos-guia">como baixar vídeo do Instagram pelo link</a>; para Reels, veja <a href="/blog/baixar-reels-do-instagram-pelo-link-guia">como baixar Reels do Instagram</a>. Para fotos e carrosséis, continue aqui e use o <a href="/instagram-photo-downloader">downloader de fotos</a>.</p>

<h2>Direitos autorais e privacidade</h2>
<p>Ser público não é o mesmo que ter permissão para reutilizar. Não baixe fotos de contas privadas, não reposte o trabalho de alguém sem autorização e mantenha os créditos do criador. Para uso de marca, guarde a permissão por escrito junto com os arquivos.</p>

<h2>Perguntas frequentes</h2>
<h3>Como baixo uma foto do Instagram pelo link?</h3><p>Abra o post, toque em Copiar link, cole no downloader de fotos e baixe a imagem. Funciona no navegador, sem app e sem cadastro.</p>
<h3>Dá para baixar todas as fotos de um carrossel de uma vez?</h3><p>Sim. Cole o link do post e o downloader lista todas as imagens do carrossel, cada uma como um arquivo JPG separado.</p>
<h3>A foto vem em alta resolução?</h3><p>Você recebe a melhor versão que o Instagram guardou para aquele post. É bem melhor que um print, mas não é possível ter mais detalhe do que o arquivo original tem.</p>
<h3>Dá para baixar foto de conta privada?</h3><p>Não. Só é possível salvar fotos de posts públicos. Contas privadas não podem ser acessadas.</p>
<h3>Onde a foto fica depois de baixar?</h3><p>No Android, na pasta Downloads (e na galeria); no iPhone, no app Arquivos, de onde você pode salvar em Fotos.</p>
<p><small>Guia publicado: 9 de novembro de 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-baixar-foto-do-instagram-pelo-link-hd-guia.svg',
                'meta_title' => 'Baixar Foto do Instagram pelo Link — HD, Sem App',
                'meta_description' => 'Baixe fotos públicas do Instagram pelo link, em alta resolução e sem app — e todas as fotos de um carrossel de uma vez. Passo a passo no Android e iPhone.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['baixar-foto-do-instagram-pelo-link-hd-guia'])->delete();
    }
}
