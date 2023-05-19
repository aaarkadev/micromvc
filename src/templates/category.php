<div class="pageCategory">
    <h2>Категории</h2> 
<?php
foreach($treeWithTovars as $category) {
    $offset = str_repeat('*',$category['level']);
?>

    <h3><?php echo $offset.' Категория: '.htmlspecialchars($category['name']);?></h3>
    <dl>
<?php
        if(empty($category['tovars'])) {
            echo ' </dl>';
            continue;
        }
?>
        <dt>Товары:</dt>
<?php
        foreach($category['tovars'] as $tovar) {
?>
        <dd>
            <img src="https://loremflickr.com/50/50?random=<?php echo htmlspecialchars($tovar['id']);?>" alt="img" title="img" />
            <a href="<?php echo $tovar['url'];?>"><?php echo htmlspecialchars($tovar['name']);?></a>
            <div class="price">цена: <?php echo htmlspecialchars($tovar['price']);?></div>
        </dd>
<?php
        }
?>
    </dl>

<?php
}

?>
</div>
