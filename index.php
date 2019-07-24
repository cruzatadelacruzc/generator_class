<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GENERATOR</title>
    <style type="text/css">
		.required:before{
			content: '* ';
			color: rgb(255,0,0.85);
		}
		input:focus:valid{
			outline: none;
			border-color: #33bd7f;
		}

		input:invalid{
			border-color: red;
		}

		input:focus:invalid{
			color: red;
		}
        .alert {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 25px;
            -webkit-box-sizing:content-box;
            -moz-box-sizing:content-box;
            box-sizing:content-box;
            box-shadow: 0 0 3px gray;
            border-radius: 5px;
        }

        .alert-succes {
            border: 5px solid #4CAF50;
        }

        fieldset {
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }

        input {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 2px solid #ccc;
            display: inline-block;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php session_start();
if (isset($_SESSION['smt_generator_output_2019'])): ?>
    <div class="alert alert-succes">
        <ul>
            <?php foreach ($_SESSION['smt_generator_output_2019'] as $item): ?>
                <li>
                    <?php echo $item ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php unset($_SESSION['smt_generator_output_2019']); endif; ?>
<form action="src/generator.php" method="post">
    <fieldset>
        <legend>Form to create project</legend>
        <div class="form-group">
            <label for="project_name" class="required">Project structure:</label>
            <input id="project_name" type="text" name="project" required placeholder="Controller\Modules"/>
            <label for="bundle_name" class="required" >Bundle name:</label>
            <input type="text" name="bundle" id="bundle_name" required placeholder="WooBundle"/>
            <label for="bundle_name" class="required" >Class name prefix:</label>
            <input type="text" name="prefix" id="bundle_name" required placeholder="Woo"/>
            <input type="submit" value="Generate">
        </div>
    </fieldset>
</form>
</body>
</html>