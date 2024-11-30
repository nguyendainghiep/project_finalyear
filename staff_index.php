<style>
    /* Container styles */
.container {
    max-width: 1100px;
    margin: auto;
}

/* Card styles */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: linear-gradient(135deg, #f5f5f5, #e8e8e8);
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.card-body {
    padding: 30px;
    text-align: center;
}

/* Title styles */
.card-title {
    font-size: 1.75rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Text styles */
.card-text {
    font-size: 1rem;
    color: #7f8c8d;
    margin-bottom: 25px;
    line-height: 1.6;
}

/* Button styles */
.btn-primary {
    background-color: #2980b9;
    border-color: #2980b9;
    font-size: 1.1rem;
    font-weight: bold;
    color: #fff;
    padding: 10px 20px;
    border-radius: 25px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 8px rgba(41, 128, 185, 0.4);
}

.btn-primary:hover {
    background-color: #1f618d;
    box-shadow: 0 6px 12px rgba(31, 97, 141, 0.5);
}

/* Spacing and layout */
.mb-6 {
    margin-bottom: 40px;
}

.mt-4 {
    margin-top: 24px;
}

.text-center {
    text-align: center;
}

/* Additional styling for a polished look */
h1.text-center {
    font-size: 2.5rem;
    font-weight: bold;
    color: #34495e;
    margin-bottom: 50px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.card-body::before {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background-color: #2980b9;
    margin: 0 auto 20px auto;
    border-radius: 2px;
}

</style>

    <div class="container mt-4">
        <h1 class="text-center">Staff Dashboard</h1>
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-6">
                    <div class="card-body">
                        <h5 class="card-title">Manage Pending Orders</h5>
                        <p class="card-text">View and manage customer orders.</p>
                        <a href="?page=staff_order" class="btn btn-primary">Go to Orders</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-6">
                    <div class="card-body">
                        <h5 class="card-title">Inventory Products</h5>
                        <p class="card-text">Manage and update inventory.</p>
                        <a href="update_products.php" class="btn btn-primary">Go to Products</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-6">
                    <div class="card-body">
                        <h5 class="card-title">Contact With Customer</h5>
                        <p class="card-text">Customer interaction and support</p>
                        <a href="reports.php" class="btn btn-primary">View Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-6">
                    <div class="card-body">
                        <h5 class="card-title">Customer Information</h5>
                        <p class="card-text">View customer information</p>
                        <a href="reports.php" class="btn btn-primary">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
