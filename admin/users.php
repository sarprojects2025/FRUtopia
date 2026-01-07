<?php include('./header.php'); ?>

<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="mb-0">Dashboard - Active</h5>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container mt-3 table-scroll">
  <h4>User List</h4>

  <!-- Export Button -->
  <button id="exportBtn" class="btn btn-primary mb-3" style="display:none;">Export CSV</button>

  <!-- Placeholder for table -->
  <div id="alertTable"></div>
</div>

<?php include('./footer.php'); ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = "https://sarsspl.com/FRUtopia/api/get_users.php";
    const container = document.getElementById("alertTable");
    const exportBtn = document.getElementById("exportBtn");

    // Call API
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data && data.data && data.data.length > 0) {
                let table = "<table id='dataTable' class='table table-bordered table-striped'>";
                table += "<thead><tr>";

                // Headers
                Object.keys(data.data[0]).forEach(header => {
                    table += "<th>" + header + "</th>";
                });
                table += "</tr></thead><tbody>";

                // Rows
                data.data.forEach(row => {
                    table += "<tr>";
                    Object.values(row).forEach(cell => {
                        table += "<td>" + (cell !== null ? cell : "") + "</td>";
                    });
                    table += "</tr>";
                });

                table += "</tbody></table>";
                container.innerHTML = table;

                // Show export button
                exportBtn.style.display = "inline-block";
            } else {
                container.innerHTML = "<p class='text-danger'>No data found or API error.</p>";
            }
        })
        .catch(error => {
            console.error("Error fetching API:", error);
            container.innerHTML = "<p class='text-danger'>Failed to load data.</p>";
        });

    // Export CSV Function
    exportBtn.addEventListener("click", function () {
        let table = document.getElementById("dataTable");
        if (!table) return;

        let rows = table.querySelectorAll("tr");
        let csv = [];

        rows.forEach(row => {
            let cols = row.querySelectorAll("td, th");
            let rowData = [];
            cols.forEach(col => {
                let text = col.innerText.replace(/"/g, '""'); // escape quotes
                rowData.push('"' + text + '"');
            });
            csv.push(rowData.join(","));
        });

        // Download CSV
        let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(csvFile);
        link.download = "users_list.csv";
        link.click();
    });
});
</script>
