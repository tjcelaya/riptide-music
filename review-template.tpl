<!-- album display -->

<div class="full-album-listing">
  {foreach $reviews as $review}
    <h1>Handle: <a href="profile.php?id={$review['display_name']}">{$review['display_name']}</a></h1>
    <p class="artistName">{$review['review']}</p>
    </foreach>
</div>

