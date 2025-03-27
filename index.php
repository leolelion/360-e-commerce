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
            <a href="product.php?item=baby_spinach" class="product">
                <img src="images/babySpinach.png" alt="Baby Spinach">
                <h3>Baby Spinach</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </a>
            <a href="product.php?item=blueberries" class="product">
                <img src="images/blueberries.jpg" alt="Baby Spinach">
                <h3>Blueberries</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </a>
            <a href="product.php?item=raspberries" class="product">
                <img src="images/rasberries.jpg" alt="Baby Spinach">
                <h3>Raspberries</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </a>
            <a href="product.php?item=eggs" class="product">
                <img src="images/eggs2.png" alt="Baby Spinach">
                <h3>Eggs</h3>
                <p>Jayleaf Farm</p>
                <p>277 g Box - $15.49</p>
            </a>
        </div>
    </section>


    <section class="all-products">
        <h2>MORE PRODUCTS</h2>
        <div class="product-grid">
            <a href="product.php?item=laundry-detergent" class="product">
                <img src="images/laundryDetergent.png" alt="Laundry Detergent">
                <h3>Laundry Detergent</h3>
                <p>Purex</p>
                <p>4.43L - $15.97</p>
            </a>
            <a href="product.php?item=oreo-minis" class="product">
                <img src="images/oreoMini.png" alt="Oreo Mini's">
                <h3>Oreo Mini's</h3>
                <p>OREO</p>
                <p>6 pack (150 g) - $3.28</p>
            </a>
            <a href="product.php?item=strawberry-croissant" class="product">
                <img src="images/strawBerryCroissant.png" alt="Strawberry Cream Cheese Croissant">
                <h3>Strawberry Cream Cheese Croissant</h3>
                <p>Your Fresh Market</p>
                <p>6 pieces (460 g) - $4.97</p>
            </a>
            <a href="product.php?item=dr-pepper-zero" class="product">
                <img src="images/drPepper.png" alt="Dr. Pepper Zero Sugar">
                <h3>Dr. Pepper Zero Sugar</h3>
                <p>Dr. Pepper</p>
                <p>12 Cans x 335L (4.26L) - $7.78</p>
            </a>
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