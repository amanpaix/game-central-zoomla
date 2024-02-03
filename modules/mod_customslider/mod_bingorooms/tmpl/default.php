<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';


$rooms = Utilities::getActiveBingoRooms();

//exit(json_encode($rooms));


?>



<div class="section-title-wrap">
    <h1 class="title">Bingo</h1>
    <div class="ctab-wrap"><button class="ctab active">Most Popular</button> <button class="ctab">New</button> <button class="ctab">Special</button> <button class="ctab">90 Balls</button> <button class="ctab">75 Balls</button></div>
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
                            <div class="game-img"><img src="images/game-logo/bingo-icon16.jpg" alt="" /></div>
                            <div class="game-title"><?php echo $item->roomName; ?></div>
                            <div class="game-desc">Bingo inspired by the TV show with a Beat the Banker feature prize.</div>
                            <div class="ball-count"><span class="ga-label"><img src="images/icons/icon-balls.svg" alt="no of balls" /><span>Balls</span></span> <span class="ga-value">75</span></div>
                            <div class="head-count"><span class="ga-label"><img src="images/icons/icon-players.svg" alt="players" /><span>Players</span></span> <span class="ga-value">155</span></div>
                            <div class="tkt-prize"><span class="ga-label"><img src="images/icons/icon-bingo.svg" alt="bingo" /><span>Tkt Price</span></span> <span class="ga-value">$0.4</span></div>
                            <div class="max-prize"><span class="ga-label"><img src="images/icons/icon-maxprize.svg" alt="Max Prize" /><span>Max Prize</span></span> <span class="ga-value">$80.0</span></div>
                            <div class="game-link"><button class="btn btn-primary">Play Now</button></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>

    </div>
</div>



