<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
.blog {
    max-width: 1200px;
    margin: 0 auto;
}

.blog h1 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 2.5em;
    color: #2c3e50; /* Darker color for a luxurious feel */
}

.blog article {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #bdc3c7; /* Lighter border color */
    border-radius: 8px;
    background-color: #ecf0f1; /* Light gray background */
}

.blog img {
    width: 100%; /* Make the image fill the container width */
    height: 300px; /* Fixed height for all images */
    object-fit: cover; /* Ensures the image covers the container without distortion */
    border-radius: 8px;
    margin-bottom: 15px; /* Space between image and content on small screens */
}

.blog .content {
    flex: 1;
}

.blog h2 {
    margin-bottom: 15px;
    color: #1a5466; /* Luxurious gold color */
}

.blog p {
    margin-bottom: 20px;
    color: #34495e; /* Darker text color for better readability */
}

.read-more {
    color: #325675; /* Luxurious gold color */
    text-decoration: none;
    font-weight: bold;
}

.read-more:hover {
    text-decoration: underline;
    color: #497ba6;
}

@media (max-width: 768px) {
    .blog {
        padding: 10px;
    }
    
    .blog article {
        flex-direction: column;
        align-items: center;
    }
    
    .blog img {
        width: 100%; /* Ensures images fit within their container */
        height: auto; /* Allows height to adjust automatically */
        max-height: 300px; /* Optional: restricts max height on smaller screens */
        margin-right: 0;
    }
}
</style>
<div class="container">
    <section class="blog">
        <h1>MAY Jewellry Blog</h1>
        <article>
            <img src="style/template/img/summer.png" alt="Summer Sale">
            <div class="content">
                <h2>Summer Sale - 10% Off</h2>
                <p>Enjoy our exclusive Summer Sale with 10% off on selected jewelry pieces! It's the perfect opportunity to add a touch of elegance to your collection. Hurry, this offer is valid for a limited time only. Explore our summer collection and take advantage of this special discount before it ends!</p>
                <a href="?page=blog_sale" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </article>
        <article>
            <img src="style/template/img/slider-3.png" alt="Featured Jewelry Piece">
            <div class="content">
                <h2>Discover Our Latest Collection</h2>
                <p>Explore the newest arrivals at MAY Jewellry. From timeless classics to modern designs, our latest collection offers something for every taste. Discover the exquisite craftsmanship and unique styles that set our pieces apart.</p>
                <a href="?page=blog1" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </article>
        <article>
            <img src="style/template/img/trending.jpg" alt="Jewelry Trends">
            <div class="content">
                <h2>Trends in Jewelry for 2024</h2>
                <p>Stay ahead of the trends with our guide to the latest jewelry trends for 2024. From bold statements to delicate details, find out what's in vogue and how you can incorporate these trends into your collection.</p>
                <a href="?page=blog2" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </article>
    </section>
</div>
