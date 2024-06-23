<?php
include 'inc/config.php';

function getMasterList($conn, $page = 1, $perPage = 10, $search = '', $brandFilter = '')
{

    $isTableExist = checkUserTableExist($conn);
    if (!$isTableExist) {
        createUserTable($conn);
        createUserPhoneNumbersTable($conn);
        createUserBrandTable($conn);
    }
    $offset = ($page - 1) * $perPage;

    $sql = "SELECT ub.id as id, u.name as customer_name, u.email as customer_email, u.age as customer_age, b.name as brand_name, m.name as model_name
            FROM user_brands ub 
            INNER JOIN users u ON ub.user_id = u.id
            INNER JOIN brand b ON ub.brand_id = b.id
            INNER JOIN model m ON ub.model_id = m.id
            WHERE 1=1";

    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $sql .= " AND (m.name LIKE '%$search%' OR b.name LIKE '%$search%' OR u.name LIKE '%$search%' OR u.age LIKE '%$search%' OR u.email LIKE '%$search%')";
    }

    if (!empty($brandFilter)) {
        $brandFilter = $conn->real_escape_string($brandFilter);
        $sql .= " AND b.id = '$brandFilter'";
    }

    $sql .= " LIMIT $perPage OFFSET $offset";

    $result = $conn->query($sql);

    $models = array();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $models[] = $row;
        }
    }

    $totalModelsQuery = "SELECT COUNT(*) as total FROM user_brands ub
                         INNER JOIN users u ON ub.user_id = u.id
                         INNER JOIN brand b ON ub.brand_id = b.id
                         INNER JOIN model m ON ub.model_id = m.id
                         WHERE 1=1";

    if (!empty($search)) {
        $totalModelsQuery .= " AND (m.name LIKE '%$search%' OR b.name LIKE '%$search%' OR u.name LIKE '%$search%' OR u.age LIKE '%$search%' OR u.email LIKE '%$search%')";
    }

    if (!empty($brandFilter)) {
        $totalModelsQuery .= " AND b.id = '$brandFilter'";
    }

    $totalModelsResult = $conn->query($totalModelsQuery);
    $totalModels = $totalModelsResult->fetch_assoc();
    $totalPages = ceil($totalModels['total'] / $perPage);

    return array(
        'brandFilter' => $brandFilter,
        'models' => $models,
        'totalPages' => $totalPages
    );
}



function getAllBrands($conn)
{
    $sql = "SELECT id, name FROM brand";
    $result = $conn->query($sql);

    $brands = array();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $brands[] = $row;
        }
    }

    return $brands;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'get_all_brands') {
    $brands = getAllBrands($conn);
    echo json_encode(array('brands' => $brands));
    exit;
}

function getModelsByBrandId($conn, $id)
{
    $sql = "SELECT id, name FROM model WHERE brand_id = " . $id;

    $result = $conn->query($sql);

    $modelsByBrand = array();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $modelsByBrand[] = $row;
        }
    }

    return $modelsByBrand;
}

if ($action === 'get_models_by_brand') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = getModelsByBrandId($conn, $id);
    } else {
        $response = array('error' => 'Brand ID is missing');
    }

    echo json_encode($response);
    exit;
}

function checkUserTableExist($conn)
{
    $sql = "SHOW TABLES FROM assessment LIKE 'users'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function createUserTable($conn)
{
    $sql = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    ic_no VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function createUserPhoneNumbersTable($conn)
{
    $sql = "CREATE TABLE user_phones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone_no VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id)
    )";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function createUserBrandTable($conn)
{
    $sql = "CREATE TABLE user_brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    brand_id INT NOT NULL,
    model_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id)
    )";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function checkEmailExists($conn, $email, $userId = null)
{
    $sql = "SELECT ub.id, u.email FROM users u INNER JOIN user_brands ub ON u.id = ub.user_id WHERE u.email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row_id = $row['id'];
        if ($userId == $row_id) {
            return false;
        } else {
            return true;
        }
    } else {
        return $result;
    }
}


if ($action === 'check_email_unique') {
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
        isset($_GET['userId']) ? $userId = $_GET['userId'] : $userId = '';
        $response = checkEmailExists($conn, $email, $userId);
    } else {
        $response = array('error' => 'Email is missing');
    }

    echo json_encode($response);
    exit;
}

function checkICUnique($conn, $icNumber, $userId = null)
{
    $sql = "SELECT ub.id, u.ic_no FROM users u INNER JOIN user_brands ub ON u.id = ub.user_id WHERE u.ic_no = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $icNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row_id = $row['id'];
        if ($userId == $row_id) {
            return false;
        } else {
            return true;
        }
    } else {
        return $result;
    }
}

if ($action === 'check_ic_unique') {
    if (isset($_GET['icNumber'])) {
        $icNumber = $_GET['icNumber'];
        isset($_GET['userId']) ? $userId = $_GET['userId'] : $userId = '';
        $response = checkICUnique($conn, $icNumber, $userId);
    } else {
        $response = array('error' => 'icNumber is missing');
    }

    echo json_encode($response);
    exit;
}
function insertUserData($conn, $data)
{
    $name = $data['name'];
    $email = $data['email'];
    $icNumber = $data['icNumber'];
    $age = $data['age'];
    $phoneNumbers = $data['phoneNumber'];
    $phoneBrand = $data['phoneBrand'];
    $phoneModel = $data['phoneModel'];

    $insertUserSql = "INSERT INTO users (name, email, ic_no, age) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertUserSql);
    $stmt->bind_param("sssi", $name, $email, $icNumber, $age);
    $stmt->execute();
    $userId = $stmt->insert_id;

    if ($userId) {
        $insertPhoneSql = "INSERT INTO user_phones (user_id, phone_no) VALUES (?, ?)";
        $stmtPhone = $conn->prepare($insertPhoneSql);
        foreach ($phoneNumbers as $phoneNumber) {
            $stmtPhone->bind_param("is", $userId, $phoneNumber);
            $stmtPhone->execute();
        }

        $insertBrandSql = "INSERT INTO user_brands (user_id, brand_id, model_id) VALUES (?, ?, ?)";
        $stmtBrand = $conn->prepare($insertBrandSql);
        $stmtBrand->bind_param("iii", $userId, $phoneBrand, $phoneModel);
        $stmtBrand->execute();

        if ($stmtBrand->affected_rows > 0) {
            $response = "User data inserted successfully";
        } else {
            $response = "Error inserting user data: " . $conn->error;
        }
    } else {
        $response = "Error inserting user data: " . $stmt->error;
    }

    return $response;
}


function updateUserData($conn, $data)
{
    $userId = $data['userId'];
    $name = $data['name'];
    $email = $data['email'];
    $icNumber = $data['icNumber'];
    $age = $data['age'];
    $phoneNumbers = $data['phoneNumber'];
    $phoneBrand = $data['phoneBrand'];
    $phoneModel = $data['phoneModel'];

    $updateUserSql = "UPDATE users SET name = ?, email = ?, ic_no = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($updateUserSql);
    $stmt->bind_param("sssii", $name, $email, $icNumber, $age, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0 || $stmt->errno === 0) {
        $deletePhoneSql = "DELETE FROM user_phones WHERE user_id = ?";
        $stmtDeletePhone = $conn->prepare($deletePhoneSql);
        $stmtDeletePhone->bind_param("i", $userId);
        $stmtDeletePhone->execute();

        $insertPhoneSql = "INSERT INTO user_phones (user_id, phone_no) VALUES (?, ?)";
        $stmtPhone = $conn->prepare($insertPhoneSql);
        foreach ($phoneNumbers as $phoneNumber) {
            $stmtPhone->bind_param("is", $userId, $phoneNumber);
            $stmtPhone->execute();
        }

        $updateBrandSql = "UPDATE user_brands SET brand_id = ?, model_id = ? WHERE user_id = ?";
        $stmtBrand = $conn->prepare($updateBrandSql);
        $stmtBrand->bind_param("iii", $phoneBrand, $phoneModel, $userId);
        $stmtBrand->execute();

        if ($stmtBrand->affected_rows > 0 || $stmtBrand->errno === 0) {
            $response = "User data updated successfully";
        } else {
            $response = "Error updating user data: " . $stmtBrand->error;
        }
    } else {
        $response = "Error updating user data: " . $stmt->error;
    }

    return $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data === null) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid JSON data'));
        exit;
    }

    if (isset($data['userId']) && !empty($data['userId'])) {
        $response = updateUserData($conn, $data);
    } else {
        $response = insertUserData($conn, $data);
    }

    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($_GET['action']) && $_GET['action'] == 'get_user') {
        $userId = $_GET['id'];
        $query = "SELECT ub.id, u.name, u.email, u.ic_no, u.age, ub.brand_id, ub.model_id, 
                         GROUP_CONCAT(up.phone_no) AS phone_numbers
                  FROM users u
                  LEFT JOIN user_brands ub ON u.id = ub.user_id
                  LEFT JOIN user_phones up ON u.id = up.user_id
                  WHERE ub.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = $result->fetch_assoc();
        if (!empty($response['phone_numbers'])) {
            $response['phone_numbers'] = explode(',', $response['phone_numbers']);
        } else {
            $response['phone_numbers'] = [];
        }

        echo json_encode($response);
        exit;
    }
}

function deleteUserBrand($conn, $id)
{
    $userId = intval($id);
    $sqlDeleteUsers = "DELETE FROM user_brands WHERE id = ?";
    $stmtUsers = $conn->prepare($sqlDeleteUsers);
    $stmtUsers->bind_param("i", $userId);
    $stmtUsers->execute();
    $affectedUsers = $stmtUsers->affected_rows;
    $stmtUsers->close();

    if ($affectedUsers > 0) {
        return true;
    } else {
        return false;
    }
}

if ($action === 'delete_user') {
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if (!empty($id)) {
        $response = deleteUserBrand($conn, $id);
    } else {
        $response = false;
    }
    echo json_encode($response);
    exit;
}

function getTotalUsersByBrand($conn)
{
    $sql = "SELECT COUNT(ub.id) AS total_users, b.name AS brand_name 
            FROM user_brands ub
            INNER JOIN brand b ON b.id = ub.brand_id 
            GROUP BY ub.brand_id";

    $result = $conn->query($sql);

    $data = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'get_total_users_by_brand') {
        $data = getTotalUsersByBrand($conn);
        echo json_encode($data);
        exit;
    }
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 10;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$brandFilter = isset($_GET['brandFilter']) ? $_GET['brandFilter'] : '';

$response = getMasterList($conn, $page, $perPage, $search, $brandFilter);

header('Content-Type: application/json');
echo json_encode($response);
