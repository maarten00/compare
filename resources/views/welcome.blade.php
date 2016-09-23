<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Compare shizzle</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color : #fff;
            color            : #636b6f;
            font-family      : 'Raleway', sans-serif;
            font-weight      : 100;
            height           : 100vh;
            margin           : 0;
        }

        .full-height {
            height : 100vh;
        }

        .flex-center {
            align-items     : center;
            display         : flex;
            justify-content : center;
        }

        .position-ref {
            position : relative;
        }

        .top-right {
            position : absolute;
            right    : 10px;
            top      : 18px;
        }

        .content {
            text-align : center;
        }

        .title {
            font-size : 84px;
        }

        .links > a {
            color           : #636b6f;
            padding         : 0 25px;
            font-size       : 12px;
            font-weight     : 600;
            letter-spacing  : .1rem;
            text-decoration : none;
            text-transform  : uppercase;
        }

        .m-b-md {
            margin-bottom : 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md">Compare shizzle</div>
        <p>Upload the CSV files you wish to compare. Currently only the following column structure is supported:</p>
        <code>
            <table class="flex-center">
                <th style="border: 1px black solid">ID</th>
                <th style="border: 1px black solid">post_title</th>
                <th style="border: 1px black solid">post_content</th>
                <th style="border: 1px black solid">post_type</th>
            </table>
        </code>
        <p>On a WP database you can use the following query to retrieve these fields You might want to add your own post
            type exceptions depending on the post types you want to compare</p>
        <code>SELECT `ID`, `post_title`, `post_content`, `post_type` FROM `wp_posts` WHERE `post_type` NOT IN
            ('revision', '_pods_field', '_pods_pod', 'nav_menu_item', 'attachment')</code>
        <p>Make sure to use the same delimiters in both CSV files. Currently only <code>;</code> delimiters and
            <code>"</code> encapsulating are supported/required.</p>
        <hr/>
        <form enctype="multipart/form-data" action="" method="post">
            <?= csrf_field() ?>
            <label>Compare* <input type="file" name="compareFrom" required/></label><br>
            <label>Compare to <input type="file" name="compareTo" required/><br></label><br>
            <input type="submit" value="GO"/>
        </form>
        <hr/>

    </div>
</div>
</body>
</html>
