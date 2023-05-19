<div class="pageTovar">

    <ul class="breadcrumbs">
<?php
    foreach($tovar['breadcrumbs'] as $name=>$url) {
?>
        <li><?php if($name!='Главная') { echo '&gt; ';} ?><a href="<?php echo htmlspecialchars($url);?>"><?php echo htmlspecialchars($name);?></a></li>
<?php
    }
?>
    </ul>

    <h2>Товар: <i><?php echo htmlspecialchars($tovar['name']);?></i></h2>
    <img src="https://loremflickr.com/150/150?random=<?php echo htmlspecialchars($tovar['id']);?>" alt="img" title="img" />
    <div><b>Цена:</b> <?php echo htmlspecialchars($tovar['price']);?></i></div>
</div>
