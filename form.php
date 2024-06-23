<?php
include 'inc/header.php';
$isEditMode = isset($_GET['id']);
$userId = $isEditMode ? $_GET['id'] : null;
$submitName = $isEditMode ? 'Update' : 'Create';
?>
<div class="d-flex justify-content-center row m-4">
    <h3 class="text-center my-2">Create New Customer</h3>
    <form class="row g-3" id="createUserForm">
        <div class="col-md-12">
            <div class="alert alert-success" role="alert" id="success" style="display:none;"></div>
            <div class="alert alert-danger" role="alert" id="fail" style="display:none;"></div>
        </div>

        <div class="col-md-12">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name">
            <input type="hidden" id="userId" name="userId" value="<?php echo $userId; ?>">
            <div class="invalid-feedback">Please enter a name.</div>
        </div>
        <div class="col-md-6">
            <label for="phoneNumber" class="form-label">Phone Numbers</label>
            <div id="phoneNumbersContainer">
                <div class="input-group mb-2">
                    <input type="text" class="form-control phoneNumberInput" id="phoneNumber" name="phoneNumber[]">
                    <button type="button" id="addPhoneNumber" class="btn btn-outline-primary">Add Phone Number</button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
            <div class="invalid-feedback" id="emailError"></div>
        </div>
        <div class="col-md-6">
            <label for="icNumber" class="form-label">IC Number</label>
            <input type="text" class="form-control" id="icNumber" name="icNumber" placeholder="560101-01-0101">
            <div class="invalid-feedback" id="icNumberError">Please enter an IC number.</div>
        </div>
        <div class="col-md-6">
            <label for="age" class="form-label">Age</label>
            <input type="text" class="form-control" id="age" name="age" readonly>
            <div class="invalid-feedback">Please calculate age.</div>
        </div>
        <div class="col-md-6">
            <label for="phoneBrand" class="form-label">Phone Brand</label>
            <select id="phoneBrand" class="form-select" name="phoneBrand">
                <option value="">Choose...</option>
            </select>
            <div class="invalid-feedback">Please select a phone brand.</div>
        </div>
        <div class="col-md-6">
            <label for="phoneModel" class="form-label">Phone Model</label>
            <select id="phoneModel" class="form-select" name="phoneModel">
                <option value="">Choose...</option>
            </select>
            <div class="invalid-feedback">Please select a phone model.</div>
        </div>
        <div class="col-12">
            <a type="button" href="index.php" class="btn btn-secondary">Back</a>
            <button type="submit" name="submit" class="btn btn-primary"><?php echo $submitName ?> user</button>
        </div>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        loadBrands();

        var isEditMode = $('#userId').val() !== '';

        if (isEditMode) {
            var userId = $('#userId').val();
            loadUserData(userId);
        }

        $('#phoneBrand').on('change', function() {
            var brandId = $(this).val();
            if (brandId) {
                fetchModels(brandId);
            } else {
                resetModelSelect();
            }
        });

        function fetchModels(brandId, modelId) {
            $.ajax({
                url: 'queries.php',
                method: 'GET',
                data: {
                    action: 'get_models_by_brand',
                    id: brandId
                },
                dataType: 'json',
                success: function(response) {
                    var phoneModelSelect = $('#phoneModel');
                    phoneModelSelect.empty();
                    phoneModelSelect.append($('<option>', {
                        value: '',
                        text: 'Choose...'
                    }));
                    $.each(response, function(index, model) {
                        var option = $('<option>', {
                            value: model.id,
                            text: model.name
                        });

                        if (modelId && model.id == modelId) {
                            option.prop('selected', true);
                        }

                        phoneModelSelect.append(option);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching models:', error);
                    console.log(xhr.responseText);
                }
            });
        }


        function loadUserData(userId) {
            $.ajax({
                url: 'queries.php',
                method: 'GET',
                data: {
                    action: 'get_user',
                    id: userId
                },
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        $('#name').val(response.name);
                        $('#email').val(response.email);
                        $('#icNumber').val(response.ic_no);
                        $('#age').val(response.age);
                        $('#phoneBrand').val(response.brand_id);
                        fetchModels(response.brand_id, response.model_id);
                        $('#phoneModel').val(response.model_id);
                        response.phone_numbers.forEach(function(number, index) {
                            if (index === 0) {
                                $('#phoneNumber').val(number);
                            } else {
                                addPhoneNumberInput(number);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function addPhoneNumberInput(number = '') {
            var phoneNumberInput =
                '<div class="input-group mb-2">' +
                '<input type="text" class="form-control phoneNumberInput" value="' + number + '" name="phoneNumber[]">' +
                '<button type="button" class="btn btn-outline-danger btn-remove">Remove</button>' +
                '</div>';
            $('#phoneNumbersContainer').append(phoneNumberInput);
        }

        $('#icNumber').on('input', function() {
            var icNumber = $(this).val();
            var formattedICNumber = formatICNumber(icNumber);
            $(this).val(formattedICNumber);

            var firstTwoDigits = icNumber.substring(0, 2);
            calculateAge(firstTwoDigits);
        });

        $('#addPhoneNumber').click(function() {
            addPhoneNumberInput();
        });

        $('#phoneNumbersContainer').on('click', '.btn-remove', function() {
            if ($(this).closest('.input-group').index() > 0) {
                $(this).closest('.input-group').remove();
            }
        });

        $('#createUserForm').submit(function(e) {
            e.preventDefault();

            if (validateForm()) {
                checkEmailUnique();
            }
        });

        function validateForm() {
            var isValid = true;

            // Validate name
            if ($('#name').val().trim() === '') {
                $('#name').addClass('is-invalid');
                isValid = false;
            } else {
                $('#name').removeClass('is-invalid');
            }

            // Validate phone numbers (at least one should be filled)
            var phoneNumbersValid = false;
            $('.phoneNumberInput').each(function() {
                if ($(this).val().trim() !== '') {
                    phoneNumbersValid = true;
                }
            });
            if (!phoneNumbersValid) {
                $('.phoneNumberInput').addClass('is-invalid');
                isValid = false;
            } else {
                $('.phoneNumberInput').removeClass('is-invalid');
            }

            // Validate email
            if ($('#email').val().trim() === '') {
                $('#email').addClass('is-invalid');
                $('#emailError').text('Please enter an email.');
                isValid = false;
            } else {
                $('#email').removeClass('is-invalid');
                $('#emailError').text('');
            }

            // Validate IC number
            var icNumber = $('#icNumber').val().trim();
            if (icNumber === '' || icNumber.length > 14) {
                $('#icNumber').addClass('is-invalid');
                $('#icNumberError').text('Please enter a valid IC number.');
                isValid = false;
            } else {
                $('#icNumber').removeClass('is-invalid');
                $('#icNumberError').text('');
            }

            // Validate age (should be calculated)
            if ($('#age').val().trim() === '') {
                $('#age').addClass('is-invalid');
                isValid = false;
            } else {
                $('#age').removeClass('is-invalid');
            }

            // Validate phone brand
            if ($('#phoneBrand').val() === '') {
                $('#phoneBrand').addClass('is-invalid');
                isValid = false;
            } else {
                $('#phoneBrand').removeClass('is-invalid');
            }

            // Validate phone model
            if ($('#phoneModel').val() === '') {
                $('#phoneModel').addClass('is-invalid');
                isValid = false;
            } else {
                $('#phoneModel').removeClass('is-invalid');
            }

            return isValid;
        }

        function checkICUnique() {
            var icNumber = $('#icNumber').val().trim();
            var userId = $('#userId').val();

            $.ajax({
                url: 'queries.php',
                method: 'GET',
                data: {
                    action: 'check_ic_unique',
                    icNumber: icNumber,
                    userId: userId
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    if (response === true) {
                        $('#icNumber').addClass('is-invalid');
                        $('#icNumberError').text('This IC number already exists.');
                    } else {
                        $('#icNumber').removeClass('is-invalid');
                        $('#icNumberError').text('');
                        submitForm();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function checkEmailUnique(isNewUser) {
            var email = $('#email').val().trim();
            var userId = $('#userId').val();

            $.ajax({
                url: 'queries.php',
                method: 'GET',
                data: {
                    action: 'check_email_unique',
                    email: email,
                    userId: userId
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    if (response === true) {
                        $('#email').addClass('is-invalid');
                        $('#emailError').text('This email is already taken.');
                    } else {
                        $('#email').removeClass('is-invalid');
                        $('#emailError').text('');
                        checkICUnique();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function submitForm() {
            let phoneNumbers = [];
            $('.phoneNumberInput').each(function() {
                phoneNumbers.push($(this).val());
            });

            const data = {
                userId: sanitizeInput($('#userId').val()),
                name: sanitizeInput($('#name').val()),
                phoneNumber: phoneNumbers.map(phone => sanitizeInput(phone)),
                email: sanitizeInput($('#email').val()),
                icNumber: sanitizeInput($('#icNumber').val()),
                age: sanitizeInput($('#age').val()),
                phoneBrand: sanitizeInput($('#phoneBrand').val()),
                phoneModel: sanitizeInput($('#phoneModel').val()),
            };

            $.ajax({
                type: 'POST',
                url: 'queries.php',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(data) {
                    if (isEditMode === true) {
                        var message = 'User updated successfully. <a href="index.php">Back to home</a>';
                        $('#createUserForm')[0].reset();
                        loadUserData(userId);
                    } else {
                        var message = 'User created successfully. <a href="index.php">Back to home</a>';
                        $('#createUserForm')[0].reset();
                    }

                    document.getElementById('success').innerHTML = message;
                    document.getElementById('success').style.display = 'block';

                },
                error: function(xhr, status, error) {
                    var message = 'User cannot be' + (isEditMode ? 'updated.' : 'created.');
                    document.getElementById('success').innerHTML = message;
                    document.getElementById('success').style.display = 'none';
                    document.getElementById('fail').style.display = 'block';
                    console.error('Error:', error);
                }
            });
        }

        function sanitizeInput(input) {
            return $('<div>').text(input).html();
        }

    });

    function resetModelSelect() {
        $('#phoneModel').empty().append($('<option>', {
            value: '',
            text: 'Choose...'
        }));
    }

    function addPhoneNumberInput() {
        var phoneNumberInput =
            '<div class="input-group mb-2">' +
            '<input type="text" class="form-control phoneNumberInput" placeholder="Enter phone number" name="phoneNumber[]">' +
            '<button type="button" class="btn btn-outline-danger btn-remove">Remove</button>' +
            '</div>';
        $('#phoneNumbersContainer').append(phoneNumberInput);
    }

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
                    $('#phoneBrand').html(options);
                } else {
                    console.error('Failed to load brands');
                }
            },
            error: function(error) {
                console.error('Error fetching brands:', error);
            }
        });
    }

    function formatICNumber(icNumber) {
        icNumber = icNumber.replace(/-/g, '');

        if (icNumber.length > 6) {
            icNumber = icNumber.slice(0, 6) + '-' + icNumber.slice(6);
        }

        if (icNumber.length > 8) {
            icNumber = icNumber.slice(0, 9) + '-' + icNumber.slice(9);
        }

        return icNumber;
    }

    function calculateAge(firstTwoDigits) {
        var yearDigits = parseInt(firstTwoDigits);

        var baseYear;
        if (yearDigits >= 0 && yearDigits <= 20) {
            baseYear = 2000 + yearDigits;
        } else {
            baseYear = 1900 + yearDigits;
        }

        var currentYear = new Date().getFullYear();

        var age = currentYear - baseYear;
        $('#age').val(age);
    }
</script>
<?php include 'inc/footer.php' ?>