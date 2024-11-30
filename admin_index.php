<head>
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
</head>
<div class="container">
    <h1 class="my-4">Welcome to the Admin Dashboard</h1>
    <p>Your admin dashboard provides the essential tools to manage the system effectively and easily. Below are the main sections of the admin dashboard:</p>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text">Manage user information, including adding, modifying, and deleting accounts. You can set up access permissions for each user and monitor their activities.</p>
                    <a href="?page=user" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Management</h5>
                    <p class="card-text">Manage product information, including adding new products, editing existing product details, and deleting unnecessary products. Easily create and maintain your product list.</p>
                    <a href="?page=item" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Management</h5>
                    <p class="card-text">Track and manage customer orders. You can update order statuses, view order details, and handle order-related requests.</p>
                    <a href="?page=order" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Category Management</h5>
                    <p class="card-text">Manage product categories. You can add, edit, and delete categories to organize your products effectively.</p>
                    <a href="?page=category" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Metal Management</h5>
                    <p class="card-text">Manage information about the types of metals used in products. You can add, edit, and delete metal information.</p>
                    <a href="?page=metal" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gemstone Management</h5>
                    <p class="card-text">Manage information about the types of gemstones used in products. You can add, edit, and delete gemstone information.</p>
                    <a href="?page=stone" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gold Price Updates</h5>
                    <p class="card-text">Update and manage the current gold price. Ensure that the gold price is updated regularly to reflect the market conditions.</p>
                    <a href="?page=updatemetalprice" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Custom Orders Management</h5>
                    <p class="card-text">Manage custom orders from customers. Track and handle special order requests according to customer requirements.</p>
                    <a href="?page=personal_order" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
</div>