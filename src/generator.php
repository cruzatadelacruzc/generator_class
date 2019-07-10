<?php
/**
 * Created by PhpStorm.
 * User: Cesar
 * Date: 9 jul 2019
 * Time: 13:20
 */


if ((isset($_POST['project']) && !empty($_POST['project'])) && (isset($_POST['bundle']) && !empty($_POST['bundle']))) {
    $project = $_POST['project'];
    $bundle = $_POST['bundle'];

    require_once('Processor.php');
    try {
        $processor = new Processor();
        $result = $processor->generateProject($bundle, $project);
        if ($result) {
            session_start();
            $_SESSION['smt_generator_output_2019'] = $processor->output;
            header('Location: ../index.php');
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }


} else {
    echo sprintf('You should fill fields the names: project and bundle');
}