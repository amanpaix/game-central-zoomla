<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';


$rooms = Utilities::getActiveBingoRooms();

//exit(json_encode($rooms));


?>


<div class="entry-header has-post-format">
    <div class="my-acc-title mb-5">
        <h1>Bingo Rooms</h1>
        <p class="sub-title">play more to win more</p>
    </div>
</div>

<div class="game-card-list-wrap">
    <div class="game-card-list">

        <?php
        foreach ( $rooms->data as $item  ){
        ?>

        <div class="game-card-wrap">
            <div class="game-card">
                <div class="game-wrap">
                    <div class="game">
                        <div class="game-img"><img src="images/game-logo/bingo-icon26.jpg" alt="" /></div>
                        <div class="game-title"><?php echo $item->roomName; ?></div>
                        <div class="game-desc">Based on the popular slot with bonus features.</div>
                        <div class="ball-count"><span class="ga-label"><img src="images/icons/icon-balls.svg" alt="no of balls" /><span>Balls</span></span> <span class="ga-value">75</span></div>
<!--                        <div class="head-count"><span class="ga-label"><img src="images/icons/icon-players.svg" alt="players" /><span>Players</span></span> <span class="ga-value">158</span></div>-->
                        <div class="tkt-prize"><span class="ga-label"><img src="images/icons/icon-bingo.svg" alt="bingo" /><span>Tkt Price</span></span> <span class="ga-value">INR 0.5</span></div>
                        <div class="max-prize"><span class="ga-label"><img src="images/icons/icon-maxprize.svg" alt="Max Prize" /><span>Max Prize</span></span> <span class="ga-value">INR 90.0</span></div>
                        <div class="game-link"><button class="btn btn-primary" play_bingo="true" room_id="<?php echo $item->roomId ?>" >Play Now</button></div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        }
        ?>

    </div>
</div>
