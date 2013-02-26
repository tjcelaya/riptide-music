<!-- album display -->
<div class="full-album-listing">
    <img width="150px" height="150px" src="{$imageURL|escape}"/>
    <h1>{$albumName}</h1>
    <form action='/~celaya/riptideMusic/api/found/{$dID}' method='POST'>
    <button  value='{$dID}' type='submit'>
        <i class='icon-plus'></i><i class='icon-pencil'></i> Be a founding writer!
    </button>
    </form>
    <br/>
    <a href="/~celaya/riptideMusic/artist.php?name={$artistName|escape:'url'}">
        <p class="artistName">{$artistName}</p>
    </a>
    <p>{$released}</p>
    {foreach $genre as $g}
        <a style="margin-right:0.2em" href="genre/{$g}">{$g}</a>
    {/foreach}
    <p>{$avgRating}</p>
    <div class="tags">
        {foreach $tags as $tag}
            <a href="/~celaya/riptideMusic/tag.php?searchTags={$tag}">{$tag}</a>
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
