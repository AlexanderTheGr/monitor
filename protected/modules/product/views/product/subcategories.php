<h3><?php echo $category->_categoryLangs_[$this->settings["language"]]->name; ?></h3>
<ul>
    <?php foreach ((array)$category->_childs_ as $subcategory):?>
    <?php if ($tecdocdata[$subcategory->id]->articles_count > 0):?>
    <li class='subcategoriesli'><?php echo $subcategory->_categoryLangs_[$this->settings["language"]]->name?></li>
    <?php endif;?>
    <?php endforeach;?>
</ul>

<script>

</script>