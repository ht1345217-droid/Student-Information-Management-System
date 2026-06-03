# SIMS - Student Information Management System

A professional, modern, and fully responsive full-stack Student Information Management System built with React, Tailwind CSS, and Supabase (PostgreSQL).

## 🚀 Features

- **Modern UI/UX**: Premium design with glassmorphism, fluid animations, and responsive layouts.
- **Role-Based Authentication**: Secure login/signup for Students and Admins.
- **Dark/Light Mode**: Full theme support configured with Tailwind CSS.
- **Student Dashboard**: View grades, attendance, courses, and transactions with interactive Recharts.
- **Admin Dashboard**: Comprehensive management interface for Students, Users, Courses, and more.
- **AI Chatbot**: Integrated floating chatbot assistant for quick support.
- **Security**: Password hashing (handled by Supabase Auth), secure session management.
- **Accessibility**: ARIA labels, semantic HTML, keyboard navigation support.

## 🛠 Tech Stack

- **Frontend**: React.js, Vite, Tailwind CSS, React Router, Recharts, Lucide React
- **Backend & Database**: Supabase (PostgreSQL + Built-in API Routes & Auth)

---

## 💻 Environment Setup & Local Development

### Prerequisites
- Node.js (v18 or higher)
- npm or yarn
- A Supabase Account (Free Tier is sufficient)

### 1. Clone & Install Dependencies
1. Navigate to this project folder in your terminal.
2. Run `npm install` to install all frontend dependencies.

### 2. Database Setup (Supabase)
1. Create a new project on [Supabase](https://supabase.com/).
2. Go to the **SQL Editor** in your Supabase dashboard.
3. Copy the contents of `schema.sql` (found in the root of this project) and run it. This will create all necessary tables and insert dummy data.
4. Go to **Authentication -> Providers** and ensure Email/Password sign-in is enabled.

### 3. Environment Variables
1. Create a `.env` file in the root of the frontend directory.
2. Add your Supabase credentials (found in Supabase Settings -> API):
   ```env
   VITE_SUPABASE_URL=your_supabase_project_url
   VITE_SUPABASE_ANON_KEY=your_supabase_anon_key
   ```
*(Note: If you do not set these up, the application will fallback to a Mock Authentication mode for demonstration purposes using `admin@sims.edu` and `student@sims.edu`.)*

### 4. Run the Application
1. Run `npm run dev` in your terminal.
2. Open `http://localhost:5173` in your browser.

---

## 🌐 Deployment Guide (Free Hosting)

### Frontend Deployment (Vercel or Netlify)

**Option 1: Vercel (Recommended)**
1. Push your code to a GitHub repository.
2. Go to [Vercel.com](https://vercel.com/) and sign in with GitHub.
3. Click "Add New Project" and import your repository.
4. Set the Framework Preset to **Vite**.
5. In the **Environment Variables** section, add your `VITE_SUPABASE_URL` and `VITE_SUPABASE_ANON_KEY`.
6. Click **Deploy**. Vercel will automatically build and host your site with a free SSL certificate.

**Option 2: Netlify**
1. Push your code to GitHub.
2. Go to [Netlify.com](https://netlify.com/) and click "Add new site" -> "Import an existing project".
3. Select your GitHub repository.
4. Build Command: `npm run build` | Publish directory: `dist`
5. Add your Environment Variables under Advanced build settings.
6. Click **Deploy Site**.

### Backend Deployment
By using **Supabase**, your PostgreSQL database, Authentication, and API Routes are already hosted and managed in the cloud for free. You do not need a separate backend deployment (like Render/Heroku) because Supabase acts as your complete Backend-as-a-Service (BaaS).

---

## 📂 Project Structure

```
/react-fullstack
├── schema.sql              # PostgreSQL Database Schema
├── package.json            # Dependencies
├── vite.config.js          # Vite configuration
├── tailwind.config.js      # Tailwind theme & colors
├── src/
│   ├── main.jsx            # Application Entry Point
│   ├── App.jsx             # Routing Configuration
│   ├── index.css           # Global Styles & Utility Classes
│   ├── lib/
│   │   └── supabase.js     # Supabase Client Initialization
│   ├── context/
│   │   ├── AuthContext.jsx # Global Authentication State
│   │   └── ThemeContext.jsx# Dark/Light Mode State
│   ├── components/         # Reusable UI Components (Navbar, Footer, Chatbot)
│   └── pages/              # Main Route Pages
│       ├── Home.jsx        # Landing Page
│       ├── About.jsx       # About Us
│       ├── Contact.jsx     # Contact Form
│       ├── Login.jsx       # User Login
│       ├── Signup.jsx      # User Registration
│       ├── StudentDashboard.jsx # Student Portal
│       └── admin/          
│           └── AdminDashboard.jsx # Admin Management Portal
```
