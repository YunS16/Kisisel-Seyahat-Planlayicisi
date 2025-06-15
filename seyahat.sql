
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO destinations (name) VALUES
('İstanbul'), ('İzmir'), ('Antalya'), ('Ankara');


CREATE TABLE plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    destination_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
);


CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    date DATE,
    FOREIGN KEY (plan_id) REFERENCES plans(id)
);
