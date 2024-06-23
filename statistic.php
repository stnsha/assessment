<?php include 'inc/header.php' ?>
<div class="container mx-8 my-4" style="width: 650px;">
    <h3 class="text-center mb-4">Total customers by phone brands.</h3>
    <canvas id="myChart"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    const ctx = document.getElementById('myChart');

    function fetchData() {
        $.ajax({
            type: 'GET',
            url: 'queries.php',
            data: {
                action: 'get_total_users_by_brand'
            },
            success: function(response) {
                const data = JSON.parse(response);
                const labels = data.map(item => item.brand_name);
                const values = data.map(item => item.total_users);

                renderChart(labels, values);
            },
            error: function(xhr, status, error) {
                console.log(xhr);
            }
        });
    }

    function renderChart(labels, values) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Customers',
                    data: values,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    fetchData();
</script>
<?php include 'inc/footer.php' ?>