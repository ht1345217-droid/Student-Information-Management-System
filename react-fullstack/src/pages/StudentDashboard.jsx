import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip as RechartsTooltip, ResponsiveContainer, LineChart, Line } from 'recharts';
import { BookOpen, Calendar, Clock, Award, Bell } from 'lucide-react';

const performanceData = [
  { subject: 'Math', score: 85 },
  { subject: 'Science', score: 92 },
  { subject: 'History', score: 78 },
  { subject: 'English', score: 88 },
  { subject: 'CS', score: 95 },
];

const attendanceData = [
  { month: 'Jan', rate: 95 },
  { month: 'Feb', rate: 98 },
  { month: 'Mar', rate: 90 },
  { month: 'Apr', rate: 96 },
  { month: 'May', rate: 99 },
];

const StudentDashboard = () => {
  const { user } = useAuth();
  const [activeTab, setActiveTab] = useState('overview');

  return (
    <div className="bg-gray-50 dark:bg-dark-900 min-h-[calc(100vh-4rem)] p-4 sm:p-6 lg:p-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Student Dashboard</h1>
            <p className="text-gray-600 dark:text-gray-400">Welcome back, {user?.email || 'Student'}</p>
          </div>
          <div className="mt-4 md:mt-0 flex space-x-3">
            <button className="p-2 bg-white dark:bg-dark-800 rounded-full shadow-sm relative border border-gray-200 dark:border-dark-700">
              <Bell className="h-5 w-5 text-gray-600 dark:text-gray-300" />
              <span className="absolute top-0 right-0 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white dark:border-dark-800"></span>
            </button>
          </div>
        </div>

        {/* Dashboard Tabs */}
        <div className="flex space-x-2 border-b border-gray-200 dark:border-dark-700 mb-8 overflow-x-auto pb-2">
          {['overview', 'courses', 'attendance', 'transactions'].map(tab => (
            <button
              key={tab}
              onClick={() => setActiveTab(tab)}
              className={`px-4 py-2 rounded-lg font-medium capitalize whitespace-nowrap transition-colors ${activeTab === tab ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-800'}`}
            >
              {tab}
            </button>
          ))}
        </div>

        {activeTab === 'overview' && (
          <div className="space-y-6">
            {/* KPI Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              <div className="glass-panel p-6 rounded-2xl flex items-center">
                <div className="bg-blue-100 dark:bg-blue-900/30 p-4 rounded-xl mr-4"><BookOpen className="h-6 w-6 text-blue-600" /></div>
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">Enrolled Courses</p>
                  <p className="text-2xl font-bold text-gray-900 dark:text-white">5</p>
                </div>
              </div>
              <div className="glass-panel p-6 rounded-2xl flex items-center">
                <div className="bg-green-100 dark:bg-green-900/30 p-4 rounded-xl mr-4"><Award className="h-6 w-6 text-green-600" /></div>
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">Current GPA</p>
                  <p className="text-2xl font-bold text-gray-900 dark:text-white">3.8</p>
                </div>
              </div>
              <div className="glass-panel p-6 rounded-2xl flex items-center">
                <div className="bg-purple-100 dark:bg-purple-900/30 p-4 rounded-xl mr-4"><Calendar className="h-6 w-6 text-purple-600" /></div>
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">Attendance</p>
                  <p className="text-2xl font-bold text-gray-900 dark:text-white">96%</p>
                </div>
              </div>
              <div className="glass-panel p-6 rounded-2xl flex items-center">
                <div className="bg-orange-100 dark:bg-orange-900/30 p-4 rounded-xl mr-4"><Clock className="h-6 w-6 text-orange-600" /></div>
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">Upcoming Assignments</p>
                  <p className="text-2xl font-bold text-gray-900 dark:text-white">3</p>
                </div>
              </div>
            </div>

            {/* Charts Section */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div className="glass-panel p-6 rounded-2xl">
                <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Academic Performance</h3>
                <div className="h-64">
                  <ResponsiveContainer width="100%" height="100%">
                    <BarChart data={performanceData}>
                      <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#374151" opacity={0.2} />
                      <XAxis dataKey="subject" tick={{fill: '#6b7280'}} axisLine={false} tickLine={false} />
                      <YAxis tick={{fill: '#6b7280'}} axisLine={false} tickLine={false} />
                      <RechartsTooltip cursor={{fill: 'transparent'}} contentStyle={{borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)'}} />
                      <Bar dataKey="score" fill="#22c55e" radius={[4, 4, 0, 0]} />
                    </BarChart>
                  </ResponsiveContainer>
                </div>
              </div>

              <div className="glass-panel p-6 rounded-2xl">
                <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Attendance Rate</h3>
                <div className="h-64">
                  <ResponsiveContainer width="100%" height="100%">
                    <LineChart data={attendanceData}>
                      <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#374151" opacity={0.2} />
                      <XAxis dataKey="month" tick={{fill: '#6b7280'}} axisLine={false} tickLine={false} />
                      <YAxis domain={[0, 100]} tick={{fill: '#6b7280'}} axisLine={false} tickLine={false} />
                      <RechartsTooltip contentStyle={{borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)'}} />
                      <Line type="monotone" dataKey="rate" stroke="#3b82f6" strokeWidth={3} dot={{r: 4, strokeWidth: 2}} activeDot={{r: 6}} />
                    </LineChart>
                  </ResponsiveContainer>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Other tabs can be expanded here. Show placeholder for now. */}
        {activeTab !== 'overview' && (
           <div className="glass-panel p-8 rounded-2xl text-center">
             <div className="text-gray-500 dark:text-gray-400">
                <p className="text-lg">Module content for <span className="font-semibold capitalize text-primary-600">{activeTab}</span></p>
                <p className="text-sm mt-2">This is where the datatables and specific features would render.</p>
             </div>
           </div>
        )}
      </div>
    </div>
  );
};

export default StudentDashboard;
