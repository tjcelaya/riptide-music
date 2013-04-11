<!-- album display -->

<div class="full-album-listing">
    <img width="150" height="150" src="img/{$artistName|escape} - {$albumName|escape}({$released}).jpg"/>
    <h1><a href="album.php?id={$albumID}">{$albumName}</a></h1>
    <p class="artistName"><a href="artist.php?id={$artistID}">{$artistName}</a></p>
    <p><a class="release" href="adv-search.php?year={$released}">{$released}</a></p>
    
    {foreach $genres as $g}
        <a class="genre-link" href="genre.php?name={$g}">{$g}</a>
    {/foreach}
  
   <p> <div class="star" style=" width: 100%; min-width:350px; "> </div> </p>
    <p>{$avgRating} </p>

    <div class="tags">
      {foreach $tags as $tag}  
        <button class="btn btn-mini btn-info" type="button"><i class="icon-tag icon-white"></i> 
        <a href="tag.php?searchTags={$tag['tagName']}" style="color: white; ">{$tag['tagName']} [{$tag['weight']}]</a> 
        <!-- Increment Tag link needs a better look, 2 forms post to the second one every time (naming?) -->

<!--        <form method="post" name="dec" action="./api/tag">
        <input type='hidden' name='key' value='1' />
        <input type='hidden' name='weight' value='{$tag['weight'] - 1}' />
        <input type='hidden' name='albumID' value='{$albumID}' />
        <input type='hidden' name='tagName' value='{$tag['tagName']}' />
         <input type="submit" name="dec" value="dec" /> --> 

        <form method="post" name="inc" action="./api/tag">
        <input type='hidden' name='key' value='1' />
        <input type='hidden' name='weight' value='{$tag['weight'] + 1}' />
        <input type='hidden' name='albumID' value='{$albumID}' />
        <input type='hidden' name='tagName' value='{$tag['tagName']}' />
         <input type="submit" name="action" value="+" />
        <i class="icon-upload icon-white"> </i> 
        
        </form>  

    </button>  

          
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

