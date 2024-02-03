<?php
if(!isset($this->data) || !isset($this->url)) {
    Session::sessionRemove();
    Redirection::to(Redirection::LOGIN);
}
//exit(json_encode($this->url).'--'.json_encode($this->data));
?>

<div style="width:100%;max-width: 340px;padding:20px 15px;margin: 20px auto;text-align: center;line-height: 150%;font-size: 16px;">
<img src="https://www.sabanzuri.com/images/loader-blank.gif" alt="Please Wait" style="
    display: inline-block;
    margin-bottom: 10px;
">
<p>Please wait. It might take a few seconds.</p>
<p>Please do not refresh the page or click the "Back" or "Close" button of your browser.</p>
</div>

<form action="<?php echo $this->url; ?>" method="post" id="form-submit">
<?php
foreach($this->data as $name => $value) {
    ?>
    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>"/>
    <?php
}
?>
</form>
<?php
        $player_info_mixpanel = Utilities::getPlayerLoginResponse();
?>
<script>

        document.getElementById('form-submit').submit();
</script>
