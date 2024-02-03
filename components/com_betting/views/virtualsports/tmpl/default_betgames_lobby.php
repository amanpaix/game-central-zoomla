<?php
defined('_JEXEC') or die('Restricted Access');

$games = json_decode(Constants::BETGAMES_DATA);

?>

<div id="betgames">

    <div class="game-list-wrap type2">
        <ul class="game-list">
            <?php
            foreach (  $games as $item ){
                ?>
                <li betgames_game_id="<?php echo $item->tableId ?>" >
                    <a href="javascript:void(0);">
                        <span class="item">
                            <img data-data-src="/images/betgames/<?php echo $item->tableId ?>.jpg" alt="<?php echo $item->name; ?>" class="lazyloaded ls-is-cached" data-src="/images/betgames/<?php echo $item->tableId ?>.jpg" src="/images/betgames/<?php echo $item->tableId ?>.jpg">
                        </span>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>



