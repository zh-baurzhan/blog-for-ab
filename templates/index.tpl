{extends file="layout.tpl"}

{block name="content"}
    {foreach $categories as $category}
        <section class="section">
            <h2>{$category.name}</h2>

            <div class="grid">
                {foreach $category.articles as $a}
                    {include file="_article_card.tpl" a=$a}
                {/foreach}
            </div>

            <a class="btn" href="/category/{$category.slug}">Все статьи</a>
        </section>
        {foreachelse}
        <p>Статей пока нет.</p>
    {/foreach}
{/block}
