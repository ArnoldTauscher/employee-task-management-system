--- BENUTZERN
CREATE TABLE users (                                /* INT (Es ist völlig in Ordnung und sogar empfohlen, einfach INT ohne Größenangabe zu verwenden.) */       
    id INT AUTO_INCREMENT PRIMARY KEY,              /* Auto-increment allows a unique number to be generated automatically when a new record is inserted into a table. */       
    full_name VARCHAR(50) NOT NULL,                 /* The PRIMARY KEY constraint uniquely identifies each record in a table. */    
    username VARCHAR(50) NOT NULL,                  /* A VARIABLE length string. */
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employee') NOT NULL,        /* A string object that can have only one value, chosen from a list of possible values. */
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  /* Mit DEFAULT CURRENT_TIMESTAMP kann der Spalte automatisch der aktuelle Zeitstempel bei der Erstellung eines neuen Datensatzes zugewiesen werden. */
);

--- AUFGABEN
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    assigned_to INT,
    status ENUM('pending', 'in progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

--- BENACHRICHTIGUNGEN
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    recipient INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    is_read BOOLEAN DEFAULT FALSE
);