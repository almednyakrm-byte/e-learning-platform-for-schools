CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE students (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE teachers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE courses (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_students (
  user_id INT NOT NULL,
  student_id INT NOT NULL,
  PRIMARY KEY (user_id, student_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE user_teachers (
  user_id INT NOT NULL,
  teacher_id INT NOT NULL,
  PRIMARY KEY (user_id, teacher_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

CREATE TABLE course_students (
  course_id INT NOT NULL,
  student_id INT NOT NULL,
  PRIMARY KEY (course_id, student_id),
  FOREIGN KEY (course_id) REFERENCES courses(id),
  FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE course_teachers (
  course_id INT NOT NULL,
  teacher_id INT NOT NULL,
  PRIMARY KEY (course_id, teacher_id),
  FOREIGN KEY (course_id) REFERENCES courses(id),
  FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO students (name, email) VALUES
('Student 1', 'student1@example.com'),
('Student 2', 'student2@example.com'),
('Student 3', 'student3@example.com');

INSERT INTO teachers (name, email) VALUES
('Teacher 1', 'teacher1@example.com'),
('Teacher 2', 'teacher2@example.com'),
('Teacher 3', 'teacher3@example.com');

INSERT INTO courses (name, description) VALUES
('Course 1', 'This is course 1'),
('Course 2', 'This is course 2'),
('Course 3', 'This is course 3');

INSERT INTO user_students (user_id, student_id) VALUES
(1, 1),
(1, 2),
(1, 3);

INSERT INTO user_teachers (user_id, teacher_id) VALUES
(1, 1),
(1, 2),
(1, 3);

INSERT INTO course_students (course_id, student_id) VALUES
(1, 1),
(1, 2),
(2, 3);

INSERT INTO course_teachers (course_id, teacher_id) VALUES
(1, 1),
(2, 2),
(3, 3);