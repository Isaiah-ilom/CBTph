-- SQLite Schema for E-Exam CBT System

CREATE TABLE IF NOT EXISTS admins (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  access_code TEXT NOT NULL,
  name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS choices (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  course_id INTEGER NOT NULL,
  qus_no INTEGER NOT NULL,
  ans_image TEXT,
  is_correct INTEGER NOT NULL DEFAULT 0,
  text TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS current_students (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  student_reg TEXT NOT NULL,
  start_time TEXT,
  exam_id INTEGER NOT NULL,
  type TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS custom_exams (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  total_time INTEGER NOT NULL,
  no_of_qus INTEGER NOT NULL,
  year TEXT,
  status INTEGER DEFAULT 1,
  created_date TEXT
);

CREATE TABLE IF NOT EXISTS c_students_ans (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  exam_id INTEGER NOT NULL,
  student_id INTEGER NOT NULL,
  subject_id INTEGER NOT NULL,
  answer TEXT NOT NULL,
  qus_no INTEGER NOT NULL,
  is_correct INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS departments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS exams (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  subject_id INTEGER NOT NULL,
  year TEXT NOT NULL,
  total_time INTEGER NOT NULL,
  no_of_qus INTEGER NOT NULL DEFAULT 40,
  status INTEGER DEFAULT 1,
  type TEXT
);

CREATE TABLE IF NOT EXISTS instructions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  value TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS pins (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  pin TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS pin_logins (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  pin TEXT NOT NULL,
  reg_no TEXT NOT NULL,
  usage_count INTEGER NOT NULL DEFAULT 1,
  last_used TEXT
);

CREATE TABLE IF NOT EXISTS questions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  course_id INTEGER NOT NULL,
  qus_no INTEGER NOT NULL,
  qus_image TEXT,
  text TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS registered_exams (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  student_id INTEGER,
  subject_id INTEGER NOT NULL,
  student_reg TEXT
);

CREATE TABLE IF NOT EXISTS score (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  student_id INTEGER NOT NULL,
  exam_id INTEGER NOT NULL,
  score REAL NOT NULL
);

CREATE TABLE IF NOT EXISTS scores (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  student_id INTEGER NOT NULL,
  subject_id INTEGER NOT NULL,
  exam_id INTEGER NOT NULL,
  score REAL NOT NULL,
  type TEXT,
  percentage REAL,
  exam_date TEXT
);

CREATE TABLE IF NOT EXISTS settings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  value TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS students (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  first_name TEXT,
  last_name TEXT,
  password TEXT,
  email TEXT,
  phone TEXT,
  gender TEXT,
  reg_no TEXT,
  institution TEXT,
  profile_pic TEXT DEFAULT 'default.png',
  subject_1 TEXT,
  subject_2 TEXT,
  subject_3 TEXT,
  subject_4 TEXT
);

CREATE TABLE IF NOT EXISTS students_ans (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  exam_id INTEGER NOT NULL,
  student_id INTEGER NOT NULL,
  subject_id INTEGER NOT NULL,
  answer TEXT NOT NULL,
  qus_no INTEGER NOT NULL,
  is_correct INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS subjects (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  subject_code TEXT
);

CREATE TABLE IF NOT EXISTS teachers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  staff_id TEXT NOT NULL,
  password TEXT NOT NULL,
  biometrics TEXT
);

-- Insert default admin
INSERT OR IGNORE INTO admins (id, access_code, name) VALUES (1, '12345', 'Administrator');

-- Insert default settings
INSERT OR IGNORE INTO settings (id, name, value) VALUES 
(1, 'site_name', 'E-Exam CBT System'),
(2, 'exam_duration', '60'),
(3, 'questions_per_exam', '40'),
(4, 'institution_name', 'E-Exam Institution'),
(5, 'enable_calculator', '1'),
(6, 'show_result', '1'),
(7, 'allow_review', '1'),
(8, 'exam_type', 'jamb'),
(9, 'current_year', '2024'),
(10, 'welcome_message', 'Welcome to E-Exam CBT System');

-- Insert sample subjects
INSERT OR IGNORE INTO subjects (id, name, subject_code) VALUES 
(1, 'English Language', 'ENG'),
(2, 'Mathematics', 'MTH'),
(3, 'Physics', 'PHY'),
(4, 'Chemistry', 'CHM'),
(5, 'Biology', 'BIO'),
(6, 'Economics', 'ECO'),
(7, 'Government', 'GOV'),
(8, 'Literature', 'LIT');

-- Insert instructions
INSERT OR IGNORE INTO instructions (id, value) VALUES (1, 'Read each question carefully before answering. Select the best option from the choices provided.');

-- Insert a sample student for testing
INSERT OR IGNORE INTO students (id, first_name, last_name, email, reg_no, password) VALUES 
(1, 'Test', 'Student', 'test@example.com', 'TEST001', '098f6bcd4621d373cade4e832627b4f6');
