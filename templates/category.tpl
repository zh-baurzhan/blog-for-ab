{extends file="layout.tpl"}

{block name="content"}
    <h1>{$category.name}</h1>
    {if $category.description}
        <p>{$category.description}</p>
    {/if}

    {* сортировка *}
    <p class="sort">
        Сортировка:
        {if $sort == 'date'}
            <span class="active">по дате</span>
        {else}
            <a href="/category/{$category.slug}?sort=date">по дате</a>
        {/if}
        {if $sort == 'views'}
            <span class="active">по просмотрам</span>
        {else}
            <a href="/category/{$category.slug}?sort=views">по просмотрам</a>
        {/if}
    </p>

    <div class="grid">
        {foreach $articles as $a}
            {include file="_article_card.tpl" a=$a}
        {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    {* пагинация *}
    {if $pages > 1}
        <nav class="pagination">
            {if $page > 1}
                <a href="/category/{$category.slug}?sort={$sort}&page={$page - 1}">< Назад</a>
            {/if}

            {for $p = 1 to $pages}
                {if $p == $page}
                    <span class="active">{$p}</span>
                {else}
                    <a href="/category/{$category.slug}?sort={$sort}&page={$p}">{$p}</a>
                {/if}
            {/for}

            {if $page < $pages}
                <a href="/category/{$category.slug}?sort={$sort}&page={$page + 1}">Вперёд ></a>
            {/if}
        </nav>
    {/if}

    <p><a href="/">< На главную</a></p>
{/block}
