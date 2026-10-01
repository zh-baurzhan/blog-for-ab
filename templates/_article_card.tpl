<article class="card">
    {if $a.image}
        <a href="/post/{$a.slug}"><img src="{$a.image}" alt="{$a.title}"></a>
    {/if}
    <h3><a href="/post/{$a.slug}">{$a.title}</a></h3>
    <div class="meta">
        {foreach $a.categories as $c}
            <a href="/category/{$c.slug}">{$c.name}</a>{if !$c@last}, {/if}
        {/foreach}
        · {$a.published_at|date_format:"%d.%m.%Y"}
        · просмотров: {$a.views}
    </div>
    <p>{$a.description}</p>
</article>
