DELIMITER //

CREATE TRIGGER deduct_stock_after_sale
BEFORE INSERT ON Order_Item
FOR EACH ROW
BEGIN
    -- Check for enough stock first
    IF (SELECT quantity FROM Book WHERE ISBN = NEW.ISBN) < NEW.quantity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Not enough stock for this order item';
    END IF;

    -- Stock will be deducted automatically after this insert
    UPDATE Book
    SET quantity = quantity - NEW.quantity
    WHERE ISBN = NEW.ISBN;
END;
//
DELIMITER ;
