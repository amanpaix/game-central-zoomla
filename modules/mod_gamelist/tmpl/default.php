<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");


//$gameTypesArr = array_keys($gameData);

$gameTypesArr = Utilities::getCasinoGamesCategory();
$featuredGames = Utilities::getCasinoGamesList("Featured Games");

?>

<div class="entry-header has-post-format">
    <div class="my-acc-title mb-5">
        <h1>Bingo Rooms</h1>
        <p class="sub-title">play more to win more</p>
    </div>
</div>

<div class="game-list-wrap type2"><!--<div class="game-list-head">
<div class="icon-wrap"><img src="images/nav-icons/nav-livegames.png" alt="" /></div>
<div class="title">New Games</div>
<div class="action-wrap"><a href="#" class="btn btn-text">View All<span class="icon-arrow"> </span></a></div>
</div>-->
    <div class="rownav-tabs-wrapper">
        <div class="rownav-tabs-box">

            <div class="rownav-tabs">
<!--                <button game_type_btn="featured" class="tab-item active">Featured Games<span class="num">(--><?php //echo $gameCount ?><!--)</span></button>-->

                <?php
                $cnt = 0;
                $sum = 0;
                  foreach ( $gameTypesArr as $types ){
                      $cnt++;
                      $sum += (int)$types['length'];
                      ?>
                      <button game_type_btn="<?php echo $types['lobby_cat']; ?>" class="tab-item <?php echo $types['lobby_cat'] == 'Featured Games' ? 'active' : '' ?>"><?php echo $types['lobby_cat'] ?><span class="num">(<?php echo $types['length']  ?>)</span></button>
                      <?php
                  }

                ?>
                <button game_type_btn="all" class="tab-item">All Games<span class="num">(<?php echo $sum ?>)</span></button>
            </div>
        </div>
    </div>
    <ul class="game-list">

        <?php

            foreach ( $featuredGames as $game ){
            ?>
            <li game_id="<?php echo $game->tableId; ?>" game_cat="<?php echo $game->gameTypeUnified; ?>" game_type="<?php echo $game->lobby_cat; ?>">
                <a href="javascript:void(0);">
                    <span class="item">
                        <img data-src="<?php echo $game->img_src; ?>" alt="" class=" lazyloaded" src="<?php echo $game->img_src; ?>">
                    </span>
                </a>
            </li>
            <?php
        }
        ?>

    </ul>
</div>


<script type="application/javascript">

     gameListMasterCnt = <?php echo count($featuredGames); ?>;

     <?php
        if( count($featuredGames) >= Constants::GAME_LIST_LIMIT ){
            ?>
             gameListOffset = gameListOffset + gameListLimit;
            <?php
     }
        else
        {
            ?>
            gameListOffset = gameListLimit;
            <?php
        }
     ?>

     $(document).ready(function () {
         jQuery('.rownav-tabs').slick({
             vertical: false,
             slidesToShow: 8,
             slidesToScroll: 1,
             arrows: true,
             infinite: false,
             variableWidth: true,
             responsive: [
                 {
                     breakpoint: 1024,
                     settings: {
                         vertical: false,
                         slidesToShow: 8,
                         slidesToScroll: 1,
                         arrows: true,
                         infinite: false,
                         variableWidth: true,
                     }
                 },
                 {
                     breakpoint: 600,
                     settings: {
                         vertical: false,
                         slidesToShow: 4,
                         slidesToScroll: 1,
                         arrows: true,
                         infinite: false,
                         variableWidth: true,
                     }
                 },
                 {
                     breakpoint: 480,
                     settings: {
                         vertical: false,
                         slidesToShow: 2,
                         slidesToScroll: 1,
                         arrows: true,
                         infinite: false,
                         variableWidth: true,
                     }
                 }
             ]
         });
     });

</script>

