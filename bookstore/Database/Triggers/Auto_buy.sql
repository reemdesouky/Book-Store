DELIMITER //

CREATE TRIGGER auto_reorder
BEFORE UPDATE ON Book
FOR EACH ROW
BEGIN
    DECLARE new_order_id INT;

    -- Trigger only if stock drops below threshold
    IF OLD.quantity >= OLD.Threshold AND NEW.quantity < NEW.Threshold THEN
        
        -- Insert a new publisher order (ensure admin_id=1 exists)
        INSERT INTO Publisher_Order (admin_id)
        VALUES (1);

        SET new_order_id = LAST_INSERT_ID();

        -- Insert the book in the new order with fixed quantity (e.g., 20)
        INSERT INTO Pub_Order_Contains (order_id, ISBN, quantity)
        VALUES (new_order_id, NEW.ISBN, 20);
    END IF;
END;
//
DELIMITER ;
