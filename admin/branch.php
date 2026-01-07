<?php include('./header.php'); 
$_user_id = $_SESSION['user_id'];
?>

<div class="container">
    <h4>Branch Details</h4>
    <button type="button" class="btn btn-primary btn-sm mb-3" id="branch_add_btn" data-bs-toggle="modal"
        data-bs-target="#exampleModal">
        Add
    </button>
    <!-- Placeholder for table -->
    <div class="card">
        <div id="panel" class="card-body">
            <div class="table-responsive">
                <table id='dataTable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="branchTableBody">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Branch Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="from-group mb-3">
                    <span class="form-label fw-bold">Branch Name</span>
                    <input type="text" class="form-control mb-3" placeholder="Enter Branch Name" id="branch_name"
                        required />
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal"
                    style="background: red;">Close</button>
                <button type="button" class="btn btn-warning" id="save_branch_btn">Save</button>
                <button type="button" class="btn btn-primary" id="update_branch_btn">Update</button>
            </div>
        </div>
    </div>
</div>

<?php include('./footer.php'); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetchData();
});
// Make sure you select the modal element first
const modalElement = document.getElementById('exampleModal');
const modal = new bootstrap.Modal(modalElement); // initialize bootstrap modal


const saveBranchBtn = document.getElementById("save_branch_btn");
const branch_add_btn = document.getElementById("branch_add_btn");

branch_add_btn.addEventListener("click", function() {
    // Reset form fields
    document.getElementById("branch_name").value = "";

    // Show save button and hide update button
    saveBranchBtn.style.display = "inline-block";
    updateBranchBtn.style.display = "none";
});

const updateBranchBtn = document.getElementById("update_branch_btn");
updateBranchBtn.style.display = "none";

saveBranchBtn.addEventListener("click", function() {
    var branch_name = document.getElementById("branch_name").value.trim();

    // Basic validation
    if (!branch_name) {
        alert("Please fill all the fields.");
        return;
    }

     const apiUrl = "https://sarsspl.com/FRUtopia/api/branch.php";
    //const apiUrl = "http://localhost/frutopia/api/branch.php";


    // ✅ Create FormData object
    const formData = new FormData();
    formData.append("branch_name", branch_name);
    formData.append("save_branch", "1");

    fetch(apiUrl, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.Code === 200) {
                // alert(data.msg);
                document.getElementById("branch_name").value = "";

                fetchData();
                modal.hide();
            } else {
                alert(data.msg || "Failed to save");
            }
        })
        .catch(error => {
            console.error("Error fetching API:", error);
            alert("Something went wrong!");
        });
});


function fetchData() {
    const apiUrl = "https://sarsspl.com/FRUtopia/api/branch.php";
    // const apiUrl = "http://localhost/frutopia/api/branch.php";


    const container = document.getElementById("branchTableBody");

    // ✅ Create FormData object
    const formData = new FormData();
    formData.append("get_all_branch", "1");

    fetch(apiUrl, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {

            if (data && data.data && data.data.length > 0) {
                let tableRows = "";
                let srno = 1;
                data.data.forEach(row => {
                    tableRows += `
                        <tr>
                            <td>${srno++}</td>
                            <td>${row.branch_name || ''}</td>
                            <td><button class="btn btn-success btn-sm" onClick="editData(${row.id});">Edit</button></td>
                        </tr>
                    `;
                });

                container.innerHTML = tableRows;

            } else {
                container.innerHTML =
                    "<tr><td colspan='3' class='text-center text-danger'>No data found or API error.</td></tr>";
            }
        })
        .catch(error => {
            console.error("Error fetching API:", error);
            container.innerHTML =
                "<tr><td colspan='3' class='text-center text-danger'>Failed to load data.</td></tr>";
        });

}

function editData(id) {
    
    const apiUrl = "https://sarsspl.com/FRUtopia/api/branch.php";
    // const apiUrl = "http://localhost/frutopia/api/branch.php";

    const formData = new FormData();
    formData.append("get_all_branch", "1");

    fetch(apiUrl, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.data) {
                const branch = data.data.find(b => b.id == id);
                if (branch) {
                    document.getElementById("branch_name").value = branch.branch_name;

                    // Show update button and hide save button
                    saveBranchBtn.style.display = "none";
                    updateBranchBtn.style.display = "inline-block";

                    // Show the modal
                    modal.show();

                    // Set up the update button click event
                    updateBranchBtn.onclick = function() {
                        updateBranch(id);
                    };
                }
            }
        })
        .catch(error => {
            console.error("Error fetching API:", error);
            alert("Something went wrong!");
        });
}

function updateBranch(id) {
    var branch_name = document.getElementById("branch_name").value.trim();

    // Basic validation
    if (!branch_name) {
        alert("Please fill all the fields.");
        return;
    }

    const apiUrl = "https://sarsspl.com/FRUtopia/api/branch.php";
    // const apiUrl = "http://localhost/frutopia/api/branch.php";

    // ✅ Create FormData object
    const formData = new FormData();
    formData.append("branch_name", branch_name);
    formData.append("id", id);
    formData.append("update_branch", "1");

    fetch(apiUrl, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.Code === 200) {
                // alert(data.msg);
                document.getElementById("branch_name").value = "";

                // Reset buttons
                saveBranchBtn.style.display = "inline-block";
                updateBranchBtn.style.display = "none";

                fetchData();
                modal.hide();
            } else {
                alert(data.msg || "Failed to update");
            }
        })
        .catch(error => {
            console.error("Error fetching API:", error);
            alert("Something went wrong!");
        });
}
</script>