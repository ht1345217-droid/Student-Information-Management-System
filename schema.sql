-- SIMS Database Schema (PostgreSQL / Supabase)

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- USERS TABLE
CREATE TABLE IF NOT EXISTS users (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    email VARCHAR(255) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'student' CHECK (role IN ('admin', 'student', 'teacher')),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- STUDENTS PROFILE TABLE
CREATE TABLE IF NOT EXISTS student_profiles (
    user_id UUID PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    enrollment_number VARCHAR(50) UNIQUE,
    date_of_birth DATE,
    address TEXT,
    phone_number VARCHAR(20),
    department VARCHAR(100),
    current_gpa DECIMAL(3,2) DEFAULT 0.00
);

-- COURSES TABLE
CREATE TABLE IF NOT EXISTS courses (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    description TEXT,
    credits INTEGER NOT NULL,
    teacher_id UUID REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- ENROLLMENTS TABLE (Many-to-Many: Students & Courses)
CREATE TABLE IF NOT EXISTS enrollments (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    student_id UUID REFERENCES users(id) ON DELETE CASCADE,
    course_id UUID REFERENCES courses(id) ON DELETE CASCADE,
    semester VARCHAR(20),
    grade VARCHAR(2),
    enrolled_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, course_id)
);

-- ATTENDANCE TABLE
CREATE TABLE IF NOT EXISTS attendance (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    enrollment_id UUID REFERENCES enrollments(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    status VARCHAR(10) CHECK (status IN ('Present', 'Absent', 'Late', 'Excused')),
    recorded_by UUID REFERENCES users(id)
);

-- TRANSACTIONS / FEES TABLE
CREATE TABLE IF NOT EXISTS transactions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    student_id UUID REFERENCES users(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    type VARCHAR(50) NOT NULL, -- e.g., 'Tuition', 'Library Fee'
    status VARCHAR(20) DEFAULT 'Pending' CHECK (status IN ('Pending', 'Paid', 'Failed')),
    due_date DATE,
    paid_date DATE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- SAMPLE DUMMY DATA

-- Insert Admin
INSERT INTO users (id, email, first_name, last_name, role) 
VALUES ('11111111-1111-1111-1111-111111111111', 'admin@sims.edu', 'System', 'Admin', 'admin') ON CONFLICT DO NOTHING;

-- Insert Student
INSERT INTO users (id, email, first_name, last_name, role) 
VALUES ('22222222-2222-2222-2222-222222222222', 'student@sims.edu', 'John', 'Doe', 'student') ON CONFLICT DO NOTHING;

INSERT INTO student_profiles (user_id, enrollment_number, department, current_gpa)
VALUES ('22222222-2222-2222-2222-222222222222', 'ENR-2023-001', 'Computer Science', 3.85) ON CONFLICT DO NOTHING;

-- Insert Course
INSERT INTO courses (id, course_code, course_name, description, credits)
VALUES ('33333333-3333-3333-3333-333333333333', 'CS101', 'Introduction to Computer Science', 'Basic programming concepts.', 4) ON CONFLICT DO NOTHING;

-- Insert Enrollment
INSERT INTO enrollments (student_id, course_id, semester)
VALUES ('22222222-2222-2222-2222-222222222222', '33333333-3333-3333-3333-333333333333', 'Fall 2023') ON CONFLICT DO NOTHING;

-- Insert Transaction
INSERT INTO transactions (student_id, amount, type, status, due_date)
VALUES ('22222222-2222-2222-2222-222222222222', 1500.00, 'Tuition Fee', 'Pending', '2023-12-01') ON CONFLICT DO NOTHING;
