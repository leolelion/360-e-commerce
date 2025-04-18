<?php
session_start();

include 'config.php';
require_once 'classes/ActivityTracker.php';

$tracker = new App\ActivityTracker($pdo);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product not found.");
}

$product_id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_url, 
                                  c.category_name, v.vendor_name 
                           FROM Products p
                           JOIN Categories c ON p.category_id = c.category_id
                           JOIN Vendors v ON p.vendor_id = v.vendor_id
                           WHERE p.product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }

    $tracker->logProductView($product_id, $product['name']);

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

try {
    $stmt = $pdo->prepare("SELECT r.review_id, r.rating, r.comment, r.created_at, 
                              CONCAT(u.first_name, ' ', u.last_name) AS username 
                       FROM Reviews r
                       JOIN Users u ON r.user_id = u.user_id
                       WHERE r.product_id = ?
                       ORDER BY r.created_at DESC");
    $stmt->execute([$product_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching reviews: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars(substr($product['description'], 0, 160)) ?>">
    <title><?= htmlspecialchars($product['name']) ?> | Product Details</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/product.css">
    <link rel="preload" href="<?= htmlspecialchars('../assets/images/' . basename($product['image_url'])) ?>" as="image">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="product-page">
        <section class="product-container" itemscope itemtype="https://schema.org/Product">
            <div class="product-header">
                <figure class="product-image-container">
                    <img src="<?= htmlspecialchars('../assets/images/' . basename($product['image_url'])) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         class="product-image"
                         itemprop="image">
                </figure>
                
                <div class="product-info" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <h1 itemprop="name"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="product-meta">
                        <p><strong>Vendor:</strong> <span itemprop="brand"><?= htmlspecialchars($product['vendor_name']) ?></span></p>
                        <p><strong>Category:</strong> <span itemprop="category"><?= htmlspecialchars($product['category_name']) ?></span></p>
                        <p itemprop="description"><strong>Description:</strong> <?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>
                    
                    <div class="product-purchase">
                        <p class="price" itemprop="price" content="<?= $product['price'] ?>">
                            <strong>Price:</strong> $<?= number_format($product['price'], 2) ?>
                        </p>
                        <p class="availability" itemprop="availability" content="<?= $product['stock_quantity'] > 0 ? 'InStock' : 'OutOfStock' ?>">
                            <strong>Stock:</strong> <?= $product['stock_quantity'] > 0 ? "In Stock" : "Out of Stock" ?>
                        </p>
                        
                        <form action="cart.php" method="post" class="add-to-cart-form" id="add-to-cart-form">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            
                            <label for="quantity">Quantity:</label>
                            <select id="quantity" name="quantity">
                                <?php for ($i = 1; $i <= min(10, $product['stock_quantity']); $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            
                            <button type="submit" class="btn" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                                Add to Cart
                            </button>
                            <div id="cart-message" class="message"></div>
                        </form>
                        
                        <a href="index.php" class="btn back-btn">Back to Products</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="reviews-container" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
            <h2>Customer Reviews</h2>
            
            <?php if ($reviews): ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <article class="review" itemprop="review" itemscope itemtype="https://schema.org/Review">
                            <div class="review-header">
                                <h3 itemprop="author"><?= htmlspecialchars($review['username']) ?></h3>
                                <div class="review-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                    <meta itemprop="ratingValue" content="<?= htmlspecialchars($review['rating']) ?>">
                                    <span>⭐ <?= htmlspecialchars($review['rating']) ?>/5</span>
                                </div>
                            </div>
                            <p itemprop="reviewBody"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <time datetime="<?= htmlspecialchars(date('c', strtotime($review['created_at']))) ?>" 
                                  class="review-date" itemprop="datePublished">
                                <?= htmlspecialchars($review['created_at']) ?>
                            </time>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-reviews">No reviews yet. Be the first to leave one!</p>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form id="review-form" class="review-form">
                    <h3>Write a Review</h3>
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <select name="rating" id="rating" required>
                            <option value="">Select rating</option>
                            <option value="5">⭐ 5 - Excellent</option>
                            <option value="4">⭐ 4 - Good</option>
                            <option value="3">⭐ 3 - Average</option>
                            <option value="2">⭐ 2 - Poor</option>
                            <option value="1">⭐ 1 - Terrible</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Review:</label>
                        <textarea name="comment" id="comment" placeholder="Write your review here..." required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-review-btn">Submit Review</button>
                    <div id="review-message" class="message"></div>
                    <div id="reviews-remaining" class="reviews-remaining"></div>
                </form>
            <?php else: ?>
                <p class="login-prompt">Please <a href="login.php">log in</a> to leave a review.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    
    <script>
    // Function to fetch and display reviews
    async function fetchAndDisplayReviews() {
        try {
            const response = await fetch(`api/get_reviews.php?product_id=<?= $product_id ?>`);
            const data = await response.json();
            
            if (response.ok) {
                const reviewsList = document.querySelector('.reviews-list');
                const noReviewsMsg = document.querySelector('.no-reviews');
                
                if (data.reviews.length === 0) {
                    if (!noReviewsMsg) {
                        const reviewsContainer = document.querySelector('.reviews-container');
                        const newNoReviewsMsg = document.createElement('p');
                        newNoReviewsMsg.className = 'no-reviews';
                        newNoReviewsMsg.textContent = 'No reviews yet. Be the first to leave one!';
                        reviewsContainer.insertBefore(newNoReviewsMsg, document.querySelector('.review-form'));
                    }
                    return;
                }
                
                if (noReviewsMsg) {
                    noReviewsMsg.remove();
                }
                
                if (!reviewsList) {
                    const reviewsContainer = document.querySelector('.reviews-container');
                    const newReviewsList = document.createElement('div');
                    newReviewsList.className = 'reviews-list';
                    reviewsContainer.insertBefore(newReviewsList, document.querySelector('.review-form'));
                }
                
                // Clear existing reviews
                document.querySelector('.reviews-list').innerHTML = '';
                
                // Add all reviews
                data.reviews.forEach(review => {
                    const reviewElement = document.createElement('article');
                    reviewElement.className = 'review';
                    reviewElement.innerHTML = `
                        <div class="review-header">
                            <h3>${review.username}</h3>
                            <div class="review-rating">
                                <span>⭐ ${review.rating}/5</span>
                            </div>
                        </div>
                        <p>${review.comment}</p>
                        <time datetime="${review.created_at}" class="review-date">
                            ${new Date(review.created_at).toLocaleDateString()}
                        </time>
                    `;
                    document.querySelector('.reviews-list').appendChild(reviewElement);
                });
            }
        } catch (error) {
            console.error('Error fetching reviews:', error);
        }
    }

    // Function to handle review submission
    async function handleReviewSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const messageDiv = document.getElementById('review-message');
        const reviewsRemainingDiv = document.getElementById('reviews-remaining');
        
        // Disable submit button and show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        messageDiv.textContent = '';
        messageDiv.className = 'message';
        
        try {
            const formData = new FormData(form);
            const response = await fetch('api/submit_review.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok) {
                // Reset form
                form.reset();
                messageDiv.textContent = 'Review submitted successfully!';
                messageDiv.className = 'message success';
                
                // Update reviews remaining
                if (data.reviews_remaining > 0) {
                    reviewsRemainingDiv.textContent = `You can submit ${data.reviews_remaining} more review${data.reviews_remaining > 1 ? 's' : ''} for this product.`;
                    reviewsRemainingDiv.className = 'reviews-remaining info';
                } else {
                    reviewsRemainingDiv.textContent = 'You have reached the maximum number of reviews for this product.';
                    reviewsRemainingDiv.className = 'reviews-remaining warning';
                    submitBtn.disabled = true;
                }
                
                // Fetch and display updated reviews
                await fetchAndDisplayReviews();
            } else {
                messageDiv.textContent = data.error || 'Error submitting review';
                messageDiv.className = 'message error';
            }
        } catch (error) {
            console.error('Error:', error);
            messageDiv.textContent = 'An error occurred while submitting the review. Please try again.';
            messageDiv.className = 'message error';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Review';
        }
    }

    // Add event listener for review form submission
    document.getElementById('review-form')?.addEventListener('submit', handleReviewSubmit);

    // Fetch reviews initially
    fetchAndDisplayReviews();

    // Set up periodic review updates (every 30 seconds)
    setInterval(fetchAndDisplayReviews, 30000);

    // Add to cart functionality
    document.getElementById('add-to-cart-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const messageDiv = document.getElementById('cart-message');
        
        // Disable submit button and show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';
        messageDiv.textContent = '';
        messageDiv.className = 'message';
        
        try {
            const formData = new FormData(form);
            const response = await fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok) {
                messageDiv.textContent = 'Product added to cart successfully!';
                messageDiv.className = 'message success';
                
                // Update cart count in header
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const currentCount = parseInt(cartCount.textContent) || 0;
                    cartCount.textContent = currentCount + parseInt(formData.get('quantity'));
                }
            } else {
                messageDiv.textContent = data.error || 'Error adding product to cart';
                messageDiv.className = 'message error';
            }
        } catch (error) {
            console.error('Error:', error);
            messageDiv.textContent = 'An error occurred while adding to cart. Please try again.';
            messageDiv.className = 'message error';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add to Cart';
        }
    });
    </script>
</body>
</html>