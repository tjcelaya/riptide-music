<!-- album display -->
<div class="full-album-listing">
    <img width="150" height="150" src="img/{$artistName|escape} - {$albumName|escape}({$released}).jpg"/>
    <h1><a href="album.php?id={$albumID}">{$albumName}</a></h1>
    <p class="artistName"><a href="artist.php?id={$artistID}">{$artistName}</a></p>
    <p><a class="release" href="adv-search.php?year={$released}">{$released}</a></p>
    {foreach $genres as $g}
        <a class="genre-link" href="genre.php?name={$g}">{$g}</a>
    {/foreach}
    <p>{$avgRating}</p>
    <div class="tags">
      {foreach $tags as $tag}
        <div class='tag'>
            <i class='icon-tag'></i>
            <a href="tag.php?searchTags={$tag}">{$tag}</a>
        </div>
      {/foreach}
    </div>
    <table class='shown-tracks'>
        {foreach $tracks as $track}
            {if $track@iteration == 3 && is_null($templatetype)}
                <tr class='tracks-ellipsis'><td></td><td>...</td><td></td></tr>
                </table>
                <table class='hidden-tracks'>
                <tr><td>{$track[1]}</td><td>{$track[2]}</td><td>{$track[0]}</td></tr>
            {elseif $track@iteration > 3 && is_null($templatetype)}
                <tr><td>{$track[1]}</td><td>{$track[2]}</td><td>{$track[0]}</td></tr>
            {else}
                <tr>
                    <td>{$track[1]}</td>
                    <td>{$track[2]}</td>
                    <td>{$track[0]}</td>
                </tr>
            {/if}

        {/foreach}
    </table>
</div>
