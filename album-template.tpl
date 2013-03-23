<!-- album display -->
<div class="full-album-listing">
    <img width="150" height="150" src="img/{$artistName|escape} - {$albumName|escape}({$released}).jpg"/>
    <a href="album.php?id={$albumID}">
        <h1>{$albumName}</h1>
    </a>
    <a href="artist.php?name={$artistName|escape:'url'}">
        <p class="artistName">{$artistName}</p>
    </a>
    <p>{$released}</p>
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
    <table>
        {foreach $tracks as $track}
            <tr>
                <td>{$track[1]}</td>
                <td>{$track[2]}</td>
                <td>{$track[0]}</td>
            </tr>
        {/foreach}
    </table>
    <hr>
</div>
