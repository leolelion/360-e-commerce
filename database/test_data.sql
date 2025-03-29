INSERT INTO Users (first_name, last_name, email, password, phone, address, profile_picture, role, created_at) VALUES
('John', 'Doe', 'john.doe@example.com', 'hashedpassword123', '555-1234', '123 Elm Street', 'profile1.jpg', 'user', NOW()),
('Jane', 'Smith', 'jane.smith@example.com', 'hashedpassword456', '555-5678', '456 Oak Avenue', 'profile2.jpg', 'user', NOW()),
('Admin', 'User', 'admin@example.com', 'adminpassword', '555-9999', '789 Maple Road', 'profile3.jpg', 'admin', NOW());

INSERT INTO Orders (user_id, total_price, order_status, created_at) VALUES
(1, 12.98, 'Pending', NOW()),
(2, 8.99, 'Shipped', NOW()),
(3, 18.97, 'Delivered', NOW());

INSERT INTO OrderItems (order_id, product_id, quantity, unit_price) VALUES
(1, (SELECT product_id FROM Products WHERE name = 'Large Eggs'), 2, 4.99),
(1, (SELECT product_id FROM Products WHERE name = 'Whole Wheat Bread'), 1, 3.99);

INSERT INTO OrderItems (order_id, product_id, quantity, unit_price) VALUES
(2, (SELECT product_id FROM Products WHERE name = 'Organic Chicken Breast'), 1, 8.99);

INSERT INTO OrderItems (order_id, product_id, quantity, unit_price) VALUES
(3, (SELECT product_id FROM Products WHERE name = 'Large Eggs'), 3, 4.99),
(3, (SELECT product_id FROM Products WHERE name = 'Whole Wheat Bread'), 2, 3.99);
