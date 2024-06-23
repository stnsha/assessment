<?php include 'inc/header.php' ?>
<div class="container mx-8 my-4">
    <h3 class="text-center mb-4">Master List</h3>
    <div class="d-flex justify-content-end mb-3">
        <div class="p-2">
            <a type="button" class="btn btn-primary" href="form.php">Add new customer</a>
        </div>
        <div class="p-2">
            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
        </div>
        <div class="p-2">
            <select class="form-select" id="brandFilter">
                <option value="">All Brands</option>
            </select>
        </div>
    </div>
    <div class="table-responsive mt-3">
        <table class="table table-striped" id="masterList">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Model Name</th>
                    <th>Brand Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mt-3" id="pagination">
        </ul>
    </nav>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        let currentPage = 1;

        loadBrands();

        loadModels(currentPage);

        $('#searchInput, #brandFilter').on('input change', function() {
            let searchText = $('#searchInput').val().toLowerCase();
            let brandFilter = $('#brandFilter').val();
            filterModels(searchText, brandFilter);
        });

        $('#masterList').on('click', '.delete-btn', function(e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to delete this item?')) {
                return;
            }

            var $deleteBtn = $(this);

            var id = $deleteBtn.data('id');
            $.ajax({
                type: 'GET',
                url: 'queries.php',
                data: {
                    id: id,
                    action: 'delete_user'
                },
                success: function(response) {
                    if (response) {
                        $deleteBtn.closest('tr').remove();
                        alert('Item deleted successfully!');
                    } else {
                        alert('Failed to delete item. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error delete data:', error);
                    console.log(xhr.responseText);
                }
            });
        });
    });

    function loadBrands() {
        $.ajax({
            url: 'queries.php',
            type: 'GET',
            dataType: 'json',
            data: {
                action: 'get_all_brands'
            },
            success: function(response) {
                if (response && response.brands) {
                    let options = '<option value="">All Brands</option>';
                    response.brands.forEach(function(brand) {
                        options += '<option value="' + brand.id + '">' + brand.name + '</option>';
                    });
                    $('#brandFilter').html(options);
                } else {
                    console.error('Failed to load brands');
                }
            },
            error: function(error) {
                console.error('Error fetching brands:', error);
            }
        });
    }

    function loadModels(page) {
        $.ajax({
            url: 'queries.php',
            type: 'GET',
            dataType: 'json',
            data: {
                page: page
            },
            success: function(response) {
                if (response && response.models) {
                    displayModels(response.models, page);
                    renderPagination(response.totalPages, page);
                    currentPage = page;
                } else {
                    console.error('Invalid response format from server');
                }
            },
            error: function(error) {
                console.error('Error fetching models:', error);
            }
        });
    }

    function displayModels(models, currentPage) {
        let table = '';
        models.forEach((model, index) => {
            let rowNum = (index + 1) + (currentPage - 1) * 10;
            table += '<tr>' +
                '<td>' + rowNum + '</td>' +
                '<td>' + model.customer_name + '</td>' +
                '<td>' + model.customer_email + '</td>' +
                '<td>' + model.customer_age + '</td>' +
                '<td>' + model.model_name + '</td>' +
                '<td>' + model.brand_name + '</td>' +
                '<td>' +
                '<a type="button" class="btn btn-info text-white me-2" href="form.php?id=' + model.id + '">Edit</a>' +
                '<button type="button" class="btn btn-danger delete-btn" data-id=' + model.id + '>Delete</button>'
            '</td>' +
            '</tr>';
        });
        $('#masterList tbody').html(table);
    }

    function filterModels(searchText, brandFilter) {
        $.ajax({
            url: 'queries.php',
            type: 'GET',
            dataType: 'json',
            data: {
                page: 1,
                search: searchText,
                brandFilter: brandFilter,
            },
            success: function(response) {
                if (response && response.models) {
                    displayModels(response.models, 1);
                    renderPagination(response.totalPages, 1);
                    currentPage = 1;
                } else {
                    console.error('Invalid response format from server');
                }
            },
            error: function(error) {
                console.error('Error fetching models:', error);
            }
        });
    }

    function renderPagination(totalPages, currentPage) {
        let pagination = '';
        for (let i = 1; i <= totalPages; i++) {
            pagination += '<li class="page-item ' + (i === currentPage ? 'active' : '') + '"><a class="page-link" href="#" onclick="loadModels(' + i + ')">' + i + '</a></li>';
        }
        $('#pagination').html(pagination);
    }
</script>
<?php include 'inc/footer.php' ?>