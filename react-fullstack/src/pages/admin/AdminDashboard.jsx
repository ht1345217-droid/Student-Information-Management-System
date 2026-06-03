import { useState } from 'react';
import { Routes, Route, Link, useLocation } from 'react-router-dom';
import { LayoutDashboard, Users, UserCog, BookOpen, CreditCard, Activity, FileText, ChevronRight, Download } from 'lucide-react';
import { AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

const adminStats = [
  { name: 'Jan', students: 400, revenue: 2400 },
  { name: 'Feb', students: 300, revenue: 1398 },
  { name: 'Mar', students: 200, revenue: 9800 },
  { name: 'Apr', students: 278, revenue: 3908 },
  { name: 'May', students: 189, revenue: 4800 },
  { name: 'Jun', students: 239, revenue: 3800 },
];

const mockStudents = [
  { id: '1', name: 'Alice Smith', email: 'alice@example.com', status: 'Active', enrolled: '2023-09-01' },
  { id: '2', name: 'Bob Johnson', email: 'bob@example.com', status: 'Inactive', enrolled: '2022-09-01' },
  { id: '3', name: 'Charlie Brown', email: 'charlie@example.com', status: 'Active', enrolled: '2024-01-15' },
];

const AdminDashboard = () => {
  const location = useLocation();
  const currentPath = location.pathname.split('/').pop() || 'dashboard';

  const menuItems = [
    { id: 'admin', label: 'Dashboard', icon: LayoutDashboard, path: '/admin' },
    { id: 'students', label: 'Manage Students', icon: Users, path: '/admin/students' },
    { id: 'users', label: 'Manage Users', icon: UserCog, path: '/admin/users' },
    { id: 'courses', label: 'Courses/Categories', icon: BookOpen, path: '/admin/courses' },
    { id: 'transactions', label: 'Transactions', icon: CreditCard, path: '/admin/transactions' },
    { id: 'attendance', label: 'Attendance', icon: Activity, path: '/admin/attendance' },
    { id: 'reports', label: 'Reports', icon: FileText, path: '/admin/reports' },
  ];

  return (
    <div className="flex min-h-[calc(100vh-4rem)] bg-gray-50 dark:bg-dark-900">
      {/* Sidebar */}
      <aside className="w-64 glass-panel border-r border-gray-200 dark:border-dark-700 hidden md:block rounded-none shadow-none z-10">
        <div className="p-4 py-6">
          <p className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 px-3">Admin Menu</p>
          <nav className="space-y-1">
            {menuItems.map((item) => {
              const Icon = item.icon;
              const isActive = location.pathname === item.path;
              return (
                <Link
                  key={item.id}
                  to={item.path}
                  className={`flex items-center px-3 py-2.5 rounded-lg transition-colors text-sm font-medium ${
                    isActive 
                      ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400' 
                      : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-800'
                  }`}
                >
                  <Icon className={`mr-3 h-5 w-5 ${isActive ? 'text-primary-600' : 'text-gray-400'}`} />
                  {item.label}
                </Link>
              );
            })}
          </nav>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
        <Routes>
          <Route path="/" element={<DashboardOverview />} />
          <Route path="/students" element={<ManageModule title="Manage Students" data={mockStudents} />} />
          <Route path="/users" element={<ManageModule title="Manage Users" data={[]} />} />
          <Route path="/courses" element={<ManageModule title="Manage Courses" data={[]} />} />
          <Route path="/transactions" element={<ManageModule title="Transactions" data={[]} />} />
          <Route path="*" element={<div className="glass-panel p-8 text-center rounded-2xl text-gray-500">Module Under Construction</div>} />
        </Routes>
      </main>
    </div>
  );
};

const DashboardOverview = () => (
  <div className="space-y-6 animate-in fade-in">
    <div className="flex justify-between items-center mb-6">
      <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Admin Overview</h1>
      <button className="btn-secondary flex items-center text-sm">
        <Download className="w-4 h-4 mr-2" /> Export PDF
      </button>
    </div>

    {/* Widget grid */}
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      {[{label: 'Total Students', value: '1,245', icon: Users, color: 'blue'},
        {label: 'Active Staff', value: '84', icon: UserCog, color: 'purple'},
        {label: 'Today Revenue', value: '$4,520', icon: CreditCard, color: 'green'},
        {label: 'Avg Attendance', value: '92%', icon: Activity, color: 'orange'}
      ].map((stat, i) => {
        const Icon = stat.icon;
        return (
          <div key={i} className="glass-panel p-6 rounded-2xl">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-gray-500 dark:text-gray-400">{stat.label}</p>
                <p className="text-2xl font-bold text-gray-900 dark:text-white mt-1">{stat.value}</p>
              </div>
              <div className={`bg-${stat.color}-100 dark:bg-${stat.color}-900/30 p-3 rounded-xl`}>
                <Icon className={`w-6 h-6 text-${stat.color}-600`} />
              </div>
            </div>
          </div>
        )
      })}
    </div>

    {/* Chart */}
    <div className="glass-panel p-6 rounded-2xl">
      <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Growth Analytics</h3>
      <div className="h-80 w-full">
        <ResponsiveContainer>
          <AreaChart data={adminStats} margin={{ top: 10, right: 30, left: 0, bottom: 0 }}>
            <defs>
              <linearGradient id="colorStudents" x1="0" y1="0" x2="0" y2="1">
                <stop offset="5%" stopColor="#22c55e" stopOpacity={0.8}/>
                <stop offset="95%" stopColor="#22c55e" stopOpacity={0}/>
              </linearGradient>
            </defs>
            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#374151" opacity={0.2}/>
            <XAxis dataKey="name" tick={{fill: '#6b7280'}} axisLine={false} tickLine={false}/>
            <YAxis tick={{fill: '#6b7280'}} axisLine={false} tickLine={false}/>
            <Tooltip contentStyle={{borderRadius: '8px', border: 'none'}}/>
            <Area type="monotone" dataKey="students" stroke="#22c55e" fillOpacity={1} fill="url(#colorStudents)" />
          </AreaChart>
        </ResponsiveContainer>
      </div>
    </div>
  </div>
);

// Generic CRUD Module Layout
const ManageModule = ({ title, data }) => (
  <div className="space-y-6 animate-in fade-in">
    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center">
      <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-0">{title}</h1>
      <div className="flex space-x-2">
        <input type="text" placeholder="Search..." className="input-field py-1.5 text-sm w-48" />
        <button className="btn-primary py-1.5 text-sm">Add New</button>
      </div>
    </div>

    <div className="glass-panel rounded-2xl overflow-hidden">
      <div className="overflow-x-auto">
        <table className="w-full text-left text-sm text-gray-500 dark:text-gray-400">
          <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-dark-800 dark:text-gray-300 border-b border-gray-200 dark:border-dark-700">
            <tr>
              <th scope="col" className="px-6 py-4">ID</th>
              <th scope="col" className="px-6 py-4">Name</th>
              <th scope="col" className="px-6 py-4">Email</th>
              <th scope="col" className="px-6 py-4">Status</th>
              <th scope="col" className="px-6 py-4">Action</th>
            </tr>
          </thead>
          <tbody>
            {data.length > 0 ? data.map((item) => (
              <tr key={item.id} className="bg-white dark:bg-dark-900 border-b border-gray-100 dark:border-dark-800 hover:bg-gray-50 dark:hover:bg-dark-800">
                <td className="px-6 py-4 font-medium text-gray-900 dark:text-white">{item.id}</td>
                <td className="px-6 py-4">{item.name}</td>
                <td className="px-6 py-4">{item.email}</td>
                <td className="px-6 py-4">
                  <span className={`px-2.5 py-1 rounded-full text-xs font-medium ${item.status === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'}`}>
                    {item.status}
                  </span>
                </td>
                <td className="px-6 py-4 space-x-2">
                  <button className="text-primary-600 hover:text-primary-800 font-medium">Edit</button>
                  <button className="text-red-600 hover:text-red-800 font-medium">Delete</button>
                </td>
              </tr>
            )) : (
              <tr>
                <td colSpan="5" className="px-6 py-8 text-center text-gray-500">No data available.</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
      
      {/* Pagination Mock */}
      <div className="p-4 border-t border-gray-200 dark:border-dark-700 flex justify-between items-center text-sm">
        <span className="text-gray-500">Showing 1 to {data.length} of {data.length} entries</span>
        <div className="flex space-x-1">
          <button className="px-3 py-1 rounded border border-gray-300 dark:border-dark-700 disabled:opacity-50" disabled>Prev</button>
          <button className="px-3 py-1 rounded border border-gray-300 dark:border-dark-700 bg-primary-50 dark:bg-primary-900/30 text-primary-600">1</button>
          <button className="px-3 py-1 rounded border border-gray-300 dark:border-dark-700 disabled:opacity-50" disabled>Next</button>
        </div>
      </div>
    </div>
  </div>
);

export default AdminDashboard;
