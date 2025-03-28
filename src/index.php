<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <section class="categories">
        <h2>PRODUCTS</h2>
        <div class="category-list">
            <div class="category">
                <img src="../assets/images/egg.png" alt="Eggs">
                <span>Eggs</span>
            </div>
            <div class="category">
                <img src="../assets/images/milk.png" alt="Milk">
                <span>Milk</span>
            </div>
            <div class="category">
                <img src="../assets/images/cake.png" alt="Cake">
                <span>Baked Goods</span>
            </div>
            <div class="category">
                <img src="../assets/images/flowers.png" alt="Flowers">
                <span>Flowers</span>
            </div>
            <div class="category">
                <img src="../assets/images/wine.png" alt="Wine">
                <span>Wine</span>
            </div>
            <div class="category">
                <img src="../assets/images/beer.png" alt="Beer">
                <span>Beer</span>
            </div>
            <div class="category">
                <img src="../assets/images/chicken.png" alt="Chicken">
                <span>Chicken</span>
            </div>
            <div class="category">
                <img src="../assets/images/beef.png" alt="Beef">
                <span>Beef</span>
            </div>
            <div class="category">
                <img src="../assets/images/laundry.png" alt="Laundry">
                <span>Home Goods</span>
            </div>
            <div class="category">
                <img src="../assets/images/tissue.png" alt="Tissue">
                <span>Health</span>
            </div>
            <div class="category">
                <img src="../assets/images/tissue.png" alt="Other">
                <span>Other</span>
            </div>
            <div class="category">
                <img src="../assets/images/tissue.png" alt="Misc.">
                <span>Misc.</span>
            </div>
        </div>
    </section>

 
    <section class="hot-products">
        <h2>HOT PRODUCTS</h2>
        <div class="product-grid">
            <div class="product">
                <img src="../assets/images/spinach.jpg" alt="Baby Spinach">
                <h3>Baby Spinach</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="../assets/images/raspberries.jpg" alt="Raspberries">
                <h3>Raspberries</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="../assets/images/blueberries.jpg" alt="Blueberries">
                <h3>Blueberries (FRZ)</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="../assets/images/eggs.jpg" alt="Large Eggs">
                <h3>Large Eggs</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
        </div>
    </section>


    <section class="all-products">
        <h2>ALL PRODUCTS</h2>
        <div class="product-grid">
            <div class="product">
                <img src="../assets/images/spinach.jpg" alt="Baby Spinach">
                <h3>Baby Spinach</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="../assets/images/raspberries.jpg" alt="Raspberries">
                <h3>Raspberries</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="../assets/images/bread.jpg" alt="Bread">
                <h3>Bread</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="../assets/images/eggs.jpg" alt="Large Eggs">
                <h3>Large Eggs</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
        </div>
        <div class="pagination">
            <button>«</button>
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button>»</button>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
<script src="search.js" ></script>
</body>
</html>