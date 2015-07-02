<center>
    <nav>
        <ul class="pagination">
            <?php if($previous): ?>
            <li>
                <a href="<?= $previous ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="<?= $next ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</center>