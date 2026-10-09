CREATE DATABASE IF NOT EXISTS eventhub_db CHARACTER SET utf8mb4; USE eventhub_db;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL UNIQUE,phone VARCHAR(20),password_hash VARCHAR(255) NOT NULL,role ENUM('user','admin') DEFAULT 'user',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE categories(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(60) NOT NULL UNIQUE);
CREATE TABLE events(id INT AUTO_INCREMENT PRIMARY KEY,category_id INT NOT NULL,title VARCHAR(150) NOT NULL,description TEXT,venue VARCHAR(120),city VARCHAR(80),organizer VARCHAR(100),start_at DATETIME NOT NULL,featured TINYINT(1) DEFAULT 0,status ENUM('draft','published','cancelled') DEFAULT 'published',
 FOREIGN KEY(category_id) REFERENCES categories(id),INDEX(start_at),INDEX(city));
CREATE TABLE ticket_types(id INT AUTO_INCREMENT PRIMARY KEY,event_id INT NOT NULL,name VARCHAR(60) NOT NULL,price DECIMAL(10,2) NOT NULL,total_qty INT NOT NULL,remaining_qty INT NOT NULL,FOREIGN KEY(event_id) REFERENCES events(id));
CREATE TABLE bookings(id INT AUTO_INCREMENT PRIMARY KEY,ref VARCHAR(20) NOT NULL UNIQUE,user_id INT NOT NULL,event_id INT NOT NULL,ticket_type_id INT NOT NULL,qty INT NOT NULL,subtotal DECIMAL(10,2),fee DECIMAL(10,2),total DECIMAL(10,2),status ENUM('pending','confirmed','cancelled') DEFAULT 'pending',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,cancelled_at DATETIME NULL,
 FOREIGN KEY(user_id) REFERENCES users(id),FOREIGN KEY(event_id) REFERENCES events(id),FOREIGN KEY(ticket_type_id) REFERENCES ticket_types(id));
CREATE TABLE attendees(id INT AUTO_INCREMENT PRIMARY KEY,booking_id INT NOT NULL,ticket_no VARCHAR(20) NOT NULL UNIQUE,name VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL,FOREIGN KEY(booking_id) REFERENCES bookings(id));
INSERT INTO categories(name) VALUES('Music'),('Sports'),('Workshops'),('Conferences'),('College Events'),('Technology'),('Arts and Culture'),('Business and Networking'),('Food and Festivals');
-- DEMO DATA (fictional)
INSERT INTO events(id,category_id,title,description,venue,city,organizer,start_at,featured) VALUES
(1,1,'Neon Nights Live','Demo concert with local bands.','City Arena','Mumbai','Demo Promoters',DATE_ADD(NOW(),INTERVAL 5 DAY),1),
(2,2,'City Marathon 10K','Demo community run.','Riverside Park','Pune','Demo Sports Club',DATE_ADD(NOW(),INTERVAL 9 DAY),0),
(3,3,'Pottery Weekend Workshop','Hands-on demo workshop.','Craft Studio','Delhi','Demo Crafts',DATE_ADD(NOW(),INTERVAL 12 DAY),0),
(4,4,'Future of Work Summit','Demo conference.','Grand Convention Hall','Bengaluru','Demo Events Ltd',DATE_ADD(NOW(),INTERVAL 20 DAY),1),
(5,5,'Campus Fest 2026','Demo college festival.','University Ground','Chennai','Demo College',DATE_ADD(NOW(),INTERVAL 15 DAY),0),
(6,6,'DevCon Hackathon','Demo 24h hackathon.','Tech Park Hub','Hyderabad','Demo Dev Community',DATE_ADD(NOW(),INTERVAL 25 DAY),1),
(7,7,'Modern Art Expo','Demo exhibition.','Gallery One','Kolkata','Demo Arts Council',DATE_ADD(NOW(),INTERVAL 30 DAY),0),
(8,8,'Startup Mixer','Demo networking evening.','Skyline Lounge','Mumbai','Demo Biz Network',DATE_ADD(NOW(),INTERVAL 7 DAY),0),
(9,9,'Street Food Festival','Demo food festival.','Central Grounds','Delhi','Demo Foodies',DATE_ADD(NOW(),INTERVAL 18 DAY),1),
(10,1,'Acoustic Evenings','Demo unplugged show.','The Blue Room','Pune','Demo Promoters',DATE_ADD(NOW(),INTERVAL 11 DAY),0),
(11,6,'AI for Beginners Talk','Demo tech talk.','Innovation Centre','Bengaluru','Demo Dev Community',DATE_ADD(NOW(),INTERVAL 14 DAY),0),
(12,2,'Inter-College Football Cup','Demo tournament.','Sports Complex','Chennai','Demo College',DATE_ADD(NOW(),INTERVAL 22 DAY),0);
INSERT INTO ticket_types(event_id,name,price,total_qty,remaining_qty) SELECT id,'General Admission',(id%4)*10*5,100,100 FROM events;
INSERT INTO ticket_types(event_id,name,price,total_qty,remaining_qty) SELECT id,'VIP',((id%4)*10+25)*5,20,20 FROM events;
