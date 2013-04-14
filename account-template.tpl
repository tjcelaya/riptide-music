        <!-- account display-->
         <div id="userStats" class="clearfix">
            <div class="pic">
              <a href="#"><img src="img/avatars/{$accountName}.jpg" width="150" height="150" /></a>
            </div>
            <div class="data">
                <h1>{$displayName}</h1>
                <div class="sepBorderless"></div>
                <br>
                <p>
                  <button class="btn btn-mini btn-info" type="button"><i class="icon-tag icon-white"></i>Tags aplenty</button>
                </p>
    
                <div class="sep"></div>
                <ul class="numbers clearfix">
                  <li>Reviews<strong>{$reviewCount}</strong></li>
                  <li>Friends<strong>{$friendCount}</strong></li>
                  <li class="nobrdr">Rates<strong>{$rateCount}</strong></li>
                </ul>
            </div>
          </div>
