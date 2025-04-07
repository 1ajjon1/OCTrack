<nav class="menu">
    <ul>
        <li><a href="Favourites_A2.php">Favourites</a></li>
        <?php if (isset($_SESSION['username'])): ?>
            <li><a href="./ModularPHP/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="./Frontpage.php">Login/Register</a></li>
        <?php endif; ?>
    </ul>
</nav>