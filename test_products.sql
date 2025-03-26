INSERT INTO Products (name, description, price, stock_quantity, category_id) VALUES
('Large Eggs', 'Farm fresh large eggs, dozen', 4.99, 100, (SELECT category_id FROM Categories WHERE category_name = 'Eggs')),
('Organic Eggs', 'Organic free-range eggs', 6.49, 75, (SELECT category_id FROM Categories WHERE category_name = 'Eggs')),

('Whole Wheat Bread', 'Freshly baked whole wheat bread', 3.99, 50, (SELECT category_id FROM Categories WHERE category_name = 'Baked Goods')),
('Chocolate Cake', 'Rich chocolate cake, serves 8', 24.99, 20, (SELECT category_id FROM Categories WHERE category_name = 'Baked Goods')),

('Baby Spinach', 'Fresh organic baby spinach', 3.49, 40, (SELECT category_id FROM Categories WHERE category_name = 'Other')),
('Raspberries', 'Fresh raspberries, 6oz package', 4.99, 30, (SELECT category_id FROM Categories WHERE category_name = 'Other')),
('Blueberries (FRZ)', 'Frozen wild blueberries, 16oz', 5.99, 45, (SELECT category_id FROM Categories WHERE category_name = 'Other')),

('Whole Milk', 'Fresh whole milk, gallon', 3.79, 60, (SELECT category_id FROM Categories WHERE category_name = 'Milk')),
('Almond Milk', 'Unsweetened almond milk, half gallon', 3.29, 35, (SELECT category_id FROM Categories WHERE category_name = 'Milk')),

('Boneless Chicken Breast', 'Fresh boneless skinless chicken breast', 8.99, 25, (SELECT category_id FROM Categories WHERE category_name = 'Chicken')),
('Ground Beef', '80/20 ground beef, 1lb package', 5.99, 30, (SELECT category_id FROM Categories WHERE category_name = 'Beef'));