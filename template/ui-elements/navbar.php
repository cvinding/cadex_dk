<ul>
    <li><a href='/'>Hjem</a></li>
    <li><a href='/products'>Produkter</a></li>
    <?php echo ($login) ? "<li><a href='/news'>News</a></li>" : "" ?>
    <?php echo ($login) ? "<li><a href='/logout'>Logout</a></li>" : "<li><a href='/login'>Login</a></li>"; ?>
</ul>