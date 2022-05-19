<div class="tabset">
    <ul class="pagination">
        <?php
        if ($results['pageCurrent'] > 1){ ?>
            <li class="prev"><a href=".?action=archive&amp;pageCurrent=<?= $results['pageCurrent'] - 1?>"> << </a></li>
        <?php } ?>
        <?php
        if ($results['pageCurrent'] <= 5) {
            for ($page = 1; $page <= $results['pageCurrent'] + 2 && $page <= $results['pageСount']; $page++) { ?>
                <?php if ($page == $results['pageCurrent']){ ?>
                    <li><a class="active"><?= $results['pageCurrent']?></a></li>
                <?php } else { ?>
                    <li class="prev"><a href=".?action=archive&amp;pageCurrent=<?= $page?>"><?= $page?></a></li><?php } ?>
            <?php } ?>

        <?php } else{?>
            <li class="prev"><a href=".?action=archive&amp;pageCurrent=1">1</a></li>
            <li ><a class="skipping">...</a></li>
            <?php for ($page = ($results['pageCurrent'] - 2); $page <= $results['pageCurrent'] + 2 && $page <= $results['pageСount']; $page++) { ?>
                <?php if ($page == $results['pageCurrent']){ ?>
                    <li><a class="active"><?= $results['pageCurrent']?></a></li>
                <?php } else { ?>
                    <li class="prev"><a href=".?action=archive&amp;pageCurrent=<?= $page?>"><?= $page?></a></li>
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php if (($results['pageCurrent'] + 4) < $results['pageСount']) {?>
            <li ><a class="skipping">...</a></li>
            <li class="prev"><a href=".?action=archive&amp;pageCurrent=<?= $results['pageСount']?>"><?= $results['pageСount']?></a></li>
        <?php } else{?>
            <?php for ($page = ($results['pageCurrent'] + 3); $page <= $results['pageСount']; $page++) { ?>
                <li class="prev"><a href=".?action=archive&amp;pageCurrent=<?= $page?>"><?= $page?></a></li>
            <?php } ?>
        <?php } ?>

        <?php if ($results['pageCurrent'] < $results['pageСount']) { ?>
            <li class="next"><a href=".?action=archive&amp;pageCurrent=<?= $results['pageCurrent']+1?>">>></a></li>
        <?php } ?>
    </ul><br><br>
</div>