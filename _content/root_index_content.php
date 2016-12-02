<section id="hero" class="header">
    <div class="container">
        <div class="row">
            <div class="row text-xs-center">
                <span class="display-3">Dashboard</span>
            </div>
            <div class="col-xs-2 offset-xs-5">
                <hr/>
            </div>
        </div>
    </div>
</section>

<?php
include "root_index_". $_SESSION['userdata']['role'] ."_content.php";