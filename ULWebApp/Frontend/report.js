document.addEventListener("DOMContentLoaded", function () {
    const reportForm = document.getElementById("reportForm");
    const reportTableBody = document.getElementById("reportTableBody");
    const chartCanvas = document.getElementById("reportChart").getContext("2d");
    let reportChart;

    reportForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const startDate = document.getElementById("startDate").value;
        const endDate = document.getElementById("endDate").value;

        fetch(`../backend/generate_report.php?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                displayReport(data);
                updateChart(data.total_income, data.total_expenses);
            })
            .catch(error => console.error("Error fetching report:", error));
    });

    function displayReport(data) {
        reportTableBody.innerHTML = `
            <tr>
                <td>${data.start_date}</td>
                <td>${data.end_date}</td>
                <td>${data.total_income.toFixed(2)}</td>
                <td>${data.total_expenses.toFixed(2)}</td>
                <td>${(data.total_income - data.total_expenses).toFixed(2)}</td>
            </tr>
        `;
    }

    function updateChart(income, expenses) {
        if (reportChart) {
            reportChart.destroy();
        }

        reportChart = new Chart(chartCanvas, {
            type: "bar",
            data: {
                labels: ["Income", "Expenses"],
                datasets: [{
                    label: "Amount",
                    data: [income, expenses],
                    backgroundColor: ["#28a745", "#dc3545"]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
