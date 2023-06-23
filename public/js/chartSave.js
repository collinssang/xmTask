$(document).ready(function(){
    var ctx = document.getElementById('priceChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Open Price',
                data: openPrices,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
                {
                    label: 'Close Price',
                    data: closePrices,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Price'
                    }
                }
            },
            bezierCurve : false,
            animation: {
                onComplete: done
            }
        }
    });

    //This function is used to store the image in data-src in a tag
    function done(){
        var url = chart.toBase64Image();
        $("#link2").attr("data-src", url);
    }
});
