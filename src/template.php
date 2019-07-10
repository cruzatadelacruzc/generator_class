<?php
/**
 * Created by PhpStorm.
 * User: Cesar
 * Date: 9 jul 2019
 * Time: 15:09
 */

$name = @$parameters['name'];
$namespace = @$parameters['namespace'];
$parent = @$parameters['parent'];
$parent_use = @$parameters['parent_use'];
?>
<?php echo '<?php' ?>


namespace <?php echo $namespace ?>;

<?php echo $parent_use . PHP_EOL ?>

class <?php echo $name ?> extends <?php echo $parent. PHP_EOL ?>
{

}


