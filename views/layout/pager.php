<center>
    <nav>
        <ul class="pagination">
            <?php if($previous): ?>
            <li>
                <a id="prev-page" href="#" link="<?= $previous ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a id="next-page" href="#" link="<?= $next ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    Страница <?= intval($page) ?>
</center>