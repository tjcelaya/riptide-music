<!-- album display -->
<div class="full-album-listing">
    <img width="150" height="150" src="img/{$artistName|escape} - {$albumName|escape}({$released}).jpg"/>
    <a href="album.php?name={$albumName|escape:'url'}&artist={$artistName|escape:'url'}">
        <h1>{$albumName}</h1>
    </a>
    <a href="artist.php?name={$artistName|escape:'url'}">
        <p class="artistName">{$artistName}</p>
    </a>
    <p>{$released}</p>
    {foreach $genres as $g}
        <a style="margin-right:0.2em" href="genre/{$g}">{$g}</a>
    {/foreach}
    <p>{$avgRating}</p>
    <div class="tags">
        {foreach $tags as $tag}
            <a href="tag.php?searchTags={$tag}">{$tag}</a>
        {/foreach}
    </div>
    <table>
        {foreach $tracklist as $track}
            <tr>
                <td>{$track[1]}</td>
                <td>{$track[2]}</td>
                <td>{$track[0]}</td>
            </tr>
        {/foreach}
    </table>
    <hr>
</div>
