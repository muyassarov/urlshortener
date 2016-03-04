<?php
/**
 * Created by PhpStorm.
 * User: behruz
 * Date: 2/27/16
 * Time: 12:58 PM
 */
$b = base_url();
?>
<div class="container">
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <h1>URL Shortener</h1><hr/>
    <form action="/welcome/index" method="get" onsubmit="return false;">

        <div id="message_block"></div>

        <div class="form-group">
            <label for="url_long">Paste Long URL</label>
            <input type="url" id="url_long" name="url_long" class="form-control input-lg" required="required" autofocus="autofocus"/>
        </div>
        <div class="form-group">
            <label for="url_short">Desired Short URL</label>
            <input type="text" id="url_short" name="url_short" class="form-control input-lg"/>
        </div>
        <button id="submit_button" class="btn btn-primary btn-lg">Submit</button>
        <button id="clean_button" type="reset" class="btn btn-default btn-lg">Clean</button>
    </form>
</div>
</div>