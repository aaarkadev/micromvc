CREATE TABLE IF NOT EXISTS categories (
    id INT NOT NULL , 
    parent_id INT NOT NULL , 
    name VARCHAR(255) NOT NULL , 
    prio INT NOT NULL , 
    PRIMARY KEY (id)
); 

INSERT INTO categories (id, parent_id, name, prio) VALUES ('1', '0', 'Category1', '0');
INSERT INTO categories (id, parent_id, name, prio) VALUES ('2', '0', 'Category2','0');
INSERT INTO categories (id, parent_id, name, prio) VALUES ('3', '0', 'Category3', '99');
INSERT INTO categories (id, parent_id, name, prio) VALUES ('4', '0', 'Category4', '50'); 
INSERT INTO categories (id, parent_id, name, prio) VALUES ('5', '1', 'Subcat11', '0');
INSERT INTO categories (id, parent_id, name, prio) VALUES ('6', '1', 'Subcat12', '0');
INSERT INTO categories (id, parent_id, name, prio) VALUES ('7', '1', 'Subcat13', '99'); 
INSERT INTO categories (id, parent_id, name, prio) VALUES ('8', '6', 'Subcat121', '0'); 
INSERT INTO categories (id, parent_id, name, prio) VALUES ('9', '3', 'Subcat31', '0');

CREATE TABLE IF NOT EXISTS tovars (
    id INT NOT NULL , 
    category_id INT NOT NULL , 
    name VARCHAR(255) NOT NULL , 
    slug VARCHAR(255) NOT NULL , 
    price decimal  NOT NULL , 
    prio INT NOT NULL , 
    PRIMARY KEY (id)
); 

INSERT INTO tovars (id, category_id, name, slug, price, prio) VALUES ('1', '8', 'Tovar1', '', '123', '0');
INSERT INTO tovars (id, category_id, name, slug, price, prio) VALUES ('2', '8', 'Some Tovar number 2', '', '345', '0');
INSERT INTO tovars (id, category_id, name, slug, price, prio) VALUES ('3', '8', 'Tovar3', 'translit-tovar-three', '567', '99'); 
INSERT INTO tovars (id, category_id, name, slug, price, prio) VALUES ('4', '7', 'Tovar4', 'translit-tovar-four', '789', '0');
INSERT INTO tovars (id, category_id, name, slug, price, prio) VALUES ('5', '7', 'Some Tovar number 5', '', '987', '0'); 
INSERT INTO tovars (id, category_id, name, slug, price, prio) VALUES ('6', '6', 'Tovar6', '', '654', '0');
INSERT INTO tovars (id, category_id, name, slug, price, prio) VALUES ('7', '5', 'Tovar7', '', '432', '0');


ALTER TABLE categories ADD INDEX (parent_id);
ALTER TABLE categories ADD INDEX (prio);

ALTER TABLE tovars ADD INDEX (category_id);
ALTER TABLE tovars ADD INDEX (price);
ALTER TABLE tovars ADD INDEX (prio);
