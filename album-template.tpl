<!-- album display -->
<div class="full-album-listing">
    <img width="150px" height="150px" src="{$imageURL|escape}"/>
    <h1>{$albumName}</h1>
    <a href="/~celaya/riptideMusic/artist.php?name={$artistName|escape:'url'}">
        <p class="artistName">{$artistName}</p>
    </a>
    <p>{$released}</p>
    <a href="genre/{$genre}">
        <p>{$genre}</p>
    </a>
    <p>{$avgRating}</p>
    <p class="tags">
        {foreach $tags as $tag}
            <a href="/~celaya/riptideMusic/tag.php?searchTags={$tag}">
                <span>{$tag}</span>
            </a>
        {/foreach}
    </p>
    <table>
        {foreach $tracks as $track}
            <tr>
                <td>{$track.name}</td>
                <td>{$track.duration}</td>
            </tr>
        {/foreach}
    </table>

    <hr>
</div>
