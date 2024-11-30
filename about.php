<style>
    .about-section {
        margin-bottom: 50px;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .about-section h1 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #333;
    }
    .about-row {
        margin-bottom: 40px;
    }
    .about-row img {
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    .about-content {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .about-content h2 {
        font-size: 2rem;
        margin-top: 20px;
        color: #555;
    }
    .about-content p {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #666;
    }
    .about-row:nth-of-type(even) {
        flex-direction: row-reverse;
    }
    .about-row .col-md-6 {
        padding: 10px;
    }
    @media (min-width: 768px) {
        .about-row {
            display: flex;
            align-items: center;
        }
        .about-row img {
            max-width: 100%;
            height: auto;
        }
    }
</style>

<div class="container">
    <div class="about-section">
        <h1>About Us</h1>

        <div class="about-row">

                <p>Welcome to MAY Jewellry, where we offer exquisite and unique jewelry pieces. With years of experience in the industry, we are committed to providing our customers with high-quality products and excellent service.</p>

        </div>

        <div class="about-row">
            <div class="col-md-6 about-content">
                <h2>Who We Are</h2>
                <p>MAY Jewellry is a company specializing in fine jewelry, offering products made from gold, silver, and precious stones. We take pride in designing and crafting jewelry pieces that reflect the elegance and personality of each customer.</p>
            </div>
            <div class="col-md-6">
                <img src="style/template/img/slider2.jpg" alt="Special Jewelry" class="about-image">
            </div>
        </div>

        <div class="about-row"><div class="col-md-6 about-content">
                <h2>Our Vision and Mission</h2>
                <p>We aspire to be the top choice for jewelry enthusiasts, not only for the quality of our products but also for our dedicated service. Our mission is to provide customers with a wonderful shopping experience and the most special jewelry pieces.</p>
                <h2>Contact Us</h2>
                <p>If you have any questions or would like more information, please do not hesitate to contact us via email or phone. We are always here to assist you!</p>
            </div>
            <div class="col-md-6">
                <img src="style/template/img/slider-3.png" alt="Customer Service" class="about-image">
            </div>
            
        </div>
    </div>
</div>
