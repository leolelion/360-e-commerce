INSERT INTO Products (name, description, price, stock_quantity, category_id, vendor_id) VALUES
('Large Eggs', 'Farm fresh large eggs, dozen', 4.99, 100, (SELECT category_id FROM Categories WHERE category_name = 'Eggs'), 1),
('Organic Eggs', 'Organic free-range eggs', 6.49, 75, (SELECT category_id FROM Categories WHERE category_name = 'Eggs'), 2),

('Whole Wheat Bread', 'Freshly baked whole wheat bread', 3.99, 50, (SELECT category_id FROM Categories WHERE category_name = 'Baked Goods'), 3),
('Chocolate Cake', 'Rich chocolate cake, serves 8', 24.99, 20, (SELECT category_id FROM Categories WHERE category_name = 'Baked Goods'), 3),

('Baby Spinach', 'Fresh organic baby spinach', 3.49, 40, (SELECT category_id FROM Categories WHERE category_name = 'Other'), 2),
('Raspberries', 'Fresh raspberries, 6oz package', 4.99, 30, (SELECT category_id FROM Categories WHERE category_name = 'Other'), 2),
('Blueberries (FRZ)', 'Frozen wild blueberries, 16oz', 5.99, 45, (SELECT category_id FROM Categories WHERE category_name = 'Other'), 2),

('Whole Milk', 'Fresh whole milk, gallon', 3.79, 60, (SELECT category_id FROM Categories WHERE category_name = 'Milk'), 1),
('Almond Milk', 'Unsweetened almond milk, half gallon', 3.29, 35, (SELECT category_id FROM Categories WHERE category_name = 'Milk'), 1),

('Boneless Chicken Breast', 'Fresh boneless skinless chicken breast', 8.99, 25, (SELECT category_id FROM Categories WHERE category_name = 'Chicken'), 4),
('Ground Beef', '80/20 ground beef, 1lb package', 5.99, 30, (SELECT category_id FROM Categories WHERE category_name = 'Beef'), 4);
