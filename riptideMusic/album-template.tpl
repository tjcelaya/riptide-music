<!-- album display -->
<div class="full-album-listing">
    <img width="150px" height="150px" src="{$imageSrc}"/>
    <h1>{$albumName}</h1>
    <a href="/~celaya/riptideMusic/artist?name={$artistName|escape:'url'}">
        <p class="artistName">{$artistName}</p>
    </a>
    <p>{$year}</p>
    <a href="#/{$genre}">
        <p>{$genre}</p>
    </a>
    <p>{$avgRating}</p>
    <p class="tags">
        {foreach $tags as $tag}
            <a href="#tag/{$tag}">
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
