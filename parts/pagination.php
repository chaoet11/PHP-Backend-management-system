<div class="col">
    <nav aria-label="Page navigation example">
        <ul class="pagination">

            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=1">
                    <i class="fa-solid fa-angles-left"></i>
                </a>
            </li>

            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            </li>

            <?php for($i = $page - 2; $i <= $page + 2; $i++) : 
                if($i >= 1 && $i <= $totalPages) : ?>
                    <li class="page-item page-num <?= $i==$page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
            <?php endif; 
            endfor; ?>

            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $totalPages ?>">
                    <i class="fa-solid fa-angles-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>