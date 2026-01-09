<?php include('./header.php'); 
$_user_id = $_SESSION['user_id'];
?>

<div class="container">
    <h4>OTP</h4>

    <!-- Placeholder for table -->
    <div class="card">
        <div id="panel" class="card-body">

            <div class="d-flex justify-content-start align-items-center mb-2">
                <input type="search" id="searchInput" class="form-control w-25" placeholder="Search...">
                <button id="clearSearchBtn" class="btn btn-outline-secondary btn-sm ms-2" style="height:38px;">✕</button>
            </div>

            <div class="table-responsive">
                <table id='dataTable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Panel ID</th>
                            <th>OTP</th>
                            <th>Generation Time</th>
                            <th>Expiry Time</th>
                        </tr>
                    </thead>
                    <tbody id="offlineOtpTableBody">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
             <div id="pagination" class="mt-3"></div>
        </div>
    </div>
</div>



<?php include('./footer.php'); ?>

<script>
const BASE_API_URL = "<?php echo BASE_API_URL; ?>";

document.addEventListener("DOMContentLoaded", function() {
    fetchData();
});


let currentPage = 1;
const limit = 25;

function fetchData(page = 1) {

    const apiUrl = BASE_API_URL + "/offlineotp.php";
    // const apiUrl = "http://localhost/frutopia/api/offlineotp.php";
    const container = document.getElementById("offlineOtpTableBody");

    const formData = new FormData();
    formData.append("get_offline_otp", "1");
    formData.append("page", page);
    formData.append("limit", limit);

    fetch(apiUrl, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.data && data.data.length > 0) {
            let tableRows = "";
            let srno = 1 + (page - 1) * limit;
            data.data.forEach(row => {
                tableRows += `
                    <tr>
                        <td>${srno++}</td>
                        <td>${row.name || ''}</td>
                        <td>${row.panel_id || ''}</td>
                        <td>${row.decrypted_otp || ''}</td>
                        <td>${row.decrypted_generation_time || ''}</td>
                        <td>${row.decrypted_expiration_time || ''}</td>
                    </tr>
                `;
            });
            container.innerHTML = tableRows;

            // ✅ Pagination Buttons
            renderPagination(data.pagination.total_pages, page);
        } else {
            container.innerHTML = "<tr><td colspan='6' class='text-center text-danger'>No data found.</td></tr>";
        }
    })
    .catch(error => {
        console.error("Error fetching API:", error);
        container.innerHTML = "<tr><td colspan='6' class='text-center text-danger'>Failed to load data.</td></tr>";
    });
}

function renderPagination(totalPages, currentPage) {
    const paginationContainer = document.getElementById("pagination");
    let paginationHTML = `<ul class="pagination justify-content-center">`;

    // Previous Button
    paginationHTML += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="fetchData(${currentPage - 1})">Previous</a>
        </li>
    `;

    // Current Page
    paginationHTML += `
        <li class="page-item active">
            <a class="page-link" href="javascript:void(0)">${currentPage}</a>
        </li>
    `;

    // Next Page
    if (currentPage < totalPages) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="fetchData(${currentPage + 1})">${currentPage + 1}</a>
            </li>
        `;
    }

    // Next Button
    paginationHTML += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="fetchData(${currentPage + 1})">Next</a>
        </li>
    `;

    paginationHTML += `</ul>`;
    paginationContainer.innerHTML = paginationHTML;
}


document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#offlineOtpTableBody tr');

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (rowText.indexOf(searchTerm) > -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Clear button functionality
document.getElementById('clearSearchBtn').addEventListener('click', function() {
    const searchInput = document.getElementById('searchInput');
    searchInput.value = '';
    searchInput.dispatchEvent(new Event('input')); // Trigger search to reset table rows
});



</script>