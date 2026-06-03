import { Link } from 'react-router-dom';
import { BookOpen, Users, Trophy, ArrowRight, CheckCircle } from 'lucide-react';

const Home = () => {
  return (
    <div className="flex flex-col min-h-screen">
      {/* Hero Section */}
      <section className="relative bg-primary-900 overflow-hidden min-h-[90vh] flex items-center justify-center">
        <div className="absolute inset-0 z-0">
          <img 
            src="https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=2086&q=80" 
            alt="University Campus" 
            className="w-full h-full object-cover filter brightness-[0.4]"
          />
        </div>
        <div className="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent z-0"></div>
        
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
          <div className="text-center max-w-4xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-1000">
            <h1 className="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white tracking-tight mb-8 leading-tight">
              Manage Your Academic Journey with <span className="text-primary-400">SIMS</span>
            </h1>
            <p className="text-xl sm:text-2xl text-gray-200 mb-10 leading-relaxed font-light">
              A comprehensive, modern, and intelligent Student Information Management System designed to streamline administrative tasks and enhance the student experience.
            </p>
            <div className="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
              <Link to="/signup" className="px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white rounded-lg text-lg font-bold transition-all shadow-lg hover:shadow-primary-500/50 flex items-center w-full sm:w-auto justify-center group">
                Apply Now <ArrowRight className="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" />
              </Link>
              <Link to="/login" className="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-lg text-lg font-bold transition-all w-full sm:w-auto text-center">
                Student Login
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-20 bg-gray-50 dark:bg-dark-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Why Choose SIMS?</h2>
            <p className="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Experience a seamless integration of technology and education tailored for modern institutions.</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {/* Feature Card 1 */}
            <div className="glass-panel p-8 rounded-2xl hover:-translate-y-2 transition-transform duration-300">
              <div className="bg-primary-100 dark:bg-primary-900/30 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <BookOpen className="h-8 w-8 text-primary-600" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">Academic Excellence</h3>
              <p className="text-gray-600 dark:text-gray-400">Track grades, manage courses, and access learning materials all in one centralized dashboard.</p>
            </div>
            
            {/* Feature Card 2 */}
            <div className="glass-panel p-8 rounded-2xl hover:-translate-y-2 transition-transform duration-300">
              <div className="bg-blue-100 dark:bg-blue-900/30 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <Users className="h-8 w-8 text-blue-600" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">Community Hub</h3>
              <p className="text-gray-600 dark:text-gray-400">Connect with peers, join clubs, and participate in campus events through our integrated social platform.</p>
            </div>

            {/* Feature Card 3 */}
            <div className="glass-panel p-8 rounded-2xl hover:-translate-y-2 transition-transform duration-300">
              <div className="bg-purple-100 dark:bg-purple-900/30 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <Trophy className="h-8 w-8 text-purple-600" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">Performance Analytics</h3>
              <p className="text-gray-600 dark:text-gray-400">Visualize your academic progress with interactive charts and get personalized AI recommendations.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Popular Courses Section */}
      <section className="py-20 bg-white dark:bg-dark-900">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Popular Courses</h2>
            <p className="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Explore our highly rated programs designed to prepare you for the modern workforce.</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {/* Course Card 1 */}
            <div className="glass-panel rounded-2xl overflow-hidden hover:-translate-y-2 transition-transform duration-300 flex flex-col">
              <div className="h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Computer Science" className="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
              </div>
              <div className="p-6 flex-1 flex flex-col">
                <div className="flex justify-between items-start mb-4">
                  <span className="px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-xs font-bold rounded-full">Technology</span>
                  <span className="flex items-center text-sm font-medium text-yellow-500"><BookOpen className="w-4 h-4 mr-1"/> 4.8</span>
                </div>
                <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Computer Science & Engineering</h3>
                <p className="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-1">Learn software engineering, algorithms, and artificial intelligence in this comprehensive modern program.</p>
                <Link to="/signup" className="text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 flex items-center">
                  Enroll Now <ArrowRight className="w-4 h-4 ml-1" />
                </Link>
              </div>
            </div>

            {/* Course Card 2 */}
            <div className="glass-panel rounded-2xl overflow-hidden hover:-translate-y-2 transition-transform duration-300 flex flex-col">
              <div className="h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Business Administration" className="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
              </div>
              <div className="p-6 flex-1 flex flex-col">
                <div className="flex justify-between items-start mb-4">
                  <span className="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-full">Business</span>
                  <span className="flex items-center text-sm font-medium text-yellow-500"><BookOpen className="w-4 h-4 mr-1"/> 4.9</span>
                </div>
                <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Business Administration</h3>
                <p className="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-1">Master modern marketing, finance, and management strategies to become a global business leader.</p>
                <Link to="/signup" className="text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 flex items-center">
                  Enroll Now <ArrowRight className="w-4 h-4 ml-1" />
                </Link>
              </div>
            </div>

            {/* Course Card 3 */}
            <div className="glass-panel rounded-2xl overflow-hidden hover:-translate-y-2 transition-transform duration-300 flex flex-col">
              <div className="h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Data Science" className="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
              </div>
              <div className="p-6 flex-1 flex flex-col">
                <div className="flex justify-between items-start mb-4">
                  <span className="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs font-bold rounded-full">Analytics</span>
                  <span className="flex items-center text-sm font-medium text-yellow-500"><BookOpen className="w-4 h-4 mr-1"/> 4.7</span>
                </div>
                <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">Data Science & Analytics</h3>
                <p className="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-1">Dive deep into machine learning, big data, and statistical analysis with hands-on real-world projects.</p>
                <Link to="/signup" className="text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 flex items-center">
                  Enroll Now <ArrowRight className="w-4 h-4 ml-1" />
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Stats Section */}
      <section className="py-20 bg-primary-600 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
              <div className="text-4xl font-extrabold mb-2">15k+</div>
              <div className="text-primary-100">Active Students</div>
            </div>
            <div>
              <div className="text-4xl font-extrabold mb-2">300+</div>
              <div className="text-primary-100">Expert Faculty</div>
            </div>
            <div>
              <div className="text-4xl font-extrabold mb-2">50+</div>
              <div className="text-primary-100">Programs</div>
            </div>
            <div>
              <div className="text-4xl font-extrabold mb-2">98%</div>
              <div className="text-primary-100">Placement Rate</div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
