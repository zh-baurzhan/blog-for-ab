{extends file="layout.tpl"}

{block name="content"}
    <article>
        <h1>{$article.title}</h1>

        <div class="meta">
            {foreach $article.categories as $c}
                <a href="/category/{$c.slug}">{$c.name}</a>{if !$c@last}, {/if}
            {/foreach}
            · {$article.published_at|date_format:"%d.%m.%Y"}
            · просмотров: {$article.views}
        </div>

        {if $article.image}
            <p><img class="cover" src="{$article.image}" alt="{$article.title}"></p>
        {/if}

        {if $article.description}
            <p><strong>{$article.description}</strong></p>
        {/if}

        <div>{$article.body|escape|nl2br nofilter}</div>
    </article>

    {if $similar}
        <section class="section">
            <h2>Похожие статьи</h2>
            <div class="grid">
                {foreach $similar as $a}
                    {include file="_article_card.tpl" a=$a}
                {/foreach}
            </div>
        </section>
    {/if}

    <p><a href="/">&larr; На главную</a></p>
{/block}
