<?php

$this->inherits("./layout.tpl.php");

$this->block("style");
?>
<link rel=stylesheet href=styles.css type="text/css">
<?php
$this->endblock();
$this->block("doc");
?>
<h1><?php echo $title ?> - <?php echo $foo; ?></h1>
<?php
    echo $this->renderFile('loremipsum.tpl.php');
    echo $this->renderFile('loremipsum.tpl.php');
$this->endblock();
$this->block("script", "before");
?>
<script>
alert("Hello !");
</script>
<?php
$this->endblock();
?>
