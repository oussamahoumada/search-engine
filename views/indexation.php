<?php
require_once(str_replace("\\views", "", __DIR__) . "/helpers/helper.php");
require_once(str_replace("\\views", "", __DIR__) . "/helpers/LireRecursDir.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function printChart(realLen, lenAfterCleaning, diff, chartName, title) {
            console.log(chartName);
            ctx = document.getElementById(chartName);
            data = {
                labels: [
                    'real Length',
                    'length after cleaning',
                    'Differenece'
                ],
                datasets: [{
                    label: 'words count ',
                    data: [realLen, lenAfterCleaning, diff],
                    backgroundColor: [
                        'rgb(' + Math.random() * 256 + ',' + Math.random() * 256 + ',' + Math.random() * 256 + ')',
                        'rgb(' + Math.random() * 256 + ',' + Math.random() * 256 + ',' + Math.random() * 256 + ')',
                        'rgb(' + Math.random() * 256 + ',' + Math.random() * 256 + ',' + Math.random() * 256 + ')',
                    ],
                    hoverOffset: 4
                }]
            };
            const options = {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        color: 'black',
                        align: 'center',
                        position: 'top',
                        text: chartName,
                    },
                },
            };
            config = {
                type: 'doughnut',
                data: data,
                options: options
            };
            new Chart(ctx, config);
        }     
    </script>
</head>

<body style="text-align: center;">
    <?php
    explorerDir(str_replace("\\views", "", __DIR__) . "/txtFiles");
    ?>
</body>

</html>