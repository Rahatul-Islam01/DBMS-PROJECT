CREATE TABLE users (
    userId VARCHAR(20) NOT NULL,
    name VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    password VARCHAR(100) DEFAULT NULL,
    dept VARCHAR(20) DEFAULT NULL,
    PRIMARY KEY (userId),
    UNIQUE KEY email (email)
);

INSERT INTO users ( userId,  name, email, password, dept ) VALUES
 ('0112330958','Rahat','rahat958@gmail.com','@123321','CSE'),
 ('0112230788','Himel','himel788@gmail.com','@456654','CSE'),
 ('0112230447','Toma','toma447@gmail.com','@789987','CSE'),
 ('0112330583','Fariya','fariya@gmail.com','@098890','CSE');

CREATE TABLE admin (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  PRIMARY KEY(id),
  UNIQUE KEY email (email)
);
INSERT INTO admin (name,email,password) VALUES
('rahat', 'admin999@gmail.com', 'admin0000');

CREATE TABLE course (
    id INT NOT NULL AUTO_INCREMENT,
    course_code VARCHAR(20) NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    trimester VARCHAR(20) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY course_code (course_code)
);
INSERT INTO course (course_code, course_name, credits, trimester) VALUES
('ENG 1011', 'English I', 3, '1st Trimester'),
('BDS 1201', 'History of the Emergence of Bangladesh', 2, '1st Trimester'),
('CSE 1110', 'Introduction to Computer Systems', 1, '1st Trimester'),
('MATH 1151', 'Fundamental Calculus', 3, '1st Trimester'),
('ENG 1013', 'English II', 3, '2nd Trimester'),
('CSE 1111', 'Structured Programming Language', 3, '2nd  Trimester'),
('CSE 1112', 'Structured Programming Language Laboratory', 1, '2nd  Trimester'),
('CSE 2213', 'Discrete Mathematics', 3, '2nd  Trimester'),
('MATH 2183', 'Calculus and Linear Algebra', 3, '3rd  Trimester'),
('PHY 2105', 'Physics', 3, '3rd  Trimester'),
('PHY 2106', 'Physics Lab', 1, '3rd  Trimester'),
('CSE 2215', 'Data Structure and Algorithms I', 3, '3rd  Trimester'),
('CSE 2216', 'Data Structure and Algorithms I Laboratory', 1, '3rd  Trimester');


CREATE TABLE resource (
  id INT NOT NULL AUTO_INCREMENT,
  Folder_name VARCHAR(100) NOT NULL,
  File_name VARCHAR(100) NOT NULL,
  File_path VARCHAR(100) NOT NULL,
  UploaderId VARCHAR(20) NOT NULL,
  resource_type VARCHAR(100) NOT NULL,
  Approve VARCHAR(20) NOT NULL DEFAULT 'no',
  PRIMARY KEY(id),
  KEY fk_uploader (UploaderId),
  CONSTRAINT fk_uploader FOREIGN KEY (UploaderId) REFERENCES users (userId)
);
INSERT INTO resource (Folder_name, File_name, File_path, UploaderId, resource_type, Approve) VALUES
('ENG 1011/MidTermQuestions', 'ENG 1011_Mid_251.pdf', 'File/ENG 1011/MidTermQuestions/ENG 1011_Mid_251.pdf', '0112230788', 'MidTermQuestions', 'yes'),
('CSE 1111/MidTermQuestions', 'CSE1111_Mid_243.pdf', 'File/CSE 1111/MidTermQuestions/CSE1111_Mid_243.pdf', '0112330958', 'MidTermQuestions', 'yes'),
('CSE 1111/MidTermSolutions', 'CSE1111_MidSolve_243.pdf', 'File/CSE 1111/MidTermSolutions/CSE1111_MidSolve_243.pdf', '0112330958', 'MidTermSolutions', 'yes'),
('CSE 2213/FinalQuestions', 'CSE2213_Final_241.pdf', 'File/CSE 2213/FinalQuestions/CSE2213_Final_241.pdf', '0112230447', 'FinalQuestions', 'yes'),
('MATH 1151/Notes', 'MATH1151_Final_Note.pdf', 'File/MATH 1151/Notes/MATH1151_Final_Note.pdf', '0112330583', 'Notes', 'yes'),
('MATH 2183/Notes', 'MATH2183_Final_Note.pdf', 'File/MATH 2183/Notes/MATH2183_Final_Note.pdf', '0112330583', 'Notes', 'yes'),
('PHY 2105/MidTermQuestions', 'PHY2105_Mid_251.pdf', 'File/PHY 2105/MidTermQuestions/PHY2105_Mid_251.pdf', '0112230788', 'MidTermQuestions', 'yes'),
('CSE 2215/MidTermSolutions', 'CSE2215_MidSolve_241.pdf', 'File/CSE 2215/MidTermSolutions/CSE2215_MidSolve_241.pdf', '0112230447', 'MidTermSolutions', 'yes');


CREATE TABLE examroutine (
  id INT NOT NULL AUTO_INCREMENT,
  dept VARCHAR(20) NOT NULL,
  course_code VARCHAR(100) NOT NULL,
  course_title VARCHAR(100) NOT NULL,
  section VARCHAR(100) NOT NULL,
  teacher VARCHAR(100) NOT NULL,
  exam_date VARCHAR(100) NOT NULL,
  exam_time VARCHAR(100) NOT NULL,
  room VARCHAR(100) NOT NULL,
  PRIMARY KEY(id)
);

INSERT INTO examroutine (dept, course_code, course_title, section, teacher, exam_date, exam_time, room) VALUES
('BSCSE', 'ENG 1011', 'English I', 'A', 'SA', 'February 1, 2026', '09:00 AM - 11:00 AM', '526'),
('BSCSE', 'CSE 1111', 'Structured Programming Language', 'B', 'RK', 'February 2, 2026', '09:00 AM - 11:00 AM', '322'),
('BSCSE', 'MATH 1151', 'Fundamental Calculus', 'C', 'NT', 'February 3, 2026', '09:00 AM - 11:00 AM', '625'),
('BSCSE', 'CSE 2213', 'Discrete Mathematics', 'C', 'KAS', 'February 3, 2026', '09:00 AM - 11:00 AM', '725'),
('BSCSE', 'BDS 1201', 'History of the Emergence of Bangladesh', 'AA', 'NA', 'February 3, 2026', '02:00 PM - 04:00 PM', '225'),
('BSCSE', 'PHY 2105', 'Physics', 'E', 'RR', 'February 4, 2026', '02:00 PM - 04:00 PM', '725'),
('BSCSE', 'ENG 1013', 'English II', 'O', 'ZA', 'February 4, 2026', '09:00 AM - 11:00 AM', '925'),
('BSCSE', 'CSE 2215', 'Data Structure and Algorithms I', 'B', 'RK', 'February 2, 2026', '09:00 AM - 11:00 AM', '622');


CREATE TABLE enroll (
  id INT NOT NULL AUTO_INCREMENT,
  course_code VARCHAR(20) NOT NULL,
  userId VARCHAR(20) NOT NULL,
  section VARCHAR(20) NOT NULL,
  PRIMARY KEY(id),
  KEY fk_code (course_code),
  CONSTRAINT fk_code FOREIGN KEY (course_code) REFERENCES course (course_code) ON DELETE CASCADE,
  KEY fk_enrolluser (userId),
  CONSTRAINT fk_enrolluser FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE

);

INSERT INTO enroll (course_code, userId, section) VALUES
('ENG 1011', '0112330958', 'A'),
('PHY 2105', '0112330958', 'E'),
('PHY 2105', '0112230788', 'E'),
('CSE 1111', '0112230788', 'B'),
('MATH 1151', '0112230447', 'C'),
('ENG 1011', '0112330583', 'A'),
('CSE 1111', '0112330958', 'B'),
('CSE 2215', '0112230447', 'B'),
('CSE 2213', '0112330583', 'C');

CREATE TABLE tasks (
  id INT NOT NULL AUTO_INCREMENT,
  userId VARCHAR(20) DEFAULT NULL,
  task_type VARCHAR(100) DEFAULT NULL,
  description VARCHAR(100) DEFAULT NULL,
  status VARCHAR(100) NOT NULL DEFAULT 'incomplete',
  PRIMARY KEY(id),
  CONSTRAINT fk_usertask FOREIGN KEY (userId) REFERENCES users (userId) ON DELETE CASCADE
);

INSERT INTO tasks (userId, task_type, description, status) VALUES
('0112330958', 'Assignment', 'SPL', 'complete'),
('0112330958', 'CT', 'PHY practice', 'incomplete'),
('0112230788', 'HW', 'DM Practice', 'complete'),
('0112230788', 'CT', 'PHY practice', 'incomplete'),
('0112230447', 'Assignment', 'spl', 'incomplete'),
('0112230447', 'Assignment', 'DSA', 'incomplete'),
('0112330583', 'Assignment', 'SPL mid solve', 'complete'),
('0112330583', 'HW', 'English', 'incomplete');




-----------------------------
-- Sample Queries
-----------------------------
SELECT e.userId, e.section, c.course_code, c.course_name, c.trimester
FROM enroll e
JOIN course c ON e.course_code = c.course_code
WHERE e.userId = '0112330958';
-----------------------------
SELECT *
FROM tasks
WHERE userId = '0112330958';
-----------------------------
SELECT DISTINCT userId
FROM tasks
WHERE status = 'incomplete';
-----------------------------
SELECT userId, name, email, dept
FROM users
WHERE dept = 'CSE';
-----------------------------
SELECT userId, COUNT(course_code) AS total_courses
FROM enroll
GROUP BY userId;
-----------------------------
SELECT course_code, course_title, exam_date, exam_time, room
FROM examroutine
ORDER BY exam_date;
-----------------------------
SELECT userId, course_code, section
FROM enroll
WHERE userId = '0112330958';
-----------------------------
INSERT INTO tasks (userId, task_type, description, status)
VALUES ('0112330958', 'Quiz', 'DSA Quiz 1', 'incomplete');
-----------------------------
UPDATE tasks
SET status = 'complete'
WHERE userId = '0112330958'
AND description = 'PHY practice';
-----------------------------
DELETE FROM enroll
WHERE userId = '0112230788'
AND course_code = 'PHY 2105';
-----------------------------
SELECT userId, COUNT(*) AS total_tasks
FROM tasks
GROUP BY userId;
-----------------------------
SELECT u.name, r.File_name, r.resource_type
FROM resource r
JOIN users u ON r.UploaderId = u.userId
WHERE r.Approve = 'yes';
-----------------------------
SELECT name, userId
FROM users
WHERE userId IN (
    SELECT userId
    FROM tasks
    WHERE status = 'incomplete'
);
-----------------------------
SELECT course_code
FROM enroll
GROUP BY course_code
HAVING COUNT(userId) = (
    SELECT MAX(total)
    FROM (
        SELECT COUNT(userId) AS total
        FROM enroll
        GROUP BY course_code
    ) AS temp
);


