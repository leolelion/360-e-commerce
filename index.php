<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">
    
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <section class="categories">
        <h2>Categories</h2>
        <div class="category-list">
            <div class="category">
                <img src="images/fruitsVegetables.png" alt="Fruits & Vegetables">
                <span>Produce</span>
            </div>
            <div class="category">
                <img src="images/meats.png" alt="Meat & Seafood">
                <span>Meats</span>
            </div>
            <div class="category">
                <img src="images/dairy.png" alt="Dairy Products">
                <span>Dairy</span>
            </div>
            <div class="category">
                <img src="images/frozenFoods.png" alt="Frozen Foods">
                <span>Frozen Foods</span>
            </div>
            <div class="category">
                <img src="images/pantryFoods.png" alt="Pantry Foods">
                <span>Pantry Foods</span>
            </div>
            <div class="category">
                <img src="images/snacksCandy.png" alt="Snacks & Candy">
                <span>Snacks</span>
            </div>
            <div class="category">
                <img src="images/freshFoods.png" alt="Deli & Fresh Prepared Meals">
                <span>Fresh Foods</span>
            </div>
            <div class="category">
                <img src="images/alcohol.png" alt="Alcohol Beverages">
                <span>Alcohol</span>
            </div>
            <div class="category">
                <img src="images/drinks.png" alt="Beverages">
                <span>Beverages</span>
            </div>
            <div class="category">
                <img src="images/bread.png" alt="Bakery Goods">
                <span>Bakery</span>
            </div>
            <div class="category">
                <img src="images/laundry.png" alt="Household Items">
                <span>Household</span>
            </div>
            <div class="category">
                <img src="images/tissue.png" alt="Paper Products">
                <span>Paperware</span>
            </div>
        </div>
    </section>

 
    <section class="hot-products">
        <h2>HOT PRODUCTS</h2>
        <div class="product-grid">
            <div class="product">
                <img src="images/babySpinach.png" alt="Baby Spinach">
                <h3>Baby Spinach</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="images/rasberries.jpg" alt="Raspberries">
                <h3>Raspberries</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="images/blueberries.jpg" alt="Blueberries">
                <h3>Blueberries (FRZ)</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="images/eggs.png" alt="Large Eggs">
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
                <img src="images/spinach.jpg" alt="Baby Spinach">
                <h3>Baby Spinach</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="images/raspberries.jpg" alt="Raspberries">
                <h3>Raspberries</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="images/bread.jpg" alt="Bread">
                <h3>Bread</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </div>
            <div class="product">
                <img src="images/eggs.jpg" alt="Large Eggs">
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