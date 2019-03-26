<?php

// at the top of a template is a shockingly lazy place to declare this
function show($data, $field) {
    $output = "";
    if(isset($data[$field])) {
        $output .= "<tr><td>";
        $output .= $field;
        $output .= "</td><td>";
        $output .= $data[$field];
        $output .= "</td><tr>\n";
    }
    return $output;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example that shows off a responsive product landing page.">
    <title>Landing Page &ndash; Layout Examples &ndash; Pure</title>
    
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-" crossorigin="anonymous">
    
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-old-ie-min.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
    <!--<![endif]-->
    
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    
        <link rel="stylesheet" href="css/marketing.css">
</head>
<body>

<div class="header">
    <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
        <a class="pure-menu-heading" href="/">Number Insights</a>

    </div>
</div>


<div class="content-wrapper">
    <div class="content">
        <h2 class="content-head is-center">Intelligence and accurate phone number information</h2>

        <div class="pure-g">
            <div class="l-box-lrg pure-u-1 pure-u-md-2-5">
                <?php if(!$insight): ?>

                <?php if($error): ?>
                <em>An error occurred. </em>
                <?php if ($error_message) echo $error_message ?>
                <?php endif; ?>
                <form class="pure-form pure-form-stacked" action="/insight" method="post">
                    <fieldset>

                        <label for="number">Phone number in E.164 format (e.g. 447700900000)</label>
                        <input id="number" type="text" name="number" placeholder="Phone number">

                        <label for="basic" class="pure-radio">
                            <input id="basic" type="radio" name="insight" value="basic" checked>
                            Basic Insight (free)
                        </label>
                        <label for="standard" class="pure-radio">
                            <input id="standard" type="radio" name="insight" value="standard">
                            Standard Insight
                        </label>
                        <label for="advanced" class="pure-radio">
                            <input id="advanced" type="radio" name="insight" value="advanced">
                            Advanced Insight
                        </label>
                        <button type="submit" class="pure-button">Get Insight</button>
                    </fieldset>
                </form>
                <?php endif; ?>
            </div>

            <div class="l-box-lrg pure-u-1 pure-u-md-3-5">
                <h4>Phone number insights</h4>
                <?php if($insight):
                    echo "<p>\n";
                    echo "<table class=\"pure-table pure-table-striped\">\n";
                    echo "<td><b>Field</b></td><td><b>Value</b></td>";
                    echo show($insight,'country_code');
                    echo show($insight,'country_name');
                    echo show($insight,'national_format_number');
                    echo show($insight,'international_format_number');
                    echo show($insight,'valid_number');
                    echo show($insight,'reachable');
                    echo show($insight,'remaining_balance');
                    echo "</table>\n";
                    echo "</p>\n";

                else: ?>
                <p>
                    Use our Number Insights API to get inside information on a phone number and its status.
                </p>

                <?php endif; ?>
            </div>
        </div>

    </div>

    <div class="ribbon l-box-lrg pure-g">
        <div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">

            <h2 class="content-head content-head-ribbon">Learn More</h2>

            <p>
                Visit our <a href="https://developer.nexmo.com/number-insight/overview">Developer Portal</a> for more information about the Number Insight API.
            </p>
        </div>
    </div>

</div>




</body>
</html>
