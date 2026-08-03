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

CREATE TABLE grades (
  id INT AUTO_INCREMENT,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  grade DECIMAL(3, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (student_id),
  KEY (course_id),
  CONSTRAINT fk_grades_students FOREIGN KEY (student_id) REFERENCES students (id),
  CONSTRAINT fk_grades_courses FOREIGN KEY (course_id) REFERENCES courses (id)
);

CREATE TABLE user_roles (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  CONSTRAINT fk_user_roles_users FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE student_courses (
  id INT AUTO_INCREMENT,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (student_id),
  KEY (course_id),
  CONSTRAINT fk_student_courses_students FOREIGN KEY (student_id) REFERENCES students (id),
  CONSTRAINT fk_student_courses_courses FOREIGN KEY (course_id) REFERENCES courses (id)
);

CREATE TABLE teacher_courses (
  id INT AUTO_INCREMENT,
  teacher_id INT NOT NULL,
  course_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (teacher_id),
  KEY (course_id),
  CONSTRAINT fk_teacher_courses_teachers FOREIGN KEY (teacher_id) REFERENCES teachers (id),
  CONSTRAINT fk_teacher_courses_courses FOREIGN KEY (course_id) REFERENCES courses (id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO students (name, email) VALUES
('John Doe', 'john@example.com'),
('Jane Doe', 'jane@example.com');

INSERT INTO teachers (name, email) VALUES
('Teacher 1', 'teacher1@example.com'),
('Teacher 2', 'teacher2@example.com');

INSERT INTO courses (name, description) VALUES
('Course 1', 'This is course 1'),
('Course 2', 'This is course 2');

INSERT INTO grades (student_id, course_id, grade) VALUES
(1, 1, 85.00),
(1, 2, 90.00),
(2, 1, 70.00),
(2, 2, 80.00);

INSERT INTO user_roles (user_id, role) VALUES
(1, 'admin');

INSERT INTO student_courses (student_id, course_id) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2);

INSERT INTO teacher_courses (teacher_id, course_id) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2);