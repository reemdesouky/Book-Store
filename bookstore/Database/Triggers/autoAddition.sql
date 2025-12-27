DELIMITER //

CREATE TRIGGER confirm_publisher_order
AFTER UPDATE ON Publisher_Order
FOR EACH ROW
BEGIN
    -- Only act when order changes from Pending → Confirmed
    IF OLD.order_status = 'Pending' AND NEW.order_status = 'Confirmed' THEN
        UPDATE Book b
        JOIN Pub_Order_Contains p
        ON b.ISBN = p.ISBN
        SET b.quantity = b.quantity + p.quantity
        WHERE p.order_id = NEW.order_id;
    END IF;
END;
//
DELIMITER ;
