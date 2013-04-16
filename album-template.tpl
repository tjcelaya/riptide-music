<!-- album display -->

<div class="full-album-listing">
    <img width="150" height="150" src="img/{$artistName|escape} - {$albumName|escape}({$released}).jpg"/>
    <h1><a href="album.php?id={$albumID}">{$albumName}</a></h1>
    <p class="artistName"><a href="artist.php?id={$artistID}">{$artistName}</a></p>
    <p><a class="release" href="adv-search.php?year={$released}">{$released}</a></p>
    
    {foreach $genres as $g}
        <a class="genre-link" href="genre.php?name={$g}">{$g}</a>
    {/foreach}
  
    <p> <div class="star"> </div> </p>

    <div class="tags">
     {foreach $tags as $tag}
      {$tagcnt = $tagcnt + 1}  
 
        <form class="increase-tag" style='display:inline; padding-right: 5px; margin-top: 5px;' method="post" name="inc{$tagcnt}" id="inc{$tagcnt}" action="./api/tag">
        <input type='hidden' name='key' value='1' />
        <input type='hidden' name='weight' id="iweight{$tagcnt}" value='{$tag['weight'] + 1}' />
        <input type='hidden' name='albumID' value='{$albumID}' />
        <input type='hidden' name='tagName' value='{$tag['tagName']}' /> 
            <div class="btn-group">
                <button class="btn btn-mini btn-info" type="button"><i class="icon-tag icon-white"></i> 
                <a href="tag.php?searchTags={$tag['tagName']}" style="color: white;">{$tag['tagName']}
                    [<span id="incw{$tagcnt}">{$tag['weight']}</span>]</a> 
                </button>   
                <button class='btn btn-mini btn-info' type="button" id="binc{$tagcnt}" value="+" >+</button>
                <script>
$('#binc{$tagcnt}').click(function()
 {literal} {   {/literal}
    var str = $('#inc{$tagcnt}').serialize();
 {literal}  
     $.post('./api/tag',
          str,
          function(data)
     {     
{/literal} 
              $('#incw{$tagcnt}').html({$tag['weight']+1});
              {$tag['weight'] = $tag['weight'] + 1}
              $('#iweight{$tagcnt}').value({$tag['weight']+1});
                  
{literal}  
          });
 });                
 </script> 
{/literal}   
                
            </div>
        </form>  

      {/foreach} 


      
        <!--<button class="btn btn-mini btn-info newTag" type="submit" name="submit" value="Tag Album"> <i class="icon-tag icon-white"></i> <i>new...</i>
        </button> -->
        <div class="btn-group" style="width: 50%;  white-space: nowrap;">
            <a class="btn btn-mini btn-info dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="icon-tag icon-white"></i> <i>new...</i>
            <span class="caret"></span>
            </a>
            <ul class="dropdown-menu"  >
                <form method="post" id="newTag" style='display:inline; padding-right: 5px; margin-top: 5px;' action="./api/tag">
                <input type='hidden' name='key' value='1' />

                &nbsp;&nbsp;
                <i class="icon-tag" > </i> 
                <input type="text" style="max-width: 150px;" name="tagName" size="7">
                <input type="hidden" name="weight" size="7" value="1">
                <input type="hidden" name="albumID" size="7" value="{$albumID}">
                &nbsp;
                </form>
            </ul>
        </div>
      


    </div>
    <table class='shown-tracks'>
        {foreach $tracks as $track}
            {if !is_null($templatetype)}
                <tr><td>{$track[1]}</td><td>{$track[2]}</td><td>{$track[0]}</td></tr>
            {/if}

        {/foreach}
    </table>
</div>

